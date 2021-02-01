<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\TestJob;
use App\Models\User;
use App\Services\Sendinblue;
use Propaganistas\LaravelPhone\PhoneNumber;

class TestQueueController extends Controller
{
    public function test($text)
    {
        // $phone = PhoneNumber::make('0671902872', 'FR')->isOfCountry('FR');
        $user = User::where('email', 'Patrick.bonfils@jscs.gouv.fr')->first();
        ray($user);
        Sendinblue::sync($user);
        return 'coucou';
        // TestJob::dispatch($text);
        // return $text;
    }
}
