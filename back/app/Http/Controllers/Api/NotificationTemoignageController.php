<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationTemoignage;

class NotificationTemoignageController extends Controller
{
    public function show(Request $request, String $token)
    {
        return NotificationTemoignage::whereToken($token)->first();
    }
}
