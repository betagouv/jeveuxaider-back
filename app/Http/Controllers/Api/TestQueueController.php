<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\TestJob;
use App\Models\User;
use App\Services\Sendinblue;

class TestQueueController extends Controller
{
    public function test($text)
    {
        $user = User::where('email', 'chrisfav1108@hotmail.fr')->first();
        ray($user);
        Sendinblue::sync($user);
        return 'coucou';
        // TestJob::dispatch($text);
        // return $text;
    }
}
