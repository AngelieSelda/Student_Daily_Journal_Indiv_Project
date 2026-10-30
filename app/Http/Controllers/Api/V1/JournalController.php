<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\JournalFilter;
use App\Http\Resources\V1\JournalResource;
use App\Models\Journal;
use App\Http\Requests\Api\V1\StoreJournalRequest;

class JournalController extends APIController
{
    /**
     * GET /api/v1/journals
     */
    public function index(JournalFilter $filters)
    {
        return JournalResource::collection(
            Journal::filter($filters)->paginate()
        );
    }

    /**
     * GET /api/v1/journals/{journal}
     */
    public function show(Journal $journal)
    {
        if ($this->include('author')) {
            return new JournalResource($journal->load('author'));
        }

        return new JournalResource($journal);
    }

    public function store(StoreJournalRequest $request)
    {
        $journal = Journal::create($request->mappedAttributes());

        return new JournalResource($journal);
    }
}