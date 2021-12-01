<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'context_role', 'contextable_type', 'contextable_id', 'utm_source',
    ];

    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'email_verified_at'
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

    public static function currentUser()
    {
        if (!Auth::guard('api')->user()) {
            return null;
        }

        // $id = Auth::guard('api')->user()->id;
        // $user = User::with(['profile.structures', 'profile.territoires', 'profile.structures.territoire', 'profile.participations', 'profile.teteDeReseau'])->where('id', $id)->first();
        // $user['profile']['roles'] = $user->profile->roles; // Hack pour éviter de le mettre append -> trop gourmand en queries
        // $user['profile']['skills'] = $user->profile->skills; // Hack pour éviter de le mettre append -> trop gourmand en queries
        // $user['profile']['domaines'] = $user->profile->domaines; // Hack pour éviter de le mettre append -> trop gourmand en queries
        // $user['social_accounts'] = $user->socialAccounts; // Hack pour éviter de le mettre append -> trop gourmand en queries
        // $user['unreadConversations'] = self::getUnreadConversations($id);
        // $user['nbParticipationsOver'] = self::getNbParticipationsOver($user->profile->id);
        // $user['nbTodayParticipationsOnPendingValidation'] =
        //     self::getNbTodayParticipationsOnPendingValidation($user->profile->id);

        $user = User::with('profile', 'profile.media')->where('id', Auth::guard('api')->user()->id)->first();
        $user->profile->append(['avatar']);

        return $user;
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
        return $this->is_admin;
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
        return $this->hasMany('App\Models\Structure');
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
            'read_at' => Carbon::now()
        ]);
    }

    public function setConversationStatus($conversation, $status)
    {
        $this->conversations()->updateExistingPivot($conversation->id, [
            'status' => $status
        ]);
    }

    public function sendMessage($conversation_id, $content)
    {
        return $this->messages()->create([
            'content' => $content,
            'conversation_id' => $conversation_id,
            'type' => 'chat'
        ]);
    }

    public function getContextRoleAttribute()
    {
        if (empty($this->attributes['context_role']) || $this->attributes['context_role'] == 'volontaire') {
            if ($this->profile) {
                $userRoles = array_filter($this->profile->roles, function ($role) {
                    return $role === true;
                });
                if (count($userRoles) > 0) {
                    $this->attributes['context_role'] = array_key_first($userRoles);
                }
            }
        }

        return $this->attributes['context_role'] ?? null;
    }

    public function getContextableTypeAttribute()
    {
        if ($this->attributes['context_role'] == 'responsable' && $this->attributes['contextable_type'] == null) {
            if ($this->profile->structures->first()) {
                return 'structure';
            } elseif ($this->profile->territoires->first()) {
                return 'territoire';
            }
        }

        return $this->attributes['contextable_type'] ?? null;
    }

    public function getContextableIdAttribute()
    {
        if ($this->attributes['context_role'] == 'responsable' && $this->attributes['contextable_type'] == null) {
            if ($this->profile->structures->first()) {
                return $this->profile->structures->first()['id'];
            } elseif ($this->profile->territoires->first()) {
                return $this->profile->territoires->first()['id'];
            }
        }

        return $this->attributes['contextable_id'] ?? null;
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

    public static function getUnreadConversations($id)
    {
        return User::find($id)->conversations()
            ->whereHas('messages')
            ->where(function ($query) {
                $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                    ->orWhere('conversations_users.read_at', null);
            })
            ->where('conversations_users.status', true)
            ->pluck('conversations.id')
            ->toArray();
    }

    public function getUnreadConversationsCount()
    {
        return $this->conversations()
            ->whereHas('messages')
            ->where(function ($query) {
                $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                    ->orWhere('conversations_users.read_at', null);
            })
            ->where('conversations_users.status', true)
            ->count();
    }

    public static function getNbParticipationsOver($pid)
    {
        return Profile::find($pid)->participations->whereIn('state', ['Validée', 'Terminée'])->count();
    }

    public static function getNbTodayParticipationsOnPendingValidation($pid)
    {
        $result = Profile::find($pid)->participations()->whereIn('state', ['En attente de validation'])
            ->whereDate('created_at', '>=', (Carbon::createMidnightDate()))
            ->count();
        return $result;
    }

    public function activities()
    {
        return $this->morphMany('App\Models\Activity', 'causer');
    }
}
