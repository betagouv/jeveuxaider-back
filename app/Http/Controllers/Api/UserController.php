<?php

namespace App\Http\Controllers\Api;

use App\Models\UserArchivedDatas;
use App\Filters\FiltersNotificationSearch;
use App\Filters\FiltersParticipationBenevoleSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRolesRequest;
use App\Jobs\SendinblueDeleteUser;
use App\Jobs\UnarchiveAndRestoreUserDatas;
use App\Jobs\UnsubscribeAndAnonymizeUserDatas;
use App\Jobs\UserCancelWaitingParticipations;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Notifications\SendCodeUnarchiveUserDatas;
use App\Notifications\UserAnonymize;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
use Maize\Encryptable\Encryption;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = User::with([
            'profile',
            'profile.avatar',
            'profile.skills',
            'profile.domaines',
            'reseaux',
            'profile.activities',
            'roles',
            'waitingListMissions'
        ])->find(Auth::guard('api')->user()->id);

        $user->append(['statistics']);
        $this->loadRoles($user);

        return $user;
    }

    public function status(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);
        $structure = $user->structures->first();

        return [
            'structure' => $structure,
            'structure_responsables' => $structure ? $structure->members()->get() : null,
            'structure_missions_where_i_m_responsable_count' => Mission::ofResponsable($user->profile->id)->count(),
            'structure_participations_count' => Participation::ofResponsable($user->profile->id)->count(),
        ];
    }

    public function notifications(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        $queryBuilder = DatabaseNotification::with('notifiable')
            ->where(function (Builder $query) use ($user) {
                $query->where(function (Builder $query) use ($user) {
                    $query
                        ->where('notifiable_type', 'App\Models\User')
                        ->where('notifiable_id', $user->id);
                })
                ->orWhere(function (Builder $query) use ($user) {
                    $query
                        ->where('notifiable_type', 'App\Models\Profile')
                        ->where('notifiable_id', $user->profile->id);
                });
            });

        return QueryBuilder::for($queryBuilder)
            ->allowedFilters([
                AllowedFilter::scope('unread'),
                AllowedFilter::custom('search', new FiltersNotificationSearch()),
            ])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));

    }

    public function notificationsMarkAsRead(Request $request, DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return $notification->fresh();
    }

    public function notificationsMarkAllAsRead(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        DatabaseNotification::where(function (Builder $query) use ($user) {
            $query->where(function (Builder $query) use ($user) {
                $query
                    ->where('notifiable_type', 'App\Models\User')
                    ->where('notifiable_id', $user->id);
            })
            ->orWhere(function (Builder $query) use ($user) {
                $query
                    ->where('notifiable_type', 'App\Models\Profile')
                    ->where('notifiable_id', $user->profile->id);
            });
        })->update(['read_at' => now()]);

        return true;
    }

    public function unreadNotifications(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        $count = DatabaseNotification::with('notifiable')
            ->where(function (Builder $query) use ($user) {
                $query->where(function (Builder $query) use ($user) {
                    $query
                        ->where('notifiable_type', 'App\Models\User')
                        ->where('notifiable_id', $user->id);
                })
                ->orWhere(function (Builder $query) use ($user) {
                    $query
                        ->where('notifiable_type', 'App\Models\Profile')
                        ->where('notifiable_id', $user->profile->id);
                });
            })->unread()->count();

        return [
            'count' => $count
        ];
    }

    public function participations(Request $request)
    {
        $user = User::with(['profile'])->find(Auth::guard('api')->user()->id);

        return QueryBuilder::for(Participation::where('profile_id', $user->profile->id)
            ->with([
                'profile:id,user_id,first_name,last_name',
                'conversation.latestMessage',
                'mission',
                'mission.responsables:id,first_name',
                'mission.responsables.avatar',
                'mission.structure:id,name',
                'temoignage',
            ]))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationBenevoleSearch()),
                'state',
            )
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function unreadMessages(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        return [
            'count' => $user->getUnreadConversationsCount()
        ];
    }

    public function lastReadConversation(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        return [
            'last_read_conversation' => $user->lastReadConversation()
        ];
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'has_agreed_benevole_terms_at' => ['date'],
            'has_agreed_responsable_terms_at' => ['date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update($validator->validated());

        $user->load('profile', 'profile.media', 'profile.skills', 'profile.domaines', 'roles');
        $user->append(['statistics']);
        $this->loadRoles($user);

        return $user;
    }

    public function switchRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'context_role' => [
                'required',
                'in:admin,referent,referent_regional,responsable,responsable_territoire,tete_de_reseau'
            ],
            'contextable_type' => 'required_if:context_role,referent,referent_regional,responsable,responsable_territoire,tete_de_reseau',
            'contextable_id' => 'required_if:context_role,referent,referent_regional,responsable,responsable_territoire,tete_de_reseau',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $inputs = $validator->safe()->getIterator();
        $user = $request->user();

        if (!$user->canSwitchToRole($inputs)) {
            return abort(403);
        }

        $user->context_role = $inputs['context_role'];
        $user->contextable_type = $inputs['contextable_type'];
        $user->contextable_id = $inputs['contextable_id'];
        $user->save();

        $user->load('profile', 'profile.media', 'profile.skills', 'profile.domaines', 'roles');
        $user->append(['statistics']);
        $this->loadRoles($user);

        return $user;
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $inputs = $request->all();

        if (!(Hash::check($request->get('current_password'), $user->password))) {
            abort(422, "L'ancien mot de passe est incorrect");
        }

        if (strcmp($request->get('current_password'), $request->get('password')) == 0) {
            abort(422, 'Le nouveau mot de passe doit être différent de l\'ancien');
        }

        $messages = [
            'current_password.required' => 'Le mode de passe actuel est requis',
            'password.required' => 'Le mot de passe est requis',
            'password.min' => 'Votre nouveau mot de passe doit contenir au moins :min caractères',
            'password.confirmed' => 'Les nouveaux mots de passe ne sont pas identiques',
        ];

        $validator = Validator::make($inputs, [
            'current_password' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ], $messages);

        if (!$validator->fails()) {
            $user->password = Hash::make($inputs['password']);
            $user->save();

            return response()->json($user, 200);
        }

        return response()->json(['errors' => $validator->errors()], 422);
    }

    public function impersonate(User $user)
    {
        $token = $user->createToken('impersonate');
        $token->token->expires_at = now()->addMinutes(60);
        $token->token->save();

        activity('impersonate')
            ->event('start_impersonated')
            ->withProperties([
                'impersonate_user_email' => $user->email,
                'impersonate_user_name' => $user->profile->full_name,
            ])
            ->log("impersonated");

        return $token;
    }

    public function stopImpersonate(Token $token)
    {
        activity('impersonate')
            ->event('stop_impersonated')
            ->withProperties([
                'impersonate_user_email' => $token->user->email,
                'impersonate_user_name' =>  $token->user->profile->full_name,
            ])
            ->log("impersonated");

        return (string) $token->revoke();
    }

    public function anonymize(Request $request)
    {
        $user = $request->user();

        $notification = new UserAnonymize($user);
        $user->notify($notification);

        UserCancelWaitingParticipations::dispatch($user, 'user_unsubscribed');
        SendinblueDeleteUser::dispatch($user->email);
        UnsubscribeAndAnonymizeUserDatas::dispatchSync($user);

        return $user->fresh();
    }

    public function hasParticipation(Mission $mission, Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);
        $participation = Participation::where('profile_id', $user->profile->id)
            ->where('mission_id', $mission->id)
            ->where('state', '!=', 'Annulée')
            ->with(['conversation'])
            ->first();

        return ['participation' => $participation];
    }

    public function addRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:referent,referent_regional',
            'department' => 'nullable|required_if:role,referent',
            'region' => 'nullable|required_if:role,referent_regional',
        ]);

        if ($user->hasRole($request->input('role'))) {
            return new Response(['message' => "L'utilisateur a déjà ce rôle."], 401);
        }

        if ($request->input('role') == 'referent') {
            $user->assignRole('referent', Department::whereNumber($request->input('department'))->get()->first());
        } elseif ($request->input('role') == 'referent_regional') {
            $user->assignRole('referent_regional', Region::whereName($request->input('region'))->get()->first());
        }

        return $user;
    }

    public function deleteRole(Request $request, User $user, Role $role)
    {
        $user->removeRole($role->name);

        return $user;
    }

    private function loadRoles($user)
    {
        foreach ($user->roles as $key => $role) {
            if (isset($role['pivot']['rolable_type'])) {
                $user->roles[$key]['pivot_model'] = $role['pivot']['rolable_type']::find($role['pivot']['rolable_id']);
            }
        }

        return $user;
    }

    public function actions(Request $request, User $user)
    {
        return [
            'activity_logs_count' => ActivityLog::where('causer_id', $user->id)->count(),
            'activity_logs_last_days_count' => ActivityLog::where('causer_id', $user->id)->where('created_at', '>', Carbon::now()->subDays(30))->count(),
            'last_activity_log' => ActivityLog::where('causer_id', $user->id)->latest()->first()
        ];
    }

    public function roles(UserRolesRequest $request, User $user)
    {
        $roles = $user->roles;
        foreach ($roles as $key => $role) {
            if (isset($role['pivot']['rolable_type'])) {
                $roles[$key]['pivot_model'] = $role['pivot']['rolable_type']::find($role['pivot']['rolable_id']);
                $roles[$key]['invited_by'] = isset($role['pivot']['invited_by_user_id']) ? User::find($role['pivot']['invited_by_user_id']) : null;
            }
        }

        return $roles;
    }

    public function visible(Request $request)
    {
        $userId = Auth::guard('api')->user()->id;
        $user = User::find($userId);
        $user->profile->update([
            'is_visible' => true
        ]);

        return $user;
    }

    public function invisible(Request $request)
    {
        $userId = Auth::guard('api')->user()->id;
        $user = User::find($userId);
        $user->profile->update([
            'is_visible' => false
        ]);

        return $user;
    }

    public function ban(Request $request, User $user)
    {
        $reason = $request->input('reason');
        $user = $user->ban($reason);
        return $user;
    }

    public function unban(Request $request, User $user)
    {
        $user = $user->unban();
        return $user;
    }

    // public function archive(Request $request, User $user)
    // {
    //     if ($user->archivedDatas) {
    //         abort(401, "Les données de l'utilisateur ne peuvent pas être archivées");
    //     }

    //     $user->archive();

    //     return $user->fresh();
    // }

    // public function unarchive(Request $request, User $user)
    // {
    //     if (!$user->archivedDatas) {
    //         abort(401, "Les données de l'utilisateur ne sont pas archivées");
    //     }

    //     $user->unarchive();

    //     return $user->fresh();
    // }

    public function checkUserArchiveExist(Request $request)
    {
        if($request->input('email')) {
            $encryptedEmail = Encryption::php()->encrypt($request->input('email'));
            return response()->json(['exist' => UserArchivedDatas::where('email', $encryptedEmail)->exists()], 200);
        }

        return response()->json(['exist' => false], 200);
    }

    public function sendUserArchiveCode(Request $request)
    {
        if($request->input('email')) {
            $encryptedEmail = Encryption::php()->encrypt($request->input('email'));
            $userArchiveDatas = UserArchivedDatas::where('email', $encryptedEmail)->first();
            if($userArchiveDatas) {
                $userArchiveDatas->generateNewCode();
                Notification::route('mail', [$request->input('email')])
                    ->route('slack', config('services.slack.hook_url'))
                    ->notify(new SendCodeUnarchiveUserDatas($userArchiveDatas->fresh()));
                return response()->json(['sent' => true], 200);
            }
        }

        return response()->json(['message' => "Le mail est incorrect"], 422);
    }

    public function validateUserArchiveCode(Request $request)
    {
        if($request->input('code') && $request->input('email')) {
            $encryptedEmail = Encryption::php()->encrypt($request->input('email'));
            $encryptedCode = Encryption::php()->encrypt(intval($request->input('code')));
            $userArchiveDatas = UserArchivedDatas::where('email', $encryptedEmail)->where('code', $encryptedCode)->first();
            if($userArchiveDatas) {
                UnarchiveAndRestoreUserDatas::dispatchSync($userArchiveDatas->user);
                return response()->json(['unarchive' => true], 200);
            }
        }

        return response()->json(['message' => "Le code est incorrect"], 422);
    }
}
