<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\JournalResource;
use App\Models\Journal;

class JournalController extends APIController
{
    /**
     * GET /api/v1/journals
     */
    public function index()
    {
        if ($this->include('author')) {
            return JournalResource::collection(Journal::with('author')->paginate());
        }

        return JournalResource::collection(Journal::paginate());
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
}