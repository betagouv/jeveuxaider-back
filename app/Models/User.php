<?php

namespace App\Models;

use App\Notifications\ParticipationDeclined;
use App\Notifications\ResetPassword;
use App\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'context_role', 'contextable_type', 'contextable_id', 'utm_source', 'utm_campaign', 'utm_medium', 'has_agreed_responsable_terms_at',
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
            ->whereHas('messages', function (Builder $query) {
                $query->where('from_id', '!=', $this->id);
            })
            ->where(function ($query) {
                $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                    ->orWhere('conversations_users.read_at', null);
            })
            ->where('conversations_users.status', true)
            ->count();
    }

    public function getStatisticsAttribute()
    {

        $responsableStats = [];

        if($this->hasRole('responsable')){
            $responsableStats = [
                'missions_as_responsable_count' => Mission::where('responsable_id', $this->profile->id)
                    ->count(),
                'missions_as_responsable_with_participations_waiting_count' => Mission::where('responsable_id', $this->profile->id)
                    ->whereHas('participations', function($query){
                        $query->where(function($query){
                            $query
                                ->where('participations.state', 'En attente de validation')
                                ->where('participations.created_at', '<', Carbon::now()->subDays(10)->startOfDay());
                        })
                        ->orWhere(function($query){
                            $query
                                ->where('participations.state', 'En cours de traitement')
                                ->where('participations.created_at', '<', Carbon::now()->subMonths(2)->startOfDay());
                        });
                    })
                    ->count(),
                'missions_inactive_count' => $this->profile->missionsInactive->count()
            ];
        }

        return array_merge([
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

        $participation->update(['state' => 'Refusée']);

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        return $participation->load(['conversation', 'conversation.latestMessage']);
    }
}
