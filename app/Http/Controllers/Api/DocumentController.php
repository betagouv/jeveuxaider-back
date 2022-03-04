<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Requests\Api\DocumentCreateRequest;
use App\Http\Requests\Api\DocumentUpdateRequest;
use App\Http\Requests\Api\DocumentDeleteRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTitleBodySearch;
use App\Http\Requests\Api\DocumentUploadRequest;
use App\Models\Profile;
use App\Notifications\DocumentSubmitted;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Document::role($request->header('Context-Role')))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
            ])
            ->allowedIncludes(['file'])
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Document $document)
    {
        return $document->load(['file']);
    }

    public function store(DocumentCreateRequest $request)
    {
        $document = Document::create($request->validated());

        return $document;
    }

    public function update(DocumentUpdateRequest $request, Document $document)
    {
        $document->update($request->validated());

        return $document;
    }

    // public function upload(DocumentUploadRequest $request, Document $document)
    // {

    //     // Delete previous file
    //     if ($media = $document->getFirstMedia('documents')) {
    //         $media->delete();
    //     }

    //     $document
    //         ->addMedia($request->file('file'))
    //         ->toMediaCollection('documents');

    //     return $document;
    // }

    // public function uploadDelete(DocumentDeleteRequest $request, Document $document)
    // {
    //     if ($media = $document->getFirstMedia('documents')) {
    //         $media->delete();
    //     }
    // }

    public function delete(DocumentDeleteRequest $request, Document $document)
    {
        return (string) $document->delete();
    }

    public function notify(Request $request, Document $document)
    {
        $referents = Profile::whereNotNull('referent_department')->get();

        if (!empty($referents)) {
            foreach ($referents as $referent) {
                $referent->notify(new DocumentSubmitted($document));
            }
        } else {
            return response('No referent has been notified', 402);
        }

        return response(['message' => 'Notifications has been sent.', 'notify_count' => count($referents)]);
    }
}
