<?php

namespace App\Console\Commands;

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

        // Inscription
        $sitemap->add(Url::create('/inscription')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.2));

        // Inscription organisation
        $sitemap->add(Url::create('/inscription/organisation')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.2));

        // Register Benevole
        $sitemap->add(Url::create('/register/volontaire')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.2));

        // Missions
        $sitemap->add(Url::create('/missions')
          ->setLastModificationDate(Carbon::yesterday())
          ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
          ->setPriority(0.8));

        // Domaines action
        $domaines = Thematique::where('published', true)->get();
        foreach ($domaines as $domaine) {
            $sitemap->add(Url::create('/domaines-action/' . $domaine->slug)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7));
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

        // Organisations validées
        $organisations = Structure::where('state', 'Validée')
        ->whereNotNull('slug')
        ->get();
        foreach ($organisations as $organisation) {
            $sitemap->add(Url::create('/organisations/' . $organisation->slug)
            ->setLastModificationDate($organisation->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.4));
        }

        Storage::disk('s3')->put('public/sitemap.xml', $sitemap->render(), 'public');
    }
}
