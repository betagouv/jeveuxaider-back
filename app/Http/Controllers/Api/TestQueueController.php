<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\TestJob;

class TestQueueController extends Controller
{
    public function test($text)
    {
        TestJob::dispatch();
        return $text;
    }
}
