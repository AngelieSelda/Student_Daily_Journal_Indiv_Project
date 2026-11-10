<?php

namespace App\Policies\V1;

use App\Models\Journal;
use App\Models\User;
use App\Permissions\V1\Abilities;

/**
 * Authorization rules for journals. Each method returns true (allowed) or false (denied).
 * The rules read the abilities stored in the user's JWT.
 */
class JournalPolicy
{
    /**
     * May this user create a journal? Needs the broad or the "own" create ability.
     */
    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CreateJournal)
            || $user->tokenCan(Abilities::CreateOwnJournal);
    }

    /**
     * May this user update this journal (PATCH)?
     */
    public function update(User $user, Journal $journal): bool
    {
        // Broad ability: any journal (admin)
        if ($user->tokenCan(Abilities::UpdateJournal)) {
            return true;
        }

        // "Own" ability: only a journal the user owns (student)
        if ($user->tokenCan(Abilities::UpdateOwnJournal)) {
            return $user->id === $journal->user_id;
        }

        return false;
    }

    /**
     * May this user replace this journal (PUT)? Managers only.
     */
    public function replace(User $user, Journal $journal): bool
    {
        return $user->tokenCan(Abilities::ReplaceJournal);
    }

    /**
     * May this user delete this journal?
     */
    public function delete(User $user, Journal $journal): bool
    {
        // Broad ability: any journal (admin)
        if ($user->tokenCan(Abilities::DeleteJournal)) {
            return true;
        }

        // "Own" ability: only a journal the user owns (student)
        if ($user->tokenCan(Abilities::DeleteOwnJournal)) {
            return $user->id === $journal->user_id;
        }

        return false;
    }
}
