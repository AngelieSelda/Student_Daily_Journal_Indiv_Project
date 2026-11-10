<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\JournalFilter;
use App\Http\Resources\V1\JournalResource;
use App\Models\Journal;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreJournalRequest;
use App\Http\Requests\Api\V1\UpdateJournalRequest;
use App\Http\Requests\Api\V1\ReplaceJournalRequest;

class UserJournalsController extends APIController
{
    /**
     * GET /api/v1/users/{user}/journals
     */
    public function index(User $user, JournalFilter $filters)
    {
        return JournalResource::collection(
            Journal::where('user_id', $user->id)->filter($filters)->paginate()
        );
    }

    /**
     * POST /api/v1/users/{user}/journals
     */
    public function store(StoreJournalRequest $request, User $user)
    {
        if ($this->isAble('store', Journal::class)) {
            return new JournalResource(
                Journal::create($request->mappedAttributes(['user' => 'user_id']))
            );
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * PUT /api/v1/users/{user}/journals/{journal}
     */
    public function replace(ReplaceJournalRequest $request, User $user, Journal $journal)
    {
        if ($this->isAble('replace', $journal)) {
            $journal->update($request->mappedAttributes());
            return new JournalResource($journal);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * PATCH /api/v1/users/{user}/journals/{journal}
     */
    public function update(UpdateJournalRequest $request, User $user, Journal $journal)
    {
        if ($this->isAble('update', $journal)) {
            $journal->update($request->mappedAttributes());
            return new JournalResource($journal);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**
     * DELETE /api/v1/users/{user}/journals/{journal}
     */
    public function destroy(User $user, Journal $journal)
    {
        if ($this->isAble('delete', $journal)) {
            $journal->delete();
            return $this->ok('Journal successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }
}
