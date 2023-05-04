<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTitleBodySearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\RuleRequest;
use App\Models\Rule;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;

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

    public function bulkExecute(Rule $rule)
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

        $rule->bulkExecute();

        return true;
    }

}
