<?php

namespace App\Http\Controllers\Member4_Reehay;

use App\Http\Controllers\Controller;
use App\Models\Member4_Reehay\BloodRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SOSController extends Controller
{
    /**
     * Display the emergency broadcast dashboard (Feature R5).
     * Shows all pinned emergency alerts and allows escalation.
     */
    public function index(Request $request): View
    {
        // Auto-escalate any emergency requests that are due
        $this->autoEscalateAll();

        // Expire overdue emergency requests
        $this->expireOverdueEmergencies();

        // Pinned emergency broadcasts — visible to ALL active dashboard users
        $pinnedBroadcasts = BloodRequest::pinnedBroadcasts()
            ->with('user')
            ->orderByRaw("CASE emergency_level
                WHEN 'sos_critical' THEN 1
                WHEN 'urgent_escalated' THEN 2
                ELSE 3 END")
            ->latest()
            ->get();

        // All active emergency requests (for the requesting user)
        $myEmergencies = collect();
        if ($request->user()) {
            $myEmergencies = BloodRequest::where('user_id', $request->user()->id)
                ->emergency()
                ->latest()
                ->get();
        }

        // Stats for the dashboard header
        $stats = [
            'active_emergencies' => BloodRequest::emergency()->open()->count(),
            'total_pinned' => $pinnedBroadcasts->count(),
            'sos_critical' => $pinnedBroadcasts->where('emergency_level', BloodRequest::EMERGENCY_SOS_CRITICAL)->count(),
            'max_radius' => $pinnedBroadcasts->max('current_radius_km') ?? 0,
        ];

        $view = view()->exists('emergency.broadcast') ? 'emergency.broadcast' : 'member4_reehay.emergency.broadcast';

        return view($view, [
            'user' => $request->user(),
            'pinnedBroadcasts' => $pinnedBroadcasts,
            'myEmergencies' => $myEmergencies,
            'stats' => $stats,
        ]);
    }

    /**
     * Activate emergency escalation on a blood request.
     * Overrides batch throttles, starts radial sweep, and pins to all dashboards.
     */
    public function activate(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'emergency_level' => 'required|string|in:sos_critical,urgent_escalated',
            'contact_phone' => 'nullable|string|max:25',
        ]);

        $bloodRequest = BloodRequest::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if (in_array($bloodRequest->status, [BloodRequest::STATUS_FULFILLED, BloodRequest::STATUS_EXPIRED])) {
            return redirect()->route('emergency.broadcast')
                ->with('error', 'Cannot escalate a ' . $bloodRequest->status . ' request.');
        }

        if ($bloodRequest->is_emergency) {
            return redirect()->route('emergency.broadcast')
                ->with('info', 'This request is already in emergency mode.');
        }

        // Set contact phone if provided
        if (! empty($validated['contact_phone'])) {
            $bloodRequest->contact_phone = $validated['contact_phone'];
        }

        // Activate emergency: pin broadcast + set radius + override throttles
        $bloodRequest->activateEmergency($validated['emergency_level']);

        return redirect()->route('emergency.broadcast')
            ->with('success', 'EMERGENCY ACTIVATED! Request has been pinned to all active dashboards. Radial sweep started at ' . $bloodRequest->current_radius_km . ' km.');
    }

    /**
     * Manually trigger the next radial sweep escalation.
     */
    public function escalate(Request $request, int $id): RedirectResponse
    {
        $bloodRequest = BloodRequest::where('user_id', $request->user()->id)
            ->where('is_emergency', true)
            ->findOrFail($id);

        if ($bloodRequest->current_radius_km >= BloodRequest::RADIUS_TIER_3) {
            return redirect()->route('emergency.broadcast')
                ->with('info', 'Maximum radius (50 km) already reached. All nearby donors have been alerted.');
        }

        $previousRadius = $bloodRequest->current_radius_km;
        $bloodRequest->escalateRadius();

        return redirect()->route('emergency.broadcast')
            ->with('success', "Radial sweep expanded: {$previousRadius} km → {$bloodRequest->current_radius_km} km. Additional donors in the expanded zone are being notified.");
    }

    /**
     * Deactivate emergency mode on a request (resolved / false alarm).
     */
    public function deactivate(Request $request, int $id): RedirectResponse
    {
        $bloodRequest = BloodRequest::where('user_id', $request->user()->id)
            ->where('is_emergency', true)
            ->findOrFail($id);

        $bloodRequest->deactivateEmergency();

        return redirect()->route('emergency.broadcast')
            ->with('info', "Emergency mode deactivated for request #{$id}. Broadcast unpinned from dashboards.");
    }

    /**
     * Create a new blood request with immediate emergency escalation.
     * Used by hospital staff for direct SOS creation.
     */
    public function createSOS(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:150',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'component_type' => 'required|string|in:whole_blood,red_cells,platelets,plasma',
            'units_needed' => 'required|integer|min:1|max:20',
            'hospital_name' => 'required|string|max:200',
            'city' => 'nullable|string|max:100',
            'deadline_at' => 'required|date|after:now',
            'contact_phone' => 'nullable|string|max:25',
            'notes' => 'nullable|string|max:1000',
        ]);

        $bloodRequest = BloodRequest::create([
            'user_id' => $request->user()->id,
            'patient_name' => $validated['patient_name'],
            'blood_group' => $validated['blood_group'],
            'component_type' => $validated['component_type'],
            'units_needed' => $validated['units_needed'],
            'hospital_name' => $validated['hospital_name'],
            'city' => $validated['city'] ?? null,
            'urgency' => BloodRequest::URGENCY_CRITICAL,
            'deadline_at' => $validated['deadline_at'],
            'contact_phone' => $validated['contact_phone'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => BloodRequest::STATUS_OPEN,
        ]);

        // Immediately activate emergency escalation
        $bloodRequest->activateEmergency(BloodRequest::EMERGENCY_SOS_CRITICAL);

        return redirect()->route('emergency.broadcast')
            ->with('success', 'SOS EMERGENCY CREATED! Request is now pinned across all active dashboards with radial sweep at 10 km.');
    }

    /**
     * Auto-escalate emergency requests that are due for radial expansion.
     * 10 km → 25 km → 50 km every 10 minutes.
     */
    private function autoEscalateAll(): void
    {
        $emergencies = BloodRequest::emergency()
            ->open()
            ->where('current_radius_km', '<', BloodRequest::RADIUS_TIER_3)
            ->get();

        foreach ($emergencies as $emergency) {
            if ($emergency->needsRadialEscalation()) {
                $emergency->escalateRadius();
            }
        }
    }

    /**
     * Expire overdue emergency requests and unpin broadcasts.
     */
    private function expireOverdueEmergencies(): void
    {
        BloodRequest::emergency()
            ->whereIn('status', [BloodRequest::STATUS_OPEN, BloodRequest::STATUS_PARTIAL])
            ->where('deadline_at', '<=', Carbon::now())
            ->update([
                'status' => BloodRequest::STATUS_EXPIRED,
                'is_emergency' => false,
                'is_broadcast_pinned' => false,
            ]);
    }
}
