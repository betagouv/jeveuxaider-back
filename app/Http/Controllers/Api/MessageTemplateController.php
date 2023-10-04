<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageTemplateCreateRequest;
use App\Http\Requests\Api\MessageTemplateUpdateRequest;
use App\Models\MessageTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class MessageTemplateController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(MessageTemplate::class)
            ->allowedIncludes(['user'])
            ->defaultSort('name')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        return $results;
    }

    public function store(MessageTemplateCreateRequest $request)
    {
        if (! $request->validated()) {
            return $request->validated();
        }

        $messageTemplate =  MessageTemplate::create($request->validated());

        return $messageTemplate;
    }

    public function duplicate(Request $request, MessageTemplate $messageTemplate)
    {
        $this->authorize('duplicate', $messageTemplate);

        $newModel = $messageTemplate->replicate();
        $newModel->user_id = Auth::guard('api')->user()->id;
        $newModel->name = $messageTemplate->name . ' (duplication)';
        $newModel->save();

        return $newModel;
    }

    public function update(MessageTemplateUpdateRequest $request, MessageTemplate $messageTemplate)
    {
        $messageTemplate->update($request->validated());

        return $messageTemplate;
    }

    public function delete(Request $request, MessageTemplate $messageTemplate)
    {
        $this->authorize('delete', $messageTemplate);

        return (string) $messageTemplate->delete();
    }
}
