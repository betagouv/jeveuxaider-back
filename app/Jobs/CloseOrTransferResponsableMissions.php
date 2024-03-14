<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloseOrTransferResponsableMissions implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        $this->onQueue('low-tasks');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $organisation = $this->user->structures->first();

        if(!$organisation || $organisation->state == 'Signalée') {
            return;
        }

        if ($organisation->members->count() > 1) {
            $newResponsable = $organisation->members()->where('id', '!=', $this->user->id)->isActive()->first();
            if($newResponsable) {
                $organisation->missions->where('responsable_id', $this->user->profile->id)->each(function ($mission) use ($newResponsable) {
                    $mission->responsable_id = $newResponsable->profile->id;
                    $mission->save();
                });
            }
        } else {
            // View StructureObserver for more details
            $organisation->state = 'Désinscrite';
            $organisation->save();
        }
    }
}
