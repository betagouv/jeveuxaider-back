<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    public function getRolesAttribute()
    {
        $roles = [];

        if ($this->is_admin) {
            $roles[] = [
                'key' => 'admin',
                'label' => 'Modérateur'
            ];
        }

        if ($this->profile->is_analyste) {
            $roles[] = [
                'key' => 'analyste',
                'label' => 'Analyste'
            ];
        }

        if ($this->profile->referent_department) {
            $roles[] = [
                'key' => 'referent',
                'label' => $this->profile->referent_department
            ];
        }

        if ($this->profile->tete_de_reseau_id) {
            $reseau = Reseau::find($this->profile->tete_de_reseau_id);
            $roles[] = [
                'key' => 'tete_de_reseau',
                'contextable_type' => 'reseau',
                'contextable_id' => $reseau->id,
                'label' => $reseau->name
            ];
        }

        if ($this->profile->referent_region) {
            $roles[] = [
                'key' => 'referent_regional',
                'label' => $this->profile->referent_region
            ];
        }

        $structures = $this->profile->structures;
        if ($structures) {
            foreach ($structures as $structure) {
                $roles[] = [
                    'key' => 'responsable',
                    'contextable_type' => 'structure',
                    'contextable_id' => $structure->id,
                    'label' => $structure->name
                ];
            }
        }

        $territoires = $this->profile->territoires;
        if ($territoires) {
            foreach ($territoires as $territoire) {
                $roles[] = [
                    'key' => 'responsable_territoire',
                    'contextable_type' => 'territoire',
                    'contextable_id' => $territoire->id,
                    'label' => $territoire->name
                ];
            }
        }

        return $roles;
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
        if (!empty($this->roles)) {
            $role = $this->roles[0];
            $this->context_role = $role['key'];
            $this->contextable_type = isset($role['contextable_type']) ? $role['contextable_type'] : null;
            $this->contextable_id = isset($role['contextable_id']) ? $role['contextable_id'] : null;
        } else {
            $this->context_role = 'volontaire';
            $this->contextable_type = null;
            $this->contextable_id = null;
        }
        $this->saveQuietly();
    }

    // public static function getUnreadConversations($id)
    // {
    //     return User::find($id)->conversations()
    //         ->whereHas('messages')
    //         ->where(function ($query) {
    //             $query->whereRaw('conversations_users.read_at < conversations.updated_at')
    //                 ->orWhere('conversations_users.read_at', null);
    //         })
    //         ->where('conversations_users.status', true)
    //         ->pluck('conversations.id')
    //         ->toArray();
    // }

    public function getUnreadConversationsCount()
    {
        return $this->conversations()
            ->whereHas('messages', function (Builder $query) {
                $query ->where('from_id', '!=', $this->id);
            })            
            ->where(function ($query) {
                $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                    ->orWhere('conversations_users.read_at', null);
            })
            ->where('conversations_users.status', true)
            ->count();
    }

    // public static function getNbParticipationsOver($pid)
    // {
    //     return Profile::find($pid)->participations->whereIn('state', ['Validée', 'Terminée'])->count();
    // }

    // public static function getNbTodayParticipationsOnPendingValidation($pid)
    // {
    //     $result = Profile::find($pid)->participations()->whereIn('state', ['En attente de validation'])
    //         ->whereDate('created_at', '>=', (Carbon::createMidnightDate()))
    //         ->count();
    //     return $result;
    // }

    public function getStatisticsAttribute(){

        return [
            'new_participations_today' => Participation::where('profile_id',$this->profile->id)
                    ->whereIn('state', ['En attente de validation'])
                    ->whereDate('created_at', '>=', (Carbon::createMidnightDate()))
                    ->count()
        ];
    }

    public function activities()
    {
        return $this->morphMany('App\Models\Activity', 'causer');
    }
}
