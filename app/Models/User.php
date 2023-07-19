<?php

namespace App\Models;

use App\Jobs\ParticipationDeclineWhenUserIsBanned;
use App\Notifications\ParticipationDeclined;
use App\Notifications\ResetPassword;
use App\Notifications\UserBannedNotRegularResident;
use App\Notifications\UserBannedYoungerThan16;
use App\Services\Sendinblue;
use App\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Bus;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'context_role', 'contextable_type', 'contextable_id', 'utm_source', 'utm_campaign', 'utm_medium', 'has_agreed_responsable_terms_at', 'has_agreed_benevole_terms_at'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['profile'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtolower($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = mb_strtolower($value);
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function socialAccounts()
    {
        return $this->hasMany('App\Models\SocialAccount');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission');
    }

    public function structures()
    {
        return $this->morphedByMany(Structure::class, 'rolable', 'rolables');
    }

    public function departmentsAsReferent()
    {
        return $this->morphedByMany(Department::class, 'rolable', 'rolables');
    }

    public function regionsAsReferent()
    {
        return $this->morphedByMany(Region::class, 'rolable', 'rolables');
    }

    public function reseaux()
    {
        return $this->morphedByMany(Reseau::class, 'rolable', 'rolables');
    }

    public function territoires()
    {
        return $this->morphedByMany(Territoire::class, 'rolable', 'rolables');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'from_id');
    }

    public function conversations()
    {
        return $this->belongsToMany('App\Models\Conversation', 'conversations_users');
    }

    public function startConversation($user, $conversable)
    {
        $conversation = new Conversation;
        $conversation->conversable()->associate($conversable);
        $conversation->save();

        $conversation->users()->attach([$this->id, $user->id]);

        return $conversation;
    }

    public function markConversationAsRead($conversation)
    {
        $this->conversations()->updateExistingPivot($conversation->id, [
            'read_at' => Carbon::now(),
        ]);
    }

    public function setConversationStatus($conversation, $status)
    {
        $this->conversations()->updateExistingPivot($conversation->id, [
            'status' => $status,
        ]);
    }

    public function sendMessage($conversation_id, $content)
    {
        return $this->messages()->create([
            'content' => $content,
            'conversation_id' => $conversation_id,
            'type' => 'chat',
        ]);
    }

    public function anonymize()
    {
        $email = $this->id . '@anonymized.fr';
        $this->anonymous_at = Carbon::now();
        $this->name = $email;
        $this->email = $email;
        $this->profile->email = $email;
        $this->profile->first_name = 'Anonyme';
        $this->profile->last_name = 'Anonyme';
        $this->profile->phone = null;
        $this->profile->mobile = null;
        $this->profile->birthday = null;
        $this->save();
        $this->profile->save();

        return $this;
    }

    public function resetContextRole()
    {
        if ($this->roles()->count() > 0) {
            $role = $this->roles()->first();
            $this->context_role = $role['name'];
            if (isset($role['pivot'])) {
                $this->contextable_id = $role['pivot']['rolable_id'];
                switch ($role['pivot']['rolable_type']) {
                    case 'App\Models\Structure':
                        $this->contextable_type = 'structure';
                        break;
                    case 'App\Models\Territoire':
                        $this->contextable_type = 'responsable_territoire';
                        break;
                    case 'App\Models\Department':
                        $this->contextable_type = 'referent';
                        break;
                    case 'App\Models\Region':
                        $this->contextable_type = 'referent_regional';
                        break;
                    case 'App\Models\Reseau':
                        $this->contextable_type = 'tete_de_reseau';
                        break;
                    default:
                        $this->contextable_type = null;
                        break;
                }
            }
        } else {
            $this->context_role = 'volontaire';
            $this->contextable_type = null;
            $this->contextable_id = null;
        }
        $this->saveQuietly();
    }

    public function getUnreadConversationsCount()
    {
        return $this->conversations()
            ->whereHas('users', function (Builder $query) {
                $query
                    ->where(function($query){
                        $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                            ->orWhere('conversations_users.read_at', null);
                    })
                    ->where('conversations_users.user_id', $this->id)
                    ->where('conversations_users.status', true);
            })
            ->count();
    }

    public function getUnreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    public function lastReadConversation()
    {
        return $this->conversations()
            ->whereHas('users', function (Builder $query) {
                $query
                    ->whereNotNull('conversations_users.read_at')
                    ->where('conversations_users.user_id', $this->id);
            })
            ->orderByDesc('conversations_users.read_at')
            ->first();
    }

    public function getStatisticsAttribute()
    {

        $responsableStats = [];

        if($this->hasRole('responsable')){
            $responsableStats = [
                'missions_as_responsable_count' => Mission::where('responsable_id', $this->profile->id)
                    ->count(),
                'participations_need_to_be_treated_count' => Participation::ofResponsable($this->profile->id)->needToBeTreated()
                    ->count(),
                'missions_inactive_count' => $this->profile->missionsInactive->count()
            ];
        }

        return array_merge([
            'participations_count' => Participation::where('profile_id', $this->profile->id)
                ->count(),
            'new_participations_today' => Participation::where('profile_id', $this->profile->id)
                ->whereIn('state', ['En attente de validation'])
                ->whereDate('created_at', '>=', (Carbon::createMidnightDate()))
                ->count(),

        ], $responsableStats);
    }

    public function activitiesLogs()
    {
        return $this->morphMany('App\Models\ActivityLog', 'causer');
    }

    public function declineParticipation(Participation $participation, $reason, $message = null)
    {
        if ($participation->conversation) {
            $participation->conversation->messages()->create([
                'from_id' => $this->id,
                'type' => 'contextual',
                'content' => 'La participation a été déclinée',
                'contextual_state' => 'Refusée',
                'contextual_reason' => $reason,
            ]);

            if ($message) {
                $this->sendMessage($participation->conversation->id, $message);
                // Trigger updated_at refresh.
                $participation->conversation->touch();
            }

            $this->markConversationAsRead($participation->conversation);

            $participation->profile->notify(new ParticipationDeclined($participation, $message, $reason));
        }

        $oldParticipationState = $participation->state;
        $participation->update(['state' => 'Refusée']);
        if (in_array($oldParticipationState, ['En attente de validation', 'En cours de traitement'])) {
            $participation->mission->structure->calculateScore();
        }

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        return $participation->load(['conversation', 'conversation.latestMessage']);
    }

    public function scopeOnline($query)
    {
        return $query->where("users.last_online_at", ">=" , Carbon::now()->subMinutes(10));
    }

    public function scopeInactive($query)
    {
        return  $query->where("users.last_online_at", "<=" , Carbon::now()->subMonth(1));
    }

    public function ban($reason)
    {
        switch ($reason) {
            case 'not_regular_resident':
            case 'younger_than_16':
                $participationIds = $this->profile->participations()
                    ->whereNotIn('state', ['Refusée', 'Annulée'])
                    ->get()
                    ->pluck('id');
                Bus::batch($participationIds->map(fn($id) => new ParticipationDeclineWhenUserIsBanned($id, $reason)))
                    ->allowFailures()
                    ->dispatch();
                if ($reason === 'not_regular_resident') {
                    $this->notify(new UserBannedNotRegularResident);
                }
                elseif ($reason === 'younger_than_16') {
                    $this->notify(new UserBannedYoungerThan16);
                }
                break;

            default:
                break;
        }

        if (config('services.sendinblue.sync')) {
            Sendinblue::deleteContact($this);
        }

        $this->banned_at = Carbon::now();
        $this->banned_reason = $reason;
        $this->saveQuietly();
        return $this;
    }

    public function unban ()
    {
        $this->banned_at = NULL;
        $this->banned_reason = NULL;
        $this->saveQuietly();
        return $this;
    }
}
