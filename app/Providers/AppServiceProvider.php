<?php

namespace App\Providers;

use Algolia\AlgoliaSearch\Config\SearchConfig;
use Algolia\AlgoliaSearch\SearchClient;
use App\Models\Conversation;
use App\Models\Invitation;
use App\Models\Message;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Note;
use App\Models\NotificationTemoignage;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Models\Temoignage;
use App\Models\Term;
use App\Models\Territoire;
use App\Models\User;
use App\Observers\ConversationObserver;
use App\Observers\InvitationObserver;
use App\Observers\MessageObserver;
use App\Observers\MissionObserver;
use App\Observers\MissionTemplateObserver;
use App\Observers\NoteObserver;
use App\Observers\NotificationTemoignageObserver;
use App\Observers\ParticipationObserver;
use App\Observers\ProfileObserver;
use App\Observers\ReseauObserver;
use App\Observers\StructureObserver;
use App\Observers\TemoignageObserver;
use App\Observers\TermObserver;
use App\Observers\TerritoireObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Exceptions\OAuthServerException;
use Predis\Connection\ConnectionException;
use Spatie\LaravelIgnition\Facades\Flare;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Throwable;

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

        Model::preventLazyLoading(!app()->isProduction());

        Structure::observe(StructureObserver::class);
        Mission::observe(MissionObserver::class);
        MissionTemplate::observe(MissionTemplateObserver::class);
        Participation::observe(ParticipationObserver::class);
        Profile::observe(ProfileObserver::class);
        User::observe(UserObserver::class);
        Message::observe(MessageObserver::class);
        Conversation::observe(ConversationObserver::class);
        Invitation::observe(InvitationObserver::class);
        Territoire::observe(TerritoireObserver::class);
        NotificationTemoignage::observe(NotificationTemoignageObserver::class);
        Temoignage::observe(TemoignageObserver::class);
        Reseau::observe(ReseauObserver::class);
        Note::observe(NoteObserver::class);
        Term::observe(TermObserver::class);

        Validator::extend('phone', function ($attribute, $value, $parameters) {
            return preg_match('/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/', $value);
        });

        Mail::extend('sendinblue', function () {
            return (new SendinblueTransportFactory())->create(
                new Dsn(
                    'sendinblue+api',
                    'default',
                    config('services.sendinblue.key')
                )
            );
        });

        Flare::filterExceptionsUsing(
            function (Throwable $throwable) {
                if ($throwable instanceof OAuthServerException) {
                    return false;
                }
                if ($throwable instanceof ConnectionException) {
                    return false;
                }

                return true;
            }
        );
    }
}
