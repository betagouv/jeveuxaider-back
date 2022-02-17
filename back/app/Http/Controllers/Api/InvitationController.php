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
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;

class InvitationController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(Invitation::class)
            ->allowedFilters(
                AllowedFilter::scope('of_reseau'),
                AllowedFilter::scope('of_territoire'),
                AllowedFilter::scope('of_structure'),
                AllowedFilter::custom('search', new FiltersInvitationSearch),
            )
            ->defaultSort('-created_at')
            ->paginate(10);
    }

    // public function show(Request $request, String $token)
    // {
    //     $invitation = Invitation::whereToken($token)->first();

    //     if (!$invitation) {
    //         abort(404, "L'invitation n'est plus disponible");
    //     }

    //     return $invitation;
    // }

    public function store(InvitationRequest $request)
    {
        // RESPONSABLE ORGANISATION
        if (in_array($request->input('role'), ['responsable_organisation'])) {
            $profile = Profile::where('email', 'ILIKE', $request->input('email'))->first();
            if ($profile && $profile->structures->count() > 0) {
                abort(422, "Cet email est déjà rattaché à une organisation");
            }
        }

        // RESPONSABLE ANTENNE
        if (in_array($request->input('role'), ['responsable_antenne'])) {
            $properties = $request->input('properties');
            $structure = Structure::where('name', 'ILIKE', $properties['antenne_name'])->first();
            if ($structure) {
                abort(422, "Cette structure est déjà inscrite sur la plateforme");
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

    public function resend(String $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (!$invitation) {
            abort(422, "L'invitation n'est plus disponible");
        }

        $diffTimestamp = Carbon::now()->timestamp - $invitation->last_sent_at->timestamp;
        if ($diffTimestamp < 3600) {
            abort(422, "Vous devez attendre " . floor(60 - ($diffTimestamp / 60)) . " minutes pour renvoyer l'email d'invitation");
        }

        $invitation->update(['last_sent_at' => Carbon::now()]);
        $invitation->notify(new InvitationSent($invitation));

        return $invitation;
    }

    // public function accept(String $token)
    // {
    //     $invitation = Invitation::whereToken($token)->first();

    //     if (!$invitation) {
    //         abort(422, "L'invitation n'est plus disponible");
    //     }

    //     $invitation->accept();
    //     $invitation->delete();

    //     return $invitation;
    // }

    public function delete(String $token)
    {
        $invitation = Invitation::whereToken($token)->first();

        if (!$invitation) {
            abort(422, "L'invitation n'est plus disponible");
        }

        return (string) $invitation->delete();
    }

    // public function register(RegisterInvitationRequest $request, String $token)
    // {
    //     $invitation = Invitation::whereToken($token)->first();

    //     if (!$invitation) {
    //         abort(422, "L'invitation n'est plus disponible");
    //     }

    //     $user = User::create(
    //         [
    //             'name' => request("email"),
    //             'email' => request("email"),
    //             'password' => Hash::make(request("password")),
    //             'utm_source' => 'invitation'
    //         ]
    //     );

    //     $attributes = $request->validated();
    //     $attributes['user_id'] = $user->id;

    //     $profile = Profile::firstOrCreate(
    //         ['email' => request('email')],
    //         $attributes
    //     );

    //     $invitation->accept();
    //     $invitation->delete();

    //     return User::with(['profile.territoires', 'profile.structures', 'profile.participations'])->where('id', $user->id)->first();
    // }
}
