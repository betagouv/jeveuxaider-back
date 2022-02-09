<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermRequest;
use App\Models\Term;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class TermController extends Controller
{

    public function show(Vocabulary $vocabulary)
    {
        return $vocabulary;
    }

    public function index(Request $request, Vocabulary $vocabulary)
    {
        return $vocabulary->terms()->withCount(['related'])->orderBy('weight')->orderBy('name')->get();
    }

    public function store(TermRequest $request, Vocabulary $vocabulary)
    {
        $attributes = $request->validated();
        $attributes['vocabulary_id'] = $vocabulary->id;
        $term = Term::create($attributes);

        return $term;
    }


    public function update(TermRequest $request, Term $term)
    {
        $term = $term->update($request->validated());

        return $term;
    }
}
