<?php

namespace App\Http\Controllers\Member3_Mashud;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member3_Mashud\StoreScreeningRequest;
use App\Models\Member3_Mashud\Screening;
use App\Models\User;
use App\Services\Member3_Mashud\ScreeningService;

class ScreeningController extends Controller
{
    // Show the form where staff enter the vitals.
    public function create()
    {
        $user = auth()->user();
        abort_unless($user->isHospital() || $user->isAdmin(), 403);

        // Only donors who have a profile card can be screened.
        $donors = User::whereHas('donorProfile')
            ->orderBy('name')
            ->get(['id', 'name', 'blood_group']);

        return view('screening.create', compact('donors'));
    }

    // Save the screening. The rules live in the service, not here.
    public function store(StoreScreeningRequest $request, ScreeningService $service)
    {
        $screening = $service->record(
            $request->validated() + ['screened_by' => $request->user()->id]
        );

        $message = $screening->outcome === Screening::OUTCOME_ACCEPTED
            ? 'Donor accepted.'
            : 'Donor deferred until ' . $screening->deferral_until->format('d M Y')
                . ' (' . $screening->reason . ').';

        return redirect()->route('screening.create')->with('status', $message);
    }
}