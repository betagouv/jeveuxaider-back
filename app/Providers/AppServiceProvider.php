<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Observers\StructureObserver;
use App\Models\Structure;
use App\Observers\MissionObserver;
use App\Observers\MissionTemplateObserver;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Observers\ParticipationObserver;
use App\Observers\ProfileObserver;
use App\Observers\UserObserver;
use App\Models\ActivityLog;
use App\Models\Conversation;
use App\Models\Invitation;
use App\Models\Message;
use App\Models\MissionTemplate;
use App\Models\User;
use App\Observers\ActivityLogObserver;
use App\Observers\ConversationObserver;
use App\Observers\InvitationObserver;
use App\Observers\MessageObserver;
use Algolia\AlgoliaSearch\Config\SearchConfig;
use Algolia\AlgoliaSearch\SearchClient;
use App\Models\NotificationTemoignage;
use App\Models\Reseau;
use App\Models\Temoignage;
use App\Models\Territoire;
use App\Observers\NotificationTemoignageObserver;
use App\Observers\ReseauObserver;
use App\Observers\TemoignageObserver;
use App\Observers\TerritoireObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        // https://github.com/laravel/scout/issues/427
        // https://github.com/algolia/scout-extended/issues/263
        $config = SearchConfig::create(config('scout.algolia.id'), config('scout.algolia.secret'));
        $config->setConnectTimeout(5);

        $this->app->bind(SearchClient::class, function () use ($config) {
            return SearchClient::createWithConfig($config);
        });
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
        MissionTemplate::observe(MissionTemplateObserver::class);
        Participation::observe(ParticipationObserver::class);
        Profile::observe(ProfileObserver::class);
        User::observe(UserObserver::class);
        ActivityLog::observe(ActivityLogObserver::class);
        Message::observe(MessageObserver::class);
        Conversation::observe(ConversationObserver::class);
        Invitation::observe(InvitationObserver::class);
        Territoire::observe(TerritoireObserver::class);
        NotificationTemoignage::observe(NotificationTemoignageObserver::class);
        Temoignage::observe(TemoignageObserver::class);
        Reseau::observe(ReseauObserver::class);


        Validator::extend('phone', function ($attribute, $value, $parameters) {
            return preg_match('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/', $value);
        });

        Model::preventLazyLoading(true);

        if (config("mail.reroute")) {
            if(Auth::guard('api')->user()) {
                Mail::alwaysTo(Auth::guard('api')->user()->email);
            } else {
                Mail::alwaysTo("pinto.jeremy@gmail.com");
            }
        }
    }
}
