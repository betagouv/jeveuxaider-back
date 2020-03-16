<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Release;

class ReleaseController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Release::class)
            ->defaultSort('-date')
            ->get();
    }
}
