<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Taggable;
use App\Models\Mission;
use Illuminate\Console\Command;

class HandleDomaineIdForMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:handle-domaine-id-for-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Handle domaine_id For Missions";

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
        $this->info("!!! NE LANCER LE SCRIPT QU'UNE SEULE FOIS !!!");
        $this->info('Table missions, colonne domaine_id : ce script va écraser la relation vers un tag et la remplacer par une liaison avec un domaine');

        if ($this->confirm('Continuer ?')) {
            $this->handleMissionDomain();
        }
    }

    private function handleMissionDomain()
    {
        $query = Mission::whereNotNull('domaine_id');
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();

        foreach ($query->cursor() as $mission) {
            $domaine = $this->getDomaine($mission->domaine_id);
            if ($domaine && ($mission->domaine_id != $domaine->id)) {
                // ray('MODEL ID: ' . $mission->id . ' || OLD ID: ' . $mission->domaine_id . ' || NEW ID: ' . $domaine->id);
                $mission->domaine_id = $domaine->id;
                $mission->saveQuietly();
            }
            $bar->advance();
        }
        $bar->finish();
    }

    private function getDomaine($tagId)
    {
        switch ($tagId) {
            case 1:
                $name = 'Mobilisation covid-19';
                break;
            case 2:
                $name = 'Éducation pour tous';
                break;
            case 3:
                $name = 'Santé pour tous';
                break;
            case 4:
                $name = 'Protection de la nature';
                break;
            case 6:
                $name = 'Solidarité et insertion';
                break;
            case 7:
                $name = 'Sport pour tous';
                break;
            case 8:
                $name = 'Prévention et protection';
                break;
            case 9:
                $name = 'Mémoire et citoyenneté';
                break;
            case 10:
                $name = 'Coopération internationale';
                break;
            case 11:
                $name = 'Art et culture pour tous';
                break;
            default:
                $name = null;
                break;
        }

        return ($name) ? Domaine::where('name', 'ILIKE', $name)->first() : null;
    }
}
