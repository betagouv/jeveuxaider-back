<?php

namespace App\Http\Controllers\API;

use App\Filters\FiltersFaqSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FaqCreateRequest;
use App\Http\Requests\Api\FaqUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Faq;
use Spatie\QueryBuilder\AllowedFilter;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->has('pagination') ? $request->input('pagination') : config('query-builder.results_per_page');

        return QueryBuilder::for(Faq::class)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersFaqSearch),
            )
            ->defaultSort('-weight')
            ->paginate($paginate);
    }

    public function indexFront(Request $request)
    {
        return Faq::orderBy('weight', 'DESC')->get();
    }

    public function store(FaqCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $faq = Faq::create($request->validated());

        return $faq;
    }

    public function show(Faq $faq)
    {
        return $faq;
    }

    public function update(FaqUpdateRequest $request, Faq $faq)
    {
        $faq->update($request->validated());

        return $faq;
    }

    public function delete(Request $request, Faq $faq)
    {
        return (string) $faq->delete();
    }
}
