<?php

namespace App\Http\Controllers\Member4_Reehay;

use App\Http\Controllers\Controller;
use App\Models\Member4_Reehay\BloodRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BloodRequestController extends Controller
{
    /**
     * Display the requester's blood request dashboard (Feature R4).
     * Shows all requests grouped by status with live progress bars.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Auto-expire overdue requests before rendering
        $this->expireOverdueRequests();

        $requests = BloodRequest::where('user_id', $user->id)
            ->orderByRaw("CASE status
                WHEN 'open' THEN 1
                WHEN 'partial' THEN 2
                WHEN 'fulfilled' THEN 3
                WHEN 'expired' THEN 4
                ELSE 5 END")
            ->latest()
            ->get();

        $stats = [
            'open' => $requests->where('status', BloodRequest::STATUS_OPEN)->count(),
            'partial' => $requests->where('status', BloodRequest::STATUS_PARTIAL)->count(),
            'fulfilled' => $requests->where('status', BloodRequest::STATUS_FULFILLED)->count(),
            'expired' => $requests->where('status', BloodRequest::STATUS_EXPIRED)->count(),
        ];

        $view = view()->exists('request.show') ? 'request.show' : 'member4_reehay.request.show';

        return view($view, [
            'user' => $user,
            'requests' => $requests,
            'stats' => $stats,
            'bloodGroups' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        ]);
    }

    /**
     * Store a new blood request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:150',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'component_type' => 'required|string|in:whole_blood,red_cells,platelets,plasma',
            'units_needed' => 'required|integer|min:1|max:20',
            'hospital_name' => 'required|string|max:200',
            'city' => 'nullable|string|max:100',
            'urgency' => 'required|string|in:normal,urgent,critical',
            'deadline_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
            'contact_phone' => 'nullable|string|max:25',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = BloodRequest::STATUS_OPEN;

        BloodRequest::create($validated);

        return redirect()->route('blood.requests')
            ->with('success', 'Blood request created successfully! Status: OPEN — awaiting donor pledges.');
    }

    /**
     * Record a pledge (fulfill units) against a blood request.
     */
    public function fulfill(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'units' => 'required|integer|min:1',
        ]);

        $bloodRequest = BloodRequest::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if (in_array($bloodRequest->status, [BloodRequest::STATUS_FULFILLED, BloodRequest::STATUS_EXPIRED])) {
            return redirect()->route('blood.requests')
                ->with('error', 'This request is already ' . $bloodRequest->status . ' and cannot receive more pledges.');
        }

        $bloodRequest->fulfill($validated['units']);

        $statusMessage = $bloodRequest->status === BloodRequest::STATUS_FULFILLED
            ? 'Request FULFILLED! All units have been pledged.'
            : "Pledge recorded: {$bloodRequest->units_fulfilled}/{$bloodRequest->units_needed} units fulfilled.";

        return redirect()->route('blood.requests')
            ->with('success', $statusMessage);
    }

    /**
     * Manually expire a request (requester cancels or deadline override).
     */
    public function expire(int $id, Request $request): RedirectResponse
    {
        $bloodRequest = BloodRequest::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if ($bloodRequest->status === BloodRequest::STATUS_FULFILLED) {
            return redirect()->route('blood.requests')
                ->with('error', 'Cannot expire a fulfilled request.');
        }

        $bloodRequest->status = BloodRequest::STATUS_EXPIRED;

        // Deactivate emergency if active
        if ($bloodRequest->is_emergency) {
            $bloodRequest->is_emergency = false;
            $bloodRequest->is_broadcast_pinned = false;
        }

        $bloodRequest->save();

        return redirect()->route('blood.requests')
            ->with('info', "Request #{$id} has been marked as expired.");
    }

    /**
     * Auto-expire all overdue open/partial requests.
     * Called internally before rendering the dashboard.
     */
    private function expireOverdueRequests(): void
    {
        BloodRequest::whereIn('status', [BloodRequest::STATUS_OPEN, BloodRequest::STATUS_PARTIAL])
            ->where('deadline_at', '<=', Carbon::now())
            ->update([
                'status' => BloodRequest::STATUS_EXPIRED,
                'is_emergency' => false,
                'is_broadcast_pinned' => false,
            ]);
    }
}
