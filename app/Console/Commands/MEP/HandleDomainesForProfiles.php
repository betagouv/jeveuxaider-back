<?php

namespace App\Console\Commands\MEP;

use App\Models\Domainable;
use App\Models\Domaine;
use App\Models\Taggable;
use Illuminate\Console\Command;

class HandleDomainesForProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:handle-domaines-for-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Handle domaines for profiles";

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
        $this->info('Ce script va créer les liaisons des profiles vers les domaines (domainables)');

        if ($this->confirm('Continuer ?')) {
            $this->handleProfileDomains();
        }
    }

    private function handleProfileDomains()
    {
        $query = Taggable::where('taggable_type', "App\Models\Profile")
            ->where('tag_id', '<=', 11);

        $bar = $this->output->createProgressBar($query->count());
        $bar->start();

        foreach ($query->cursor() as $taggable) {
            $domaine = Domaine::where('name', 'ILIKE', $taggable->tag->name)->first();
            if ($domaine) {
                Domainable::create([
                    'domaine_id' => $domaine->id,
                    'domainable_id' => $taggable->taggable_id,
                    'domainable_type' => $taggable->taggable_type,
                    'field' => 'profile_domaines',
                ]);
            } else {
                $this->warn($taggable->tag_id . ' - ' . $taggable->tag->name . ' not found. Skipped.');
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
