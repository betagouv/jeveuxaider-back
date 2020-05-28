<?php

namespace App\Observers;

use App\Models\Profile;
use App\Models\Structure;
use App\Notifications\StructureWaitingValidation;
use App\Notifications\StructureSignaled;
use App\Notifications\StructureSubmitted;
use App\Notifications\StructureValidated;

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
            $structure->members()->attach($structure->user->profile, ['role' => 'responsable']);
        }

        if ($structure->state == 'En attente de validation') {
            if ($structure->user->profile) {
                $structure->user->profile->notify(new StructureWaitingValidation($structure));
            }
            if ($structure->department) {
                Profile::where('referent_department', $structure->department)->get()->map(function ($profile) use ($structure) {
                    $profile->notify(new StructureSubmitted($structure));
                });
            }
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
    }
}
