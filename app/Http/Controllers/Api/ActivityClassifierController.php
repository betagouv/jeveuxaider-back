<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Http\Requests\Api\ActivityClassifyRequest;
use App\Services\ActivityClassifier;


class ActivityClassifierController extends Controller
{
    public function evaluate(ActivityClassifyRequest $request)
    {
        $payload = $request->validated();
        return ActivityClassifier::evaluate($payload['description']);
    }
}
