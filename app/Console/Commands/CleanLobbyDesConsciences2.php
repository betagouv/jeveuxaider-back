<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CleanLobbyDesConsciences2 extends Command
{
    protected $signature = 'clean-lobby-des-consciences-2';
    protected $description = "Clean Lobby des Consciences - 2";
    protected $organisationIds;
    protected $missionIds;
    protected $outdatedMissionIds;
    protected $participationIds;
    protected $cancelledParticipationIds;
    protected $newResponsable;
    protected $newOrganisationId;
    protected $conversationIds;

    public function __construct()
    {
        parent::__construct();
        $this->organisationIds = [9719, 9746, 9963, 9736, 9680, 9756, 9677, 9868, 9867, 9735, 9805, 10123];

        $this->missionIds = Mission::whereIn('structure_id', $this->organisationIds)
            ->pluck('id')->toArray();
        $this->participationIds = Participation::whereIn('mission_id', $this->missionIds)->pluck('id')->toArray();
        $this->conversationIds = Conversation::whereIn('conversable_id', $this->participationIds)
            ->where('conversable_type', 'App\Models\Participation')->pluck('id')->toArray();

        $this->newResponsable = User::find(337602);
        $this->newOrganisationId = 7198;
    }

    public function handle()
    {
        $this->log();
        $this->assignMissionsToNewOrganization();
        $this->adjustNbWantedVolunteers();
        $this->attachNewResponsableToConversations();
        $this->unregisterOrganizations();
        $this->deleteOrganizationMembers();
        $this->syncMissionsAlgolia();
    }

    private function log()
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/clean-lobby-des-consciences-2.log'),
        ])->debug(json_encode([
            'organisationIds' => $this->organisationIds,
            'missionIds' => $this->missionIds,
            'participationIds' => $this->participationIds,
            'conversationIds' => $this->conversationIds,
            'newResponsable' => $this->newResponsable,
            'newOrganisationId' => $this->newOrganisationId,
        ]));
    }

    private function assignMissionsToNewOrganization()
    {
        $query = Mission::whereIn('id', $this->missionIds)
            ->where(function($query) {
                $query->where('structure_id', '<>', $this->newOrganisationId)->orWhere('responsable_id', '<>', $this->newResponsable->profile->id);
            });
        $this->line("Rapatriement des missions vers l'organisation {$this->newOrganisationId} et assignation du nouveau responsable (Profile ID: {$this->newResponsable->profile->id})");
        $this->info("{$query->count()} missions sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $query->update([
                'structure_id' => $this->newOrganisationId,
                'responsable_id' => $this->newResponsable->profile->id
            ]);
        }
    }

    private function adjustNbWantedVolunteers()
    {
        // Passer à 15 bénévoles recherchés les missions qui ont moins de 15 candidatures actives
        // (en attente de validation, validée ou en cours de traitement)
        $query = Participation::whereIn('participations.state', Participation::ACTIVE_STATUS)
            ->whereHas('mission', function (Builder $query) {
                $query->whereIn('id', $this->missionIds)
                    ->where('participations_max', '>', 15)
                    ->whereIn('state', ['Validée', 'En attente de validation', 'En cours de traitement', 'Brouillon']);
            })
            ->join('missions', 'participations.mission_id', '=', 'missions.id')
            ->groupBy(['missions.id'])
            ->select([
                DB::raw('COUNT(participations.id) as nb_active_participations'),
                'missions.id as mission_id',
                'missions.participations_max as nb_max_participations'
            ])
            ->having(DB::raw('COUNT(participations.id)'), '<', 15)
            ->get();

        $results = $query->toArray();
        $this->line("Ajustement du nombre de bénévoles recherchés.");
        $this->info(count($results) . " missions sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $progressBar = $this->output->createProgressBar(count($results));
            $progressBar->start();
            foreach ($results as $result) {
                Mission::find($result['mission_id'])->update([
                    'participations_max' => 15,
                    'places_left' => 15 - $result['nb_active_participations']
                ]);
                $progressBar->advance();
            }
            $progressBar->finish();
            $this->newLine(2);
        }
    }

    private function attachNewResponsableToConversations()
    {
        $query = Conversation::whereIn('id', $this->conversationIds);
        $this->line("Ajout du nouveau responsable dans les conversations des participations (User ID: {$this->newResponsable->id}).");
        $this->info("{$query->count()} conversations sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $progressBar = $this->output->createProgressBar($query->count());
            $progressBar->start();
            foreach ($query->cursor() as $conversation) {
                // Seulement le nouveau responsable + participant
                // On force en lu pour le responsable
                $conversation->users()->sync([
                    $this->newResponsable->id => [
                        'read_at' => Carbon::now()
                    ],
                    $conversation->conversable->profile->user->id
                ]);
                $progressBar->advance();
            }
            $progressBar->finish();
            $this->newLine(2);
        }
    }

    private function unregisterOrganizations()
    {
        $query = Structure::whereIn('id', $this->organisationIds);
        $this->line("Passage des organisations en 'Désinscrite'.");
        $this->info("{$query->count()} organisations sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $query->update(['state' => 'Désinscrite']);
        }
    }

    private function deleteOrganizationMembers()
    {
        $query = Structure::whereIn('id', $this->organisationIds);
        $this->line("Suppression des membres des organisations.");
        $this->info("{$query->count()} organisations sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $progressBar = $this->output->createProgressBar($query->count());
            $progressBar->start();
            foreach ($query->cursor() as $organisation) {
                $users = [];
                $organisation->members->each(function($member) use (&$users) {
                    $member->load('user');
                    $users[] = $member->user;
                });
                $organisation->members()->detach();
                foreach($users as $user) {
                    $user->resetContextRole();
                }
                $progressBar->advance();
            }
            $progressBar->finish();
            $this->newLine(2);
        }
    }

    private function syncMissionsAlgolia()
    {
        $this->line("Synchronisation des missions (scout:reimport et apiengagement:sync)");
        if ($this->confirm('Continuer ?')) {
            $this->info("Synchronisation des missions (scout:reimport) ...");
            Artisan::call('scout:reimport', [
                'searchable' => "App\Models\Mission"
            ]);
            $this->info("Synchronisation Api Engagement (apiengagement:sync) ...");
            Artisan::call('apiengagement:sync');
        }
    }
}
