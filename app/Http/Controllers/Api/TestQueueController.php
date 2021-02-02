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
        $user = User::where('email', 'ines.he@live.fr')->first();

        return Sendinblue::formatAttributes($user);
        return 'coucou';
        // TestJob::dispatch($text);
        // return $text;
    }
}
