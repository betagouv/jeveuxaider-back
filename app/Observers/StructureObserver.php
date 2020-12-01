<?php

namespace App\Observers;

use App\Models\Collectivity;
use App\Models\Profile;
use App\Models\Structure;
use App\Notifications\CollectivityWaitingValidation;
use App\Notifications\StructureSignaled;
use App\Notifications\StructureSubmitted;
use App\Notifications\StructureValidated;
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
            $this->createCollectivity($structure);
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

        // STRUCTURE PUBLIQUE TYPE
        $oldStructureType = $structure->getOriginal('statut_juridique');
        $newStructureType = $structure->statut_juridique;
        if ($oldStructureType != $newStructureType && $newStructureType == 'Collectivité') {
            $this->createCollectivity($structure);
        }
    }

    private function createCollectivity($structure)
    {
        // TODO : Enlever quand script terminé
        /*
        $collectivity = Collectivity::create([
            'name' => $structure->city ?? $structure->name,
            'zips' => $structure->zip ? [$structure->zip] : [],
            'structure_id' => $structure->id,
            'published' => false,
            'type' => 'commune',
            'state' => 'waiting'
        ]);
        $collectivity->save();
        */
        /*
        Notification::route('mail', ['achkar.joe@hotmail.fr', 'sophie.hacktiv@gmail.com', 'nassim.merzouk@beta.gouv.fr'])
        ->route('slack', 'https://hooks.slack.com/services/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')
        ->notify(new CollectivityWaitingValidation($collectivity));
        */
    }
}
