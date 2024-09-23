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
        'App\Models\Territoire' => 'App\Policies\TerritoirePolicy',
        'App\Models\Document' => 'App\Policies\DocumentPolicy',
        'App\Models\MissionTemplate' => 'App\Policies\MissionTemplatePolicy',
        'App\Models\Conversation' => 'App\Policies\ConversationPolicy',
        'App\Models\Temoignage' => 'App\Policies\TemoignagePolicy',
        'App\Models\Reseau' => 'App\Policies\ReseauPolicy',
        'App\Models\Note' => 'App\Policies\NotePolicy',
        'App\Models\Media' => 'App\Policies\MediaPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addDays(365));
        Passport::refreshTokensExpireIn(now()->addDays(365));
    }
}
