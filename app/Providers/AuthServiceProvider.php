<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Structure' => 'App\Policies\StructurePolicy',
        'App\Models\Mission' => 'App\Policies\MissionPolicy',
        'App\Models\Profile' => 'App\Policies\ProfilePolicy',
        'App\Models\Participation' => 'App\Policies\ParticipationPolicy',
        'App\Models\Faq' => 'App\Policies\FaqPolicy',
        'App\Models\Collectivity' => 'App\Policies\CollectivityPolicy',
        'App\Models\Document' => 'App\Policies\DocumentPolicy',
        'App\Models\Release' => 'App\Policies\ReleasePolicy',
        'App\Models\Thematique' => 'App\Policies\ThematiquePolicy',
        'App\Models\MissionTemplate' => 'App\Policies\MissionTemplatePolicy',
        'Spatie\Tags\Tag' => 'App\Policies\TagPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(7));
        Passport::refreshTokensExpireIn(now()->addDays(365));
    }
}
