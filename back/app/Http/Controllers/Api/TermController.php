<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersNameSearch;
use App\Filters\FiltersTermHasRelated;
use App\Http\Requests\TermRequest;
use App\Models\Term;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;

class TermController extends Controller
{

    public function index(Request $request, Vocabulary $vocabulary)
    {
        return QueryBuilder::for(Term::where('vocabulary_id', $vocabulary->id)->withCount(['related']))
            ->allowedFilters([
                AllowedFilter::exact('is_archived'),
                AllowedFilter::custom('search', new FiltersNameSearch),
                AllowedFilter::custom('has_related', new FiltersTermHasRelated),
            ])
            ->defaultSort('-updated_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function store(TermRequest $request, Vocabulary $vocabulary)
    {
        $attributes = $request->validated();
        $attributes['vocabulary_id'] = $vocabulary->id;
        $term = Term::create($attributes);

        return $term;
    }

    public function show(Term $term)
    {
        return $term;
    }

    public function update(TermRequest $request, Vocabulary $vocabulary, Term $term)
    {
        $term = $term->update($request->validated());

        return $term;
    }
}
