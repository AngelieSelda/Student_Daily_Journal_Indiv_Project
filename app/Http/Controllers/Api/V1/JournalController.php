<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\JournalFilter;
use App\Http\Requests\Api\V1\ReplaceJournalRequest;
use App\Http\Requests\Api\V1\StoreJournalRequest;
use App\Http\Requests\Api\V1\UpdateJournalRequest;
use App\Http\Resources\V1\JournalResource;
use App\Models\Journal;

/**
 * @group Journals
 *
 * APIs for managing student journal entries.
 */
class JournalController extends APIController
{
    /**
     * List journals
     *
     * Returns a paginated list of student journals. Supports filtering, sorting, and including the author.
     *
     * @queryParam status string Filter by status if applicable. Example: submitted
     * @queryParam sort string Sort by title or dates. Prefix with - for descending. Example: -createdAt
     * @queryParam include string Include related data. Example: author
     * @queryParam page integer The page number. Example: 1
     */
    public function index(JournalFilter $filters)
    {
        return JournalResource::collection(
            Journal::filter($filters)->paginate()
        );
    }

    /**
     * Create a journal
     *
     * Creates a new journal entry. Regular students can only create journals for themselves, while managers can assign any author.
     */
    public function store(StoreJournalRequest $request)
    {
        if ($this->isAble('store', Journal::class)) {
            $journal = Journal::create($request->mappedAttributes());

            return new JournalResource($journal);
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * Get a journal
     *
     * Returns a single student journal entry by its ID.
     */
    public function show(Journal $journal)
    {
        if ($this->include('author')) {
            return new JournalResource($journal->load('author'));
        }

        return new JournalResource($journal);
    }

    /**
     * Update a journal
     *
     * Updates specific fields of an existing journal entry.
     */
    public function update(UpdateJournalRequest $request, Journal $journal)
    {
        if ($this->isAble('update', $journal)) {
            $journal->update($request->mappedAttributes());

            return new JournalResource($journal);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**
     * Replace a journal
     *
     * Completely replaces an existing journal entry (Managers only).
     */
    public function replace(ReplaceJournalRequest $request, Journal $journal)
    {
        if ($this->isAble('replace', $journal)) {
            $journal->update($request->mappedAttributes());

            return new JournalResource($journal);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * Delete a journal
     *
     * Removes a journal entry from the database.
     */
    public function destroy(Journal $journal)
    {
        if ($this->isAble('delete', $journal)) {
            $journal->delete();

            return $this->ok('Journal successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }
}
