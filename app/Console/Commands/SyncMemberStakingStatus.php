<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\StakingDetail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-member-staking-status')]
#[Description('Synchronize member_details.status based on staking_details (Active, Deactive, or Temp)')]
class SyncMemberStakingStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting member staking status synchronization...');

        $members = MemberDetail::all();
        $updatedToActive = 0;
        $updatedToDeactive = 0;
        $updatedToTemp = 0;
        $unchanged = 0;

        foreach ($members as $member) {
            if ($member->status === 'Blocked') {
                $unchanged++;
                continue;
            }

            $hasActiveStaking = StakingDetail::where('memberid', $member->memberid)
                ->where('status', 'Active')
                ->exists();

            if ($hasActiveStaking) {
                if ($member->status !== 'Active') {
                    $member->status = 'Active';
                    $member->save();
                    $updatedToActive++;
                } else {
                    $unchanged++;
                }
                continue;
            }

            $hasAnyStaking = StakingDetail::where('memberid', $member->memberid)->exists();

            if ($hasAnyStaking) {
                // Member has staking records, but 0 are active -> Deactive!
                if ($member->status !== 'Deactive') {
                    $member->status = 'Deactive';
                    $member->save();
                    $updatedToDeactive++;
                } else {
                    $unchanged++;
                }
                continue;
            }

            // Member has 0 staking records ever -> Temp (Inactive)
            if ($member->status === 'Active') {
                // Was marked active without any staking -> revert to Temp
                $member->status = 'Temp';
                $member->save();
                $updatedToTemp++;
            } else {
                $unchanged++;
            }
        }

        $this->info("Synchronization complete.");
        $this->line(" - Updated to Active: {$updatedToActive}");
        $this->line(" - Updated to Deactive: {$updatedToDeactive}");
        $this->line(" - Updated to Temp (Inactive): {$updatedToTemp}");
        $this->line(" - Unchanged: {$unchanged}");

        return self::SUCCESS;
    }
}

