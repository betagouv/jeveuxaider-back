<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersReferentDepartment;
use App\Filters\FiltersReferentRegion;
use App\Filters\FiltersTags;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Jobs\AirtableSyncObject;
use App\Jobs\MissionSetSearchable;
use App\Models\Activity;
use App\Models\Mission;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\ResponsableMissionsDeactivated;
use App\Notifications\ResponsableMissionsReactivated;
use App\Sorts\ProfileParticipationsValidatedCountSort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Bus\Batch;
use Laravel\Passport\Passport;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Profile::role($request->header('Context-Role')))
            ->allowedIncludes([
                'user',
                'participationsValidatedCount',
                'avatar',
                'tags',
            ])
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch()),
                AllowedFilter::scope('user.role'),
                AllowedFilter::custom('referent_department', new FiltersReferentDepartment()),
                AllowedFilter::custom('referent_region', new FiltersReferentRegion()),
                AllowedFilter::scope('department'),
                AllowedFilter::exact('zip'),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations()),
                AllowedFilter::custom('tags', new FiltersTags())
            )
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                AllowedSort::custom('participations_validated_count', new ProfileParticipationsValidatedCountSort()),
            ])
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(ProfileRequest $request, Profile $profile)
    {
        $profile->load(['user', 'user.roles', 'user.territoires', 'user.structures', 'user.regionsAsReferent', 'user.departmentsAsReferent', 'user.reseaux', 'skills', 'domaines', 'activities', 'avatar', 'activities', 'tags'])->loadCount(['participations', 'participationsValidated']);

        foreach ($profile->user->roles as $key => $role) {
            if (isset($role['pivot']['rolable_type'])) {
                $profile->user->roles[$key]['pivot_model'] = $role['pivot']['rolable_type']::find($role['pivot']['rolable_id']);
            }
        }

        return $profile;
    }

    public function update(ProfileUpdateRequest $request, Profile $profile = null)
    {
        if ($request->has('domaines')) {
            $domaines = collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'profile_domaines'];
            });
            $profile->domaines()->sync($values);
        }

        if ($request->has('skills')) {
            $skills = collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'profile_skills'];
            });
            $profile->skills()->sync($values);
        }

        if ($request->has('activities')) {
            $activities = collect($request->input('activities'));
            $values = $activities->pluck($activities, 'id')->toArray();
            $profile->activities()->sync(array_keys($values));
        }

        if ($request->has('tags')) {
            $tags = collect($request->input('tags'));
            $values = $tags->pluck($tags, 'id')->map(function ($item) {
                return ['field' => 'tags'];
            });
            $profile->tags()->sync($values);
            // Sync Airtable
            if (config('services.airtable.sync')) {
                if ($profile->user->hasRole(['referent', 'referent_regional'])) {
                    AirtableSyncObject::dispatch($profile->user);
                }
            }
        }

        $profile->update($request->validated());

        return $profile;
    }

    public function firstname(Request $request)
    {
        $profile = Profile::where('email', 'ILIKE', request('email'))->first();

        if (!$profile) {
            return null;
        }

        return collect($profile)->only('first_name', 'email');
    }

    public function attachActivity(Profile $profile, Activity $activity)
    {
        if(!Auth::guard('api')->user()->can('update', $profile)) {
            abort(403, 'This action is not authorized');
        }

        $profile->activities()->syncWithoutDetaching($activity);

        return $profile;
    }

    public function detachActivity(Profile $profile, Activity $activity)
    {
        if(!Auth::guard('api')->user()->can('update', $profile)) {
            abort(403, 'This action is not authorized');
        }

        $profile->activities()->detach($activity);

        return $profile;
    }

    public function setMissionsIsActiveForResponsable(Request $request, Profile $profile)
    {
        $currentUserId = Auth::guard('api')->user()->id;
        $currentUser = User::find($currentUserId);
        if ($request->input('is_online')) {
            $missionIds = Profile::find($profile->id)->missionsValidatedAndOffline()->get()->pluck('id');
            Mission::whereIn('id', $missionIds)->update(['is_online' => true]);

            $batch = Bus::batch(
                $missionIds->map(fn ($id) => new MissionSetSearchable($id))
            )
                ->then(function (Batch $batch) use ($currentUser, $profile, $missionIds) {
                    Passport::actingAs($currentUser);
                    $profile->notify(new ResponsableMissionsReactivated());
                    activity()
                        ->causedBy($currentUser)
                        ->on($profile)
                        ->withProperties(['items_count' => $missionIds->count()])
                        ->event('responsable_reactivate_missions')
                        ->log('responsable_reactivate_missions');
                })
                ->allowFailures()
                ->dispatch();

            return $batch->id;
        } else {
            $missionIds = Mission::ofResponsable($profile->id)->where('is_online', true)->where('state', 'Validée')->get()->pluck('id');
            Mission::whereIn('id', $missionIds)->update(['is_online' => false]);
            Mission::whereIn('id', $missionIds)->unsearchable();
            $profile->notify(new ResponsableMissionsDeactivated());
            activity()
                ->causedBy($currentUser)
                ->on($profile)
                ->withProperties(['items_count' => $missionIds->count()])
                ->event('responsable_deactivate_missions')
                ->log('responsable_deactivate_missions');
        }
    }
}
