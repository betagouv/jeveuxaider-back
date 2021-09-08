<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationAvis;

class NotificationAvisController extends Controller
{
    public function show(Request $request, String $token)
    {
        return NotificationAvis::whereToken($token)->first();
    }
}
