<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTitleBodySearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\RuleRequest;
use App\Jobs\RuleMissionAttachTag;
use App\Models\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Notification;
use Throwable;

class RuleController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Rule::class)
            ->allowedFilters([
                AllowedFilter::exact('is_active'),
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
            ])
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Request $request, Rule $rule)
    {
        if($request->has('appends')){
            $rule->append(explode(',',$request->input('appends')));
        }

        return $rule;
    }

    public function store(RuleRequest $request)
    {
        $rule = Rule::create($request->validated());

        return $rule;
    }

    public function update(RuleRequest $request, Rule $rule)
    {
        $rule->update($request->validated());

        return $rule;
    }

    public function delete(Request $request, Rule $rule)
    {
        return (string) $rule->delete();
    }

    public function batch(Rule $rule)
    {
        $validator = Validator::make($rule->toArray(), [
            'event' => 'required',
            'conditions' => 'array|required',
            'action_key' => 'required',
            'action_value' => 'required',
        ]);

        if ($validator->fails()) {
            abort('422', "Cette règle n'a pas de conditions ou d'action");
        }

        $currentUserId = Auth::guard('api')->user()->id;
        $user = User::find($currentUserId);

        $itemsCount = $rule->pending_items_count;

        $batch = Bus::batch(
            $rule->pendingItems()->map(function($item) use ($rule) {
                return new RuleMissionAttachTag($rule, $item);
            })
        )
        ->onQueue('rules')
        ->then(function (Batch $batch) use ($rule, $user, $itemsCount) {
            //
            // Notification::route('slack', config('services.slack.hook_url'))
            //     ->notify(new BulkOperationsParticipationsValidated($rule));
        })->catch(function (Batch $batch, Throwable $e) use ($rule, $user, $itemsCount) {
           //
        })->finally(function (Batch $batch) use ($rule, $user, $itemsCount) {
            activity('batch')
                ->causedBy($user)
                ->on($rule)
                ->withProperties(['rule_id' => $rule->id, 'rule_event' => $rule->event, 'items_count' => $itemsCount])
                ->event('executed')
                ->log('executed');
        })->allowFailures()->dispatch();

        return $batch->id;
    }

}
