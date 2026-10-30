<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\JournalFilter;
use App\Http\Resources\V1\JournalResource;
use App\Models\Journal;
use App\Models\User;

class UserJournalsController extends APIController
{
    /**
     * GET /api/v1/users/{user}/journals
     * Ilista lamang ang mga journal na nakapangalan sa partikular na user/author.
     */
    public function index(User $user, JournalFilter $filters)
    {
        return JournalResource::collection(
            Journal::where('user_id', $user->id)->filter($filters)->paginate()
        );
    }
}
