<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Requests\Api\DocumentCreateRequest;
use App\Http\Requests\Api\DocumentUpdateRequest;
use App\Http\Requests\Api\DocumentDeleteRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersTitleBodySearch;
use App\Http\Requests\Api\DocumentUploadRequest;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Document::role($request->header('Context-Role')))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
            ])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Document $document)
    {
        return $document;
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

    public function upload(DocumentUploadRequest $request, Document $document)
    {

        // Delete previous file
        if ($media = $document->getFirstMedia('documents')) {
            $media->delete();
        }

        $document
            ->addMedia($request->file('file'))
            ->toMediaCollection('documents');

        return $document;
    }

    public function uploadDelete(DocumentDeleteRequest $request, Document $document)
    {
        if ($media = $document->getFirstMedia('documents')) {
            $media->delete();
        }
    }

    public function delete(DocumentDeleteRequest $request, Document $document)
    {
        return (string) $document->delete();
    }
}
