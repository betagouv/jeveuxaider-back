<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersInvitationSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Http\Requests\RegisterInvitationRequest;
use App\Models\Invitation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\User;
use App\Notifications\InvitationSent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvitationController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Invitation::class)
            ->allowedFilters(
                'role',
                AllowedFilter::scope('of_reseau'),
                AllowedFilter::scope('of_reseau_and_role_antenne'),
                AllowedFilter::scope('of_territoire'),
                AllowedFilter::scope('of_structure'),
                // AllowedFilter::custom('search', new FiltersInvitationSearch),
            )
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, string $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (! $invitation) {
            abort(404, "L'invitation n'est plus disponible");
        }
        $invitation->load('invitable');
        $invitation->append('is_registered');

        return $invitation;
    }

    public function store(InvitationRequest $request)
    {
        if ($request->input('email') == $request->user()->email) {
            if (! $request->user()->is_admin) {
                abort(422, 'Vous ne pouvez pas vous inviter vous-même');
            }
        }

        // RESPONSABLE ORGANISATION
        if (in_array($request->input('role'), ['responsable_organisation'])) {
            $user = User::where('email', 'ILIKE', $request->input('email'))->first();
            if ($user && $user->structures->count() > 0) {
                abort(422, 'Cet email est déjà rattaché à une organisation');
            }
        }

        // RESPONSABLE ANTENNE
        if (in_array($request->input('role'), ['responsable_antenne'])) {
            $properties = $request->input('properties');
            $structure = Structure::where('name', 'ILIKE', $properties['antenne_name'])->first();
            if ($structure) {
                abort(422, 'Cette structure est déjà inscrite sur la plateforme');
            }
        }

        do {
            $token = Str::random(32);
        } while (Invitation::where('token', $token)->first());

        $attributes = $request->validated();
        $attributes['token'] = $token;

        $invitation = Invitation::create($attributes);

        return $invitation;
    }

    public function resend(string $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (! $invitation) {
            abort(422, "L'invitation n'est plus disponible");
        }

        $diffTimestamp = Carbon::now()->timestamp - $invitation->last_sent_at->timestamp;
        if ($diffTimestamp < 3600) {
            abort(422, 'Vous devez attendre '.floor(60 - ($diffTimestamp / 60))." minutes pour renvoyer l'email d'invitation");
        }

        $invitation->update(['last_sent_at' => Carbon::now()]);
        $invitation->notify(new InvitationSent($invitation));

        return $invitation;
    }

    public function accept(string $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (! $invitation) {
            abort(422, "L'invitation n'est plus disponible");
        }

        $invitation->accept();

        $user = User::find(Auth::guard('api')->user()->id);
        $user->resetContextRole();
        $invitation->delete();

        return $invitation;
    }

    public function delete(string $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (! $invitation) {
            abort(422, "L'invitation n'est plus disponible");
        }

        return (string) $invitation->delete();
    }

    public function register(RegisterInvitationRequest $request, string $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (! $invitation) {
            abort(422, "L'invitation n'est plus disponible");
        }

        $user = User::create(
            [
                'name' => request('email'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'utm_source' => 'invitation',
            ]
        );

        $attributes = $request->validated();
        $attributes['user_id'] = $user->id;

        $profile = Profile::firstOrCreate(
            ['email' => request('email')],
            $attributes
        );

        return $profile;
    }
}
