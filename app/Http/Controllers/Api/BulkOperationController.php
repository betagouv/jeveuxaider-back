<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\DeclineParticipation;
use App\Jobs\ValidateParticipation;
use App\Notifications\BulkOperationsParticipationsDeclined;
use App\Notifications\BulkOperationsParticipationsValidated;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Throwable;

class BulkOperationController extends Controller
{
    public function participationsValidate(Request $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;
        $ids = $request->input('ids');

        $batch = Bus::batch(
            array_map(function ($id) use ($currentUserId) {
                return new ValidateParticipation($id, $currentUserId);
            }, $ids)
        )->then(function (Batch $batch) use ($ids, $currentUserId) {
            // All jobs completed successfully...
            Notification::route('slack', config('services.slack.hook_url'))
                ->notify(new BulkOperationsParticipationsValidated($ids, $currentUserId));
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
        $ids = $request->input('ids');
        $reason = $request->input('reason');
        $content = $request->input('content');

        $batch = Bus::batch(
            array_map(function ($id) use ($currentUserId, $reason, $content) {
                return new DeclineParticipation($id, $currentUserId, $reason, $content);
            }, $ids)
        )->then(function (Batch $batch) use ($ids, $currentUserId, $reason, $content) {
            // All jobs completed successfully...
            Notification::route('slack', config('services.slack.hook_url'))
                ->notify(new BulkOperationsParticipationsDeclined($ids, $currentUserId, $reason, $content));
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->allowFailures()->dispatch();

        return $batch->id;
    }
}
