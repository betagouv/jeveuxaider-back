<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class DebugController extends Controller
{
    public function debug()
    {
        return response()->json(get_loaded_extensions());
    }
}
