<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreJournalRequest extends BaseJournalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Support both 'author' and 'user' relationships, as well as nested route 'user'
        $userIdAttr = $this->routeIs('journals.store')
            ? ($this->has('data.relationships.user.data.id')
                ? 'data.relationships.user.data.id'
                : 'data.relationships.author.data.id')
            : 'user';

        $userRule = 'required|integer|exists:users,id';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string|max:255',
            'data.attributes.content' => 'required|string',
            'data.attributes.status' => 'sometimes|string|in:submitted,reviewed,approved',
            $userIdAttr => $userRule,
        ];

        // Default: Principle of Least Privilege
        $user = $this->user();

        // Regular student (CreateOwnJournal): author MUST be themselves (size check)
        if ($user && $user->tokenCan(Abilities::CreateOwnJournal)) {
            $rules[$userIdAttr] = 'required|integer|size:' . $user->id;
        }

        // Manager with broad ability (CreateJournal): can assign to any valid user
        if ($user && $user->tokenCan(Abilities::CreateJournal)) {
            $rules[$userIdAttr] = $userRule;
        }

        return $rules;
    }

    /**
     * Kapag galing sa nested route (users.journals.store), kunin ang user_id mula sa URL.
     */
    protected function prepareForValidation(): void
    {
        if ($this->routeIs('users.journals.store')) {
            $this->merge([
                'user' => $this->route('user')->getKey(),
            ]);
        }
    }
}
