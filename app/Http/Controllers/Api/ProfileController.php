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
use App\Models\Activity;
use App\Models\Mission;
use App\Models\Profile;
use App\Notifications\ResponsableMissionsDeactivated;
use App\Notifications\ResponsableMissionsReactivated;
use App\Sorts\ProfileParticipationsValidatedCountSort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

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
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::scope('user.role'),
                AllowedFilter::custom('referent_department', new FiltersReferentDepartment),
                AllowedFilter::custom('referent_region', new FiltersReferentRegion),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('zip'),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations),
                AllowedFilter::custom('tags', new FiltersTags)
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
        $profile->load(['user', 'user.roles', 'user.territoires', 'user.structures', 'user.regionsAsReferent', 'user.departmentsAsReferent', 'user.reseaux', 'skills', 'domaines', 'avatar', 'activities', 'tags'])->loadCount(['participations', 'participationsValidated']);

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

        if (! $profile) {
            return null;
        }

        return collect($profile)->only('first_name', 'email');
    }

    public function attachActivity(Profile $profile, Activity $activity)
    {
        if(!Auth::guard('api')->user()->can('update', $profile)) {
            abort(403, 'This action is not authorized');
        }

        $profile->activities()->attach($activity);

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

    public function setMissionsIsActiveForResponsable (Request $request, Profile $profile)
    {
        if ($request->input('is_active')) {
            $missionIds = Mission::ofResponsable($profile->id)->where('is_active', false)->get()->pluck('id');
            Mission::whereIn('id', $missionIds)->update(['is_active' => true]);
            Mission::whereIn('id', $missionIds)->with('structure')->searchable();
            $profile->notify(new ResponsableMissionsReactivated);
        }
        else {
            $missionIds = Mission::ofResponsable($profile->id)->available()->get()->pluck('id');
            Mission::whereIn('id', $missionIds)->update(['is_active' => false]);
            Mission::whereIn('id', $missionIds)->unsearchable();
            $profile->notify(new ResponsableMissionsDeactivated);
        }
    }
}
