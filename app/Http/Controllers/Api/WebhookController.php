<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WebhookSendinblueRequest;
use App\Services\Sendinblue;

class WebhookController extends Controller
{
    public function sendinblue(WebhookSendinblueRequest $request)
    {
        $payload = $request->validated();

        switch ($payload['event']) {
            case 'hard_bounce':
                Sendinblue::onHardBounce($payload);
                break;
            default:
                break;
        }
    }
}
