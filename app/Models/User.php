<?php

namespace App\Models;

use App\Jobs\ArchiveAndClearUserDatas;
use App\Jobs\CloseOrTransferResponsableMissions;
use App\Jobs\ParticipationDeclineWhenUserIsBanned;
use App\Jobs\SendinblueDeleteUser;
use App\Jobs\UnarchiveAndRestoreUserDatas;
use App\Jobs\UserCancelWaitingParticipations;
use App\Notifications\ParticipationDeclined;
use App\Notifications\ResetPassword;
use App\Notifications\UserBannedInappropriateBehavior;
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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasRoles;
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'utm_source', 'utm_campaign', 'utm_medium', 'has_agreed_responsable_terms_at', 'has_agreed_benevole_terms_at'
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

    public function waitingListMissions()
    {
        return $this->belongsToMany('App\Models\Mission', 'missions_users_waiting_list')
            ->withTimestamps()
            ->whereNull('missions_users_waiting_list.deleted_at');
    }

    public function favoriteMissions()
    {
        return $this->belongsToMany('App\Models\Mission', 'missions_users_favorites', 'user_id', 'mission_id');
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

    public function archivedDatas()
    {
        return $this->hasOne('App\Models\UserArchivedDatas');
    }

    public function conversations()
    {
        return $this->belongsToMany('App\Models\Conversation', 'conversations_users');
    }

    public function startConversation($user, $conversable)
    {
        $conversation = new Conversation();
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
            ->whereHas('conversable')
            ->whereHas('users', function (Builder $query) {
                $query
                    ->where(function ($query) {
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
            ->whereHas('conversable')
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

        if($this->hasRole('responsable')) {
            $responsableStats = [
                'missions_as_responsable_count' => Mission::ofResponsable($this->profile->id)->count(),
                'participations_need_to_be_treated_count' => Participation::ofResponsable($this->profile->id)->needToBeTreated()
                    ->count(),
                'missions_offline_count' => $this->profile->missionsValidatedAndOffline->count()
            ];
        }

        return array_merge([
            'participations_count' => Participation::where('profile_id', $this->profile->id)
                ->count(),
            'participations_waiting_count' => Participation::where('profile_id', $this->profile->id)
                ->whereIn('state', ['En attente de validation', 'En cours de traitement'])
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
        return $query->where("users.last_online_at", ">=", Carbon::now()->subMinutes(10));
    }

    public function scopeInactive($query)
    {
        return $query->where("users.last_online_at", "<=", Carbon::now()->subMonth(1));
    }

    public function scopeIsActive($query)
    {
        return $query
            ->where("users.last_interaction_at", ">=", Carbon::now()->subYears(3))
            ->doesntHave('archivedDatas')
            ->whereNull('users.archived_at')
            ->whereNull('users.anonymous_at')
            ->whereNull('users.banned_at')
        ;
    }

    public function scopeShouldBeArchived($query)
    {
        return $query
            ->where("users.last_interaction_at", "<=", Carbon::now()->subYears(3)->startOfDay())
            ->doesntHave('archivedDatas')
            ->whereNull('users.archived_at')
            ->whereNull('users.anonymous_at')
            ->whereNull('users.banned_at')
        ;
    }

    // public function canBeArchived()
    // {
    //     return ($this->last_interaction_at < Carbon::now()->subYears(3)) && !$this->archivedDatas && !$this->archived_at && !$this->anonymous_at && !$this->banned_at;
    // }

    public function scopeCanReceiveNotifications($query)
    {
        // Don't add users.hard_bounced_at, as there is no Brevo hook when a contact has been "de-hardbounced"
        // Also, Brevo handles it already by blocklisting hardbounced contacts
        return $query->whereNull('users.archived_at')
            ->whereNull('users.anonymous_at')
            ->whereNull('users.banned_at');
    }

    public function isBlocked()
    {
        // Users that should not be able to connect or use the platform
        return $this->archived_at || $this->anonymous_at || $this->banned_at;
    }

    public function canBeNotified()
    {
        // Don't add hard_bounced_at, as there is no Brevo hook when a contact has been "de-hardbounced"
        // Also, Brevo handles it already by blocklisting hardbounced contacts
        return !$this->archived_at && !$this->anonymous_at && !$this->banned_at;
    }

    public function ban($reason)
    {
        switch ($reason) {
            case 'not_regular_resident':
            case 'younger_than_16':
            case 'inappropriate_behavior':
                $participationIds = $this->profile->participations()
                    ->whereNotIn('state', ['Refusée', 'Annulée'])
                    ->get()
                    ->pluck('id');
                Bus::batch($participationIds->map(fn ($id) => new ParticipationDeclineWhenUserIsBanned($id, $reason)))
                    ->allowFailures()
                    ->dispatch();
                if ($reason === 'not_regular_resident') {
                    $this->notify(new UserBannedNotRegularResident());
                } elseif ($reason === 'younger_than_16') {
                    $this->notify(new UserBannedYoungerThan16());
                } elseif ($reason === 'inappropriate_behavior') {
                    $this->notify(new UserBannedInappropriateBehavior());
                }
                break;

            default:
                break;
        }

        if (config('services.sendinblue.sync')) {
            Sendinblue::deleteContact($this->email);
        }

        $this->banned_at = Carbon::now();
        $this->banned_reason = $reason;
        $this->saveQuietly();
        return $this;
    }

    public function unban()
    {
        $this->banned_at = null;
        $this->banned_reason = null;
        $this->saveQuietly();
        return $this;
    }

    public function canSwitchToRole($role)
    {
        if (!$this->roles()->pluck('name')->contains($role['context_role'])) {
            return false;
        }

        if ($role['context_role'] === 'admin' && empty($role['contextable_type']) && empty($role['contextable_id'])) {
            return true;
        }

        switch ($role['context_role']) {
            case 'responsable':
                $roleId = 2;
                $rolableType = 'App\Models\Structure';
                break;
            case 'responsable_territoire':
                $roleId = 6;
                $rolableType = 'App\Models\Territoire';
                break;
            case 'referent':
                $roleId = 3;
                $rolableType = 'App\Models\Department';
                break;
            case 'referent_regional':
                $roleId = 4;
                $rolableType = 'App\Models\Region';
                break;
            case 'tete_de_reseau':
                $roleId = 5;
                $rolableType = 'App\Models\Reseau';
                break;
            default:
                $roleId = null;
                $rolableType = null;
                break;
        }

        if (!$roleId || !$rolableType) {
            return false;
        }

        $role = $this->roles()
            ->where('role_id', $roleId)
            ->where('rolable_type', $rolableType)
            ->where('rolable_id', $role['contextable_id'])
            ->first();

        return (bool) $role;
    }

    // public function archive()
    // {
    //     if (!$this->canBeArchived()) {
    //         return;
    //     }

    //     UserCancelWaitingParticipations::dispatch($this, 'user_archived');
    //     SendinblueDeleteUser::dispatch($this->email, "user_archived");
    //     CloseOrTransferResponsableMissions::dispatchIf($this->hasRole('responsable'), $this);
    //     ArchiveAndClearUserDatas::dispatchSync($this);
    // }

    // public function unarchive()
    // {
    //     UnarchiveAndRestoreUserDatas::dispatchSync($this);
    // }

    public function validateForPassportPasswordGrant(string $password): bool
    {
        if($this->isBlocked()) {
            return false;
        }

        return Hash::check($password, $this->password);
    }
}
