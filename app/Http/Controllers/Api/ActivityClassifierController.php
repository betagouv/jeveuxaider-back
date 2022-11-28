<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Http\Requests\Api\ActivityClassifyRequest;
use App\Services\ActivityClassifier;


class ActivityClassifierController extends Controller
{
    public function sortedOptions(ActivityClassifyRequest $request)
    {
        $payload = $request->validated();
        return ActivityClassifier::sortedOptions($payload['description']);
    }
}
