<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CleanLobbyDesConsciences extends Command
{
    protected $signature = 'clean-lobby-des-consciences';

    protected $description = 'Clean Lobby des Consciences';

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
        $this->organisationIds = [9676, 9678, 9679, 9681, 9682, 9683, 9690, 9704, 9728, 9732, 9737, 9739, 9742, 9747, 9748, 9749, 9750, 9752, 9753, 9754, 9759, 9760, 9761, 9763, 9798, 9811, 9815, 9863, 9881, 9882, 9883, 9896, 9905, 9908, 9920, 9921, 9924, 9926, 9930, 9935, 9940, 9952, 9958, 9961, 9964, 9991, 10084, 10085, 10086, 10088, 10098, 10103, 10105, 10106, 10107, 10109, 10110, 10111, 10112, 10113, 10114, 10115, 10116, 10117, 10118, 10119, 10120, 10121, 10125, 10126, 10127, 10128, 10133, 10157, 10170, 10174, 10203, 10228, 10252, 10262, 10349, 10374, 10399, 10400, 10401, 10402, 10404, 10406, 10430, 10473, 10632];

        $this->missionIds = Mission::whereIn('structure_id', $this->organisationIds)
            ->pluck('id')->toArray();
        $this->outdatedMissionIds = Mission::whereIn('id', $this->missionIds)
            ->where('state', 'Validée')->outdated()->pluck('id')->toArray();
        $this->cancelledParticipationIds = Participation::whereIn('mission_id', $this->outdatedMissionIds)
            ->whereIn('state', ['En attente de validation', 'En cours de traitement'])->pluck('id')->toArray();

        $this->participationIds = Participation::whereIn('mission_id', $this->missionIds)->pluck('id')->toArray();
        $this->conversationIds = Conversation::whereIn('conversable_id', $this->participationIds)
            ->where('conversable_type', 'App\Models\Participation')->pluck('id')->toArray();

        $this->newResponsable = User::find(337602);
        $this->newOrganisationId = 7198;
    }

    public function handle()
    {
        $this->log();
        $this->closeOutdatedMissions();
        $this->assignMissionsToNewOrganization();
        $this->attachNewResponsableToConversations();
        $this->unregisterOrganizations();
        $this->deleteOrganizationMembers();
        $this->syncMissionsAlgolia();
    }

    private function log()
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/clean-lobby-des-consciences.log'),
        ])->debug(json_encode([
            'organisationIds' => $this->organisationIds,
            'missionIds' => $this->missionIds,
            'outdatedMissionIds' => $this->outdatedMissionIds,
            'cancelledParticipationIds' => $this->cancelledParticipationIds,
            'participationIds' => $this->participationIds,
            'conversationIds' => $this->conversationIds,
            'newResponsable' => $this->newResponsable,
            'newOrganisationId' => $this->newOrganisationId,
        ]));
    }

    private function closeOutdatedMissions()
    {
        $queryMissions = Mission::whereIn('id', $this->outdatedMissionIds);
        $queryParticipations = Participation::whereIn('id', $this->cancelledParticipationIds);
        $this->line("Passage des missions dont la date de fin est passée en 'Terminée'. Passage des participations 'En attente de validation' et 'En cours de traitement' en 'Annulée'");
        $this->info("{$queryMissions->count()} missions et {$queryParticipations->count()} participations sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $queryMissions->update(['state' => 'Terminée']);
            $queryParticipations->update(['state' => 'Annulée']);
        }
    }

    private function assignMissionsToNewOrganization()
    {
        $query = Mission::whereIn('id', $this->missionIds)
            ->where(function ($query) {
                $query->where('structure_id', '<>', $this->newOrganisationId)->orWhere('responsable_id', '<>', $this->newResponsable->profile->id);
            });
        $this->line("Rapatriement des missions vers l'organisation {$this->newOrganisationId} et assignation du nouveau responsable (Profile ID: {$this->newResponsable->profile->id})");
        $this->info("{$query->count()} missions sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $query->update([
                'structure_id' => $this->newOrganisationId,
                'responsable_id' => $this->newResponsable->profile->id,
            ]);
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
                        'read_at' => Carbon::now(),
                    ],
                    $conversation->conversable->profile->user->id,
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
        $this->line('Suppression des membres des organisations.');
        $this->info("{$query->count()} organisations sont concernées.");
        if ($this->confirm('Continuer ?')) {
            $progressBar = $this->output->createProgressBar($query->count());
            $progressBar->start();
            foreach ($query->cursor() as $organisation) {
                $users = [];
                $organisation->members->each(function ($member) use (&$users) {
                    $member->load('user');
                    $users[] = $member->user;
                });
                $organisation->members()->detach();
                foreach ($users as $user) {
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
        $this->line('Synchronisation des missions (scout:reimport et apiengagement:sync)');
        if ($this->confirm('Continuer ?')) {
            $this->info('Synchronisation des missions (scout:reimport) ...');
            Artisan::call('scout:reimport', [
                'searchable' => "App\Models\Mission",
            ]);
            $this->info('Synchronisation Api Engagement (apiengagement:sync) ...');
            Artisan::call('apiengagement:sync');
        }
    }
}
