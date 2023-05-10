<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Throwable;

class BatchController extends Controller
{
    public function show(string $batchId)
    {
        return Bus::findBatch($batchId);
    }

    public function cancel(string $batchId)
    {
        $batch = Bus::findBatch($batchId);
        $batch->cancel();

        return $batch;
    }
}
