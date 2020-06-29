<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Observers\StructureObserver;
use App\Models\Structure;
use App\Observers\MissionObserver;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Observers\ParticipationObserver;
use App\Observers\ProfileObserver;
use Spatie\QueryBuilder\QueryBuilderRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Structure::observe(StructureObserver::class);
        Mission::observe(MissionObserver::class);
        Participation::observe(ParticipationObserver::class);
        Profile::observe(ProfileObserver::class);

        Validator::extend('phone', function ($attribute, $value, $parameters) {
            return preg_match('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/', $value);
        });
    }
}
