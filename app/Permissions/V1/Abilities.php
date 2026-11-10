<?php

namespace App\Permissions\V1;

use App\Models\User;

/**
 * Every ability name in one place, plus the rule that decides who receives which.
 * Format: resource:action. "own" means "only for things I own".
 */
final class Abilities
{
    // Journal abilities
    public const CreateJournal     = 'journal:create';
    public const CreateOwnJournal  = 'journal:own:create';
    public const UpdateJournal     = 'journal:update';
    public const UpdateOwnJournal  = 'journal:own:update';
    public const ReplaceJournal    = 'journal:replace';
    public const DeleteJournal     = 'journal:delete';
    public const DeleteOwnJournal  = 'journal:own:delete';

    // User management abilities (managers only)
    public const CreateUser  = 'user:create';
    public const UpdateUser  = 'user:update';
    public const ReplaceUser = 'user:replace';
    public const DeleteUser  = 'user:delete';

    /**
     * Returns the list of abilities a user receives.
     */
    public static function getAbilities(User $user): array
    {
        // Managers may act on anything
        if ($user->is_manager) {
            return [
                self::CreateJournal,
                self::UpdateJournal,
                self::ReplaceJournal,
                self::DeleteJournal,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        }

        // Regular users may only act on their own journals
        return [
            self::CreateOwnJournal,
            self::UpdateOwnJournal,
            self::DeleteOwnJournal,
        ];
    }
}