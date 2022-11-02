<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Sendinblue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    public function sendinblue(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'event' => 'required|in:hard_bounce',
                'email' => 'required|email'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payload = $validator->validated();
        switch ($payload['event']) {
            case 'hard_bounce':
                Sendinblue::onHardBounce($payload);
                break;
            default:
                break;
        }

        return response()->json('webhook sendinblue called successfully', 200);
    }
}
