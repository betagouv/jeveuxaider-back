<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class CloseOrTransferResponsableMissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $organisation = $this->user->structures->first();

        if(!$organisation) {
            return;
        }

        if ($organisation->members->count() > 1) {
            $newResponsable = $organisation->members->where('id', '!=', $this->user->id)->first();
            $organisation->missions->where('responsable_id', $this->user->profile->id)->each(function ($mission) use ($newResponsable) {
                $mission->responsable_id = $newResponsable->profile->id;
                $mission->save();
            });
        } else {
            $organisation->state = 'Désinscrite';
            $organisation->save();
        }

    }
}
