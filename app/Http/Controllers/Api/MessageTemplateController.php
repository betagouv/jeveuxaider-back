<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageTemplateCreateRequest;
use App\Http\Requests\Api\MessageTemplateUpdateRequest;
use App\Models\MessageTemplate;
use App\Models\Structure;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class MessageTemplateController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(MessageTemplate::role($request->header('Context-Role')))
            ->allowedIncludes(['user'])
            ->defaultSort('name')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        return $results;
    }

    public function store(MessageTemplateCreateRequest $request)
    {
        $attributes =  array_merge($request->validated(), [
            'user_id' => Auth::guard('api')->user()->id
        ]);

        $messageTemplate = MessageTemplate::create($attributes);

        if($messageTemplate->is_shared) {
            $userStructure = Structure::find(Auth::guard('api')->user()->contextable_id);
            $messageTemplate->sharable()->associate($userStructure);
        } else {
            $messageTemplate->sharable()->dissociate();
        }

        $messageTemplate->save();

        return $messageTemplate;
    }

    public function duplicate(Request $request, MessageTemplate $messageTemplate)
    {
        $this->authorize('duplicate', $messageTemplate);

        $newModel = $messageTemplate->replicate();
        $newModel->user_id = Auth::guard('api')->user()->id;
        $newModel->name = $messageTemplate->name . ' (duplication)';
        $newModel->is_shared = false;
        $newModel->save();

        return $newModel;
    }

    public function update(MessageTemplateUpdateRequest $request, MessageTemplate $messageTemplate)
    {
        $messageTemplate->update($request->validated());

        if($messageTemplate->is_shared) {
            $userStructure = Structure::find(Auth::guard('api')->user()->contextable_id);
            $messageTemplate->sharable()->associate($userStructure);
        } else {
            $messageTemplate->sharable()->dissociate();
        }

        $messageTemplate->save();

        return $messageTemplate;
    }

    public function delete(Request $request, MessageTemplate $messageTemplate)
    {
        $this->authorize('delete', $messageTemplate);

        return (string) $messageTemplate->delete();
    }
}
