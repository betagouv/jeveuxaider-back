<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Structure;
use Illuminate\Console\Command;

class MigrateOrganisationMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-organisation-missions {id?*} {--origin=} {--destination=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate missions from an organisation to another one
                                {id : if present, only for these given mission ids}
                                {--origin= : The organisation origin ID}
                                {--destination= : The organisation destination ID}
                                ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();
        if (empty($options['origin']) || empty($options['destination'])) {
            if (empty($options['origin'])) {
                $this->error('Mandatory argument: --origin');
            }
            if (empty($options['destination'])) {
                $this->error('Mandatory argument: --destination');
            }

            return;
        }

        $structureOrigin = Structure::find($options['origin']);
        $structureDestination = Structure::find($options['destination']);
        if (! $structureOrigin || ! $structureDestination) {
            if (! $structureOrigin) {
                $this->error("This organisation {$options['origin']} doesnt exists!");
            }
            if (! $structureDestination) {
                $this->error("This organisation {$options['destination']} doesnt exists!");
            }

            return;
        }

        $missionsQuery = Mission::where('structure_id', $structureOrigin->id)->withTrashed();
        $ids = $this->argument('id');
        if (! empty($ids)) {
            $missionsQuery->whereIn('id', $ids);
        }

        $count = $missionsQuery->count();

        if (app()->runningInConsole()) {
            if ($this->confirm("{$count} missions(s) will be migrated from {$structureOrigin->name} to {$structureDestination->name}")) {
                $this->executeScript($count, $missionsQuery, $structureOrigin, $structureDestination);
            }
        } else {
            $this->executeScript($count, $missionsQuery, $structureOrigin, $structureDestination);
        }
    }

    private function executeScript($count, $missionsQuery, $structureOrigin, $structureDestination)
    {
        $addedMembers = [];

        // ATTENTION : L'ajout de membres dans une autre structure n'est plus possible si l'utilisateur a déjà une structure.
        // Ce script ne fonctionne plus pour la migration des membres

        // Migre le responsable de la mission dans la nouvelle structure.
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        foreach ($missionsQuery->cursor() as $mission) {
            $structureDestination->addMember($mission->responsable->user);
            $addedMembers[] = $mission->responsable;
            $bar->advance();
        }
        $bar->finish();
        $this->line(PHP_EOL);
        foreach ($addedMembers as $member) {
            $this->info($member->email.' #'.$member->id.' has been added to '.$structureDestination->name.' #'.$structureDestination->id);
        }

        // Migre les missions vers la nouvelle structure.
        $missionsQuery->update([
            'structure_id' => $structureDestination->id,
        ]);
        $this->info(PHP_EOL.'<options=bold;fg=blue>'.$count.' missions(s) has been migrated.</>');

        // Si les responsables migrés n'ont plus aucune mission dans l'ancienne structure,
        // les supprimer des membres.
        if (count($addedMembers) > 0) {
            $this->info('<options=bold;fg=blue>Begining cleaning of members in structure origin ('.$structureOrigin->name.')</>'.PHP_EOL);
            $deletedMembers = [];
            $bar = $this->output->createProgressBar(count($addedMembers));
            $bar->start();
            foreach ($addedMembers as $member) {
                $count = $structureOrigin->missions->ofResponsable($member->id)->count();
                if ($count == 0) {
                    $deletedMembers[] = $member;
                    $structureOrigin->deleteMember($member->user);
                    // Force contextable_id s'il correspondait à l'ancienne structure.
                    $user = $member->user;
                    if ($user->contextable_id == $structureOrigin->id) {
                        $user->contextable_id = $structureDestination->id;
                        // Prevent updated_at timestamp change.
                        $user->timestamps = false;
                        $user->saveQuietly();
                    }
                }
                $bar->advance();
            }
            $bar->finish();
            $this->line(PHP_EOL);
            foreach ($deletedMembers as $member) {
                $this->info($member->email.' #'.$member->id.' has been deleted from '.$structureOrigin->name.' #'.$structureOrigin->id);
            }
        }
    }
}
