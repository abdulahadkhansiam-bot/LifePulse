<?php

namespace App\Console\Commands;

use App\Models\Member4_Reehay\BloodRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessBloodRequestsCommand extends Command
{
    protected $signature = 'blood:process-requests';

    protected $description = 'Auto-expire overdue blood requests and escalate emergency radial sweeps';

    public function handle(): int
    {
        $this->info('Processing blood requests...');

        // 1. Auto-expire overdue open/partial requests
        $expiredCount = BloodRequest::whereIn('status', [BloodRequest::STATUS_OPEN, BloodRequest::STATUS_PARTIAL])
            ->where('deadline_at', '<=', Carbon::now())
            ->update([
                'status' => BloodRequest::STATUS_EXPIRED,
                'is_emergency' => false,
                'is_broadcast_pinned' => false,
            ]);

        $this->line("  Expired {$expiredCount} overdue request(s).");

        // 2. Auto-escalate emergency radial sweeps
        $escalated = 0;
        $emergencies = BloodRequest::emergency()
            ->open()
            ->where('current_radius_km', '<', BloodRequest::RADIUS_TIER_3)
            ->get();

        foreach ($emergencies as $emergency) {
            if ($emergency->needsRadialEscalation()) {
                $previous = $emergency->current_radius_km;
                $emergency->escalateRadius();
                $this->line("  Escalated request #{$emergency->id}: {$previous} km → {$emergency->current_radius_km} km");
                $escalated++;
            }
        }

        $this->line("  Escalated {$escalated} emergency request(s).");
        $this->info('Blood request processing complete.');

        return Command::SUCCESS;
    }
}
