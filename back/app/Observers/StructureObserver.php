<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Collectivity;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Territoire;
use App\Notifications\CollectivityWaitingValidation;
use App\Notifications\RegisterUserResponsable;
use App\Notifications\StructureAssociationValidated;
use App\Notifications\StructureCollectivityValidated;
use App\Notifications\StructureSignaled;
use App\Notifications\StructureSubmitted;
use App\Notifications\StructureValidated;
use App\Services\ApiEngagement;
use Illuminate\Support\Facades\Notification;

class StructureObserver
{
    /**
     * Listen to the Structure created event.
     *
     * @param  \App\Models\Structure  $structure
     * @return void
     */
    public function created(Structure $structure)
    {

        if ($structure->user->profile) {
            $notification = new RegisterUserResponsable($structure);
            $structure->user->notify($notification);
        }

        if ($structure->user->profile) {
            $structure->members()->attach($structure->user->profile, ['role' => 'responsable']);
        }

        if ($structure->state == 'En attente de validation') {
            if ($structure->department) {
                Profile::where('referent_department', $structure->department)->get()->map(function ($profile) use ($structure) {
                    $profile->notify(new StructureSubmitted($structure));
                });
            }
        }

        if ($structure->statut_juridique == 'Collectivité') {
            $this->createTerritoire($structure);
        }
        if (config('app.env') === 'production') {
            SendinblueSyncUser::dispatch($structure->user);
        }
    }

    public function updated(Structure $structure)
    {
        // STATE
        $oldState = $structure->getOriginal('state');
        $newState = $structure->state;

        if ($oldState != $newState) {
            switch ($newState) {
                case 'Validée':
                    if ($structure->user->profile) {
                        $structure->user->profile->notify(new StructureValidated($structure));
                        if ($structure->statut_juridique == 'Collectivité') {
                            $structure->user->notify(new StructureCollectivityValidated($structure));
                        } else {
                            $structure->user->notify(new StructureAssociationValidated($structure));
                        }
                    }
                    if ($structure->missions) {
                        foreach ($structure->missions->where("state", "En attente de validation") as $mission) {
                            $mission->update(['state' => 'Validée']);
                        }
                        foreach ($structure->missions->where("state", "Validée") as $mission) {
                            $mission->searchable();
                        }
                    }



                    break;
                case 'Signalée':
                    if ($structure->user->profile) {
                        $structure->user->profile->notify(new StructureSignaled($structure));
                    }
                    if ($structure->missions) {
                        foreach ($structure->missions as $mission) {
                            $mission->update(['state' => 'Signalée']);
                        }
                    }
                    break;
                case 'Désinscrite':
                    $structure->members()->detach();
                    if ($structure->missions) {
                        foreach ($structure->missions->where("state", "En attente de validation") as $mission) {
                            $mission->update(['state' => 'Annulée']);
                        }
                        $structure->missions->unsearchable();
                    }
                    break;
            }
        }

        // DEPARTMENT
        $oldDepartment = $structure->getOriginal('department');
        $newDepartment = $structure->department;

        if ($oldDepartment != $newDepartment) {
            if ($structure->state == 'En attente de validation') {
                if ($structure->department) {
                    Profile::where('referent_department', $structure->department)->get()->map(function ($profile) use ($structure) {
                        $profile->notify(new StructureSubmitted($structure));
                    });
                }
            }
        }

        // STRUCTURE PUBLIQUE TYPE
        if (!$structure->getOriginal('statut_juridique') && $structure->statut_juridique) {
            if ($structure->statut_juridique == 'Collectivité') {
                $this->createTerritoire($structure);
            }
        }

        // Maj Sendinblue
        if (config('app.env') === 'production') {
            if ($structure->isDirty('name')) {
                $structure->responsables->each(function ($profile, $key) {
                    if ($profile->user) { // Parfois il n'y a pas de user car ce sont des profiles invités
                        SendinblueSyncUser::dispatch($profile->user);
                    }
                });
            }
        }

        // Update API Engagement (NOT READY YET)
        // if ($structure->canBeSendToApiEngagement()) {
        //     (new ApiEngagement())->syncAssociation($structure);
        // }
    }

    public function deleted(Structure $structure)
    {
        // Delete pending invitation
        $structure->invitations()->delete();
    }

    private function createTerritoire($structure)
    {
        ray('department', $structure);
        $territoire = Territoire::create([
            'structure_id' => $structure->id,
            'name' => $structure->city ?? $structure->name,
            'suffix_title' => 'à ' . $structure->city ?? $structure->name,
            'zips' => $structure->zip ? [$structure->zip] : [],
            'department' => $structure->department,
            'is_published' => false,
            'type' => 'city',
            'state' => 'waiting',
        ]);
        ray('territoire', $territoire);
        $territoire->save();
        $territoire->addResponsable($structure->user->profile);

        Notification::route('slack', config('services.slack.hook_url'))
            ->notify(new CollectivityWaitingValidation($territoire));
    }
}
