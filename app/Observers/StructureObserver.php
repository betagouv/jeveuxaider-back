<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Territoire;
use App\Models\User;
use App\Notifications\RegisterUserResponsable;
use App\Notifications\StructureAssociationValidated;
use App\Notifications\StructureCollectivityValidated;
use App\Notifications\StructureSignaled;
use App\Notifications\StructureSubmitted;
use App\Notifications\StructureValidated;
use App\Services\ApiEngagement;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StructureBeingProcessed;
use App\Jobs\AirtableDeleteObject;
use App\Jobs\AirtableSyncObject;
use App\Models\Domaine;

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

        // Si admin -> Commentez pour éviter les plantages tant qu'on n'a pas pris la décision de ce qu'on fait
        // $user = Auth::guard('api')->user();
        // if ($user && $user->is_admin) {
        //     return;
        // }

        if ($structure->user->profile) {
            $structure->members()->attach($structure->user->profile, ['role' => 'responsable']);
        }

        // COLLECTIVITE
        if ($structure->statut_juridique === 'Collectivité') {
            $name = preg_replace("/(^Mairie (des|du|de|d')*)/mi", "", $structure->name);
            $territoire = Territoire::create([
                'structure_id' => $structure->id,
                'name' => $name,
                'suffix_title' => 'à ' . $name,
                'zips' => $structure->zip ? [$structure->zip] : [],
                'department' => $structure->department,
                'is_published' => false,
                'type' => 'city',
                'state' => 'waiting',
            ]);
            $responsable = $structure->responsables->first();
            if ($responsable) {
                $territoire->addResponsable($responsable);
            }
        }

        // Sync Sendinblue
        if (config('services.sendinblue.sync')) {
            SendinblueSyncUser::dispatch($structure->user);
        }

        // Sync Airtable
        if (config('services.airtable.sync')) {
            AirtableSyncObject::dispatch($structure);
        }
    }

    public function updated(Structure $structure)
    {
        // STATE
        $oldState = $structure->getOriginal('state');
        $newState = $structure->state;

        // RESPONSABLE
        $responsable = $structure->responsables->first();

        if ($oldState != $newState) {
            switch ($newState) {
                case 'En attente de validation':
                    if ($structure->user->profile) {
                        $notification = new RegisterUserResponsable($structure);
                        $structure->user->notify($notification);
                    }
                    if ($structure->department) {
                        Profile::where('referent_department', $structure->department)->get()->map(function ($profile) use ($structure) {
                            $profile->notify(new StructureSubmitted($structure));
                        });
                    }
                    break;
                case 'En cours de traitement':
                    if ($responsable) {
                        $responsable->notify(new StructureBeingProcessed($structure));
                    }
                    break;
                case 'Validée':
                    if ($responsable) {
                        $responsable->notify(new StructureValidated($structure));
                        if ($structure->statut_juridique == 'Collectivité') {
                            $responsable->notify(new StructureCollectivityValidated($structure));
                        } else {
                            $responsable->notify(new StructureAssociationValidated($structure));
                        }
                    }
                    // Update all missions linked to template to 'Validée' status
                    $structure->missions()->whereNotNull('template_id')->where("state", "En attente de validation")->get()->map(function ($mission) {
                        $mission->update(['state' => 'Validée']);
                    });
                    break;
                case 'Signalée':
                    if ($responsable) {
                        $responsable->notify(new StructureSignaled($structure));
                    }
                    // Update all missions to 'Signalée' status
                    Mission::with(['structure', 'participations', 'participations.conversation'])->where('structure_id', $structure->id)->get()->map(function ($mission) {
                        $mission->update(['state' => 'Signalée']);
                    });
                    //  Si territoire relié on dépublie
                    if ($structure->statut_juridique == 'Collectivité') {
                        $territoire = Territoire::where('structure_id', $structure->id)->first();
                        if ($territoire) {
                            $territoire->update([
                                'is_published' => false,
                                'state' => 'refused',
                            ]);
                        }
                    }
                    break;
                case 'Désinscrite':
                    $members = $structure->members;
                    $structure->members()->detach();

                    foreach ($members as $member) {
                        $user = User::find($member->user_id);
                        if ($user->context_role == 'responsable' && $user->contextable_id == $structure->id) {
                            $user->resetContextRole();
                        }
                    }

                    if ($structure->missions) {
                        foreach ($structure->missions->where("state", "En attente de validation") as $mission) {
                            $mission->update(['state' => 'Annulée']);
                        }

                        // Notifs OFF
                        Mission::where('structure_id', $structure->id)
                            ->outdated()
                            ->where('state', 'Validée')
                            ->update(['state' => 'Terminée']);

                        // Notifs ON
                        Mission::where('structure_id', $structure->id)
                            ->notOutdated()
                            ->where('state', 'Validée')
                            ->get()->map(function ($mission) {
                                $mission->update(['state' => 'Annulée']);
                            });

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

        // MAJ SENDINBLUE
        if (config('services.sendinblue.sync')) {
            if ($structure->isDirty('name')) {
                $structure->responsables->each(function ($profile, $key) {
                    $profile->load('user');
                    if ($profile->user) { // Parfois il n'y a pas de user car ce sont des profiles invités
                        SendinblueSyncUser::dispatch($profile->user);
                    }
                });
            }
        }

        // Update API Engagement
        if ($structure->canBeSendToApiEngagement()) {
            (new ApiEngagement())->syncAssociation($structure);
        }

        // Sync Airtable
        if (config('services.airtable.sync')) {
            AirtableSyncObject::dispatch($structure);
        }
    }

    public function saving(Structure $structure)
    {
        // On passe automatiquement la structure en Attente de validation
        // si elle a remplit les champs strictements necessaires (décorrélé des missing fields)
        if ($structure->state == 'Brouillon') {
            $mandatoryFields = ['address'];
            if (!in_array($structure->statut_juridique, ['Collectivité', 'Organisation publique'])) {
                $mandatoryFields[] = 'description';
            }

            $changeStatus = true;
            foreach ($mandatoryFields as $mandatoryField) {
                if (empty($structure->$mandatoryField)) {
                    $changeStatus = false;
                    break;
                }
            }

            if ($changeStatus) {
                $structure->state = 'En attente de validation';
            }
        }

        // On force les publics pour les collectivités
        if ($structure->statut_juridique == 'Collectivité') {
            $structure->publics_beneficiaires = array_keys(config('taxonomies.mission_publics_beneficiaires.terms'));
        }
    }

    public function saved(Structure $structure)
    {
        // On force les domaines pour les collectivités
        if ($structure->statut_juridique == 'Collectivité') {
            $domaines = Domaine::all();
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'structure_domaines'];
            });
            $structure->domaines()->sync($values);
        }
    }

    public function deleted(Structure $structure)
    {
        // Delete pending invitation
        $structure->invitations()->delete();

        // Detaching members from organisation
        $structure->responsables->map(function ($responsable) use ($structure) {
            $structure->deleteMember($responsable);
        });

        // Sync Airtable
        if (config('services.airtable.sync')) {
            AirtableDeleteObject::dispatch($structure);
        }
    }
}
