<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\JournalFilter;
use App\Http\Resources\V1\JournalResource;
use App\Models\Journal;
use App\Http\Requests\Api\V1\StoreJournalRequest;
use App\Http\Requests\Api\V1\ReplaceJournalRequest;
use App\Http\Requests\Api\V1\UpdateJournalRequest;

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

    public function destroy(Journal $journal)
    {
        $journal->delete();

        return $this->ok('Journal successfully deleted');
    }

    public function replace(ReplaceJournalRequest $request, Journal $journal)
    {
        $journal->update($request->mappedAttributes());

        return new JournalResource($journal);
    }

    public function update(UpdateJournalRequest $request, Journal $journal)
    {
        $journal->update($request->mappedAttributes());

        return new JournalResource($journal);
    }
}