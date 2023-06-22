<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersParticipationBenevoleSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRolesRequest;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Notifications\UserAnonymize;
use App\Services\Sendinblue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = User::with('profile', 'profile.avatar', 'profile.skills', 'profile.domaines', 'reseaux', 'profile.activities', 'roles')->find(Auth::guard('api')->user()->id);
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
            'structure_missions_where_i_m_responsable_count' => Mission::where('responsable_id', $user->profile->id)->count(),
            'structure_participations_count' => Participation::ofResponsable($user->profile->id)->count(),
        ];
    }

    public function participations(Request $request)
    {
        $user = User::with(['profile'])->find(Auth::guard('api')->user()->id);

        return QueryBuilder::for(Participation::where('profile_id', $user->profile->id)->with('profile', 'mission'))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationBenevoleSearch),
                'state',
            )
            ->allowedIncludes([
                'conversation.latestMessage',
                'mission.responsable.avatar',
                'mission.structure',
                'temoignage'
            ])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function unreadMessages(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        return $user->getUnreadConversationsCount();
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $messages = [
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cet email est déjà pris',
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update($request->all());

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

        return $token;
    }

    public function stopImpersonate(Token $token)
    {
        return (string) $token->revoke();
    }

    public function anonymize(Request $request)
    {

        $user = $request->user();

        // Annulation de ses participations en cours de modération
        $user->profile->participations()->with(['conversation'])->whereIn('state', ['En attente de validation', 'En cours de traitement'])
            ->each(function ($participation) use ($user) {
                $participation->conversation->messages()->create([
                    'from_id' => $user->id,
                    'type' => 'contextual',
                    'content' => 'La participation a été annulée',
                    'contextual_state' => 'Désinscription',
                    'contextual_reason' => 'user_unsubscribed',
                ]);

                activity()
                    ->causedBy($user)
                    ->performedOn($participation)
                    ->withProperties([
                            'attributes' => ['state' => 'Annulée'],
                            'old' => ['state' => $participation->state]
                        ])
                    ->event('updated')
                    ->log('updated');

                $participation->state = 'Annulée';
                $participation->saveQuietly();
            });

        $notification = new UserAnonymize($user);
        $user->notify($notification);
        if (config('services.sendinblue.sync')) {
            Sendinblue::deleteContact($user);
        }
        $user->anonymize();

        return $user;
    }

    public function hasParticipation(Mission $mission, Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);
        $participation = Participation::where('profile_id', $user->profile->id)
            ->where('mission_id', $mission->id)
            ->where('state', '!=', 'Annulée')
            ->with(['conversation'])
            ->first();

        return $participation;
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

    public function ban (Request $request, User $user)
    {
        $reason = $request->input('reason');
        $user = $user->ban($reason);
        return $user;
    }

    public function unban (Request $request, User $user)
    {
        $user = $user->unban();
        return $user;
    }
}
