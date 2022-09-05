<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\DeclineParticipation;
use App\Jobs\ValidateParticipation;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Throwable;

class BulkOperationController extends Controller
{
    public function participationsValidate(Request $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;

        $batch = Bus::batch(
            array_map(function ($id) use ($currentUserId) {
                return new ValidateParticipation($id, $currentUserId);
            }, $request->input('ids'))
        )->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->allowFailures()->dispatch();

        return $batch->id;
    }

    public function participationsDecline(Request $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;

        $batch = Bus::batch(
            array_map(function ($id) use ($currentUserId, $request) {
                return new DeclineParticipation($id, $currentUserId, $request->input('reason'), $request->input('content'));
            }, $request->input('ids'))
        )->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->allowFailures()->dispatch();

        return $batch->id;
    }
}
