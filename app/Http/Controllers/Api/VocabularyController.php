<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersNameSearch;
use App\Filters\FiltersTermHasRelated;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermRequest;
use App\Models\Term;
use App\Models\Termable;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class VocabularyController extends Controller
{
    public function show(Vocabulary $vocabulary)
    {
        return $vocabulary;
    }
}
