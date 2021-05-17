<?php

namespace App\Console\Commands;

use App\Models\Collectivity;
use App\Models\Mission;
use App\Models\Structure;
use App\Models\Thematique;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = Sitemap::create(config('app.url'));

        // Homepage
        $sitemap->add(Url::create('/')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.5));

        // Login
        $sitemap->add(Url::create('/login')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.2));

        // Register Benevole
        $sitemap->add(Url::create('/register/benevole')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.2));

        // Register Responsable
        $sitemap->add(Url::create('/register/responsable')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.2));

        // Missions
        $sitemap->add(Url::create('/missions')
          ->setLastModificationDate(Carbon::yesterday())
          ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
          ->setPriority(0.8));

        // Départements
        $departements = Collectivity::where('type', 'department')->get();
        foreach ($departements as $departement) {
            $sitemap->add(Url::create('/territoires/' . $departement->slug)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.5));
        }

        // Domaines action
        $domaines = Thematique::where('published', true)->get();
        foreach ($domaines as $domaine) {
            $sitemap->add(Url::create('/domaines-action/' . $domaine->slug)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7));
        }

        // Communes
        $communes = Collectivity::where('type', 'commune')->get();
        foreach ($communes as $commune) {
            $sitemap->add(Url::create('/territoires/' . $commune->slug)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.5));
        }

        // Missions validées non complètes (orga validées)
        $missions = Mission::available()
        ->hasPlacesLeft()
        ->whereHas('structure', function (Builder $query) {
            $query->where('state', 'Validée');
        })
        ->get();
        foreach ($missions as $mission) {
            $sitemap->add(Url::create('/missions/' . $mission->id)
            ->setLastModificationDate($mission->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.4));
        }

        // Missions validées non complètes (orga validées)
        $organisations = Structure::where('state', 'Validée')
        ->whereNotNull('slug')
        ->get();
        ray(count($organisations));
        foreach ($organisations as $organisation) {
            $sitemap->add(Url::create('/organisations/' . $organisation->slug)
            ->setLastModificationDate($organisation->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.4));
        }

        Storage::disk('s3')->put('public/sitemap.xml', $sitemap->render(), 'public');
    }
}
