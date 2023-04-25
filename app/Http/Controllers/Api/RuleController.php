<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTitleBodySearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\RuleRequest;
use App\Models\Rule;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function show(Rule $rule)
    {
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

}
