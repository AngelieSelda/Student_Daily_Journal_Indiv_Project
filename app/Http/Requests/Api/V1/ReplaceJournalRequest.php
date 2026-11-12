<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class ReplaceJournalRequest extends BaseJournalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * PUT request replaces the entire resource.
     * Principle of Least Privilege: Only managers (ReplaceJournal) can replace.
     */
    public function rules(): array
    {
        $userIdAttr = $this->has('data.relationships.user.data.id')
            ? 'data.relationships.user.data.id'
            : 'data.relationships.author.data.id';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string|max:255',
            'data.attributes.content' => 'required|string',
            'data.attributes.status' => 'required|string|in:submitted,reviewed,approved',

            // Deny by default for relationships
            'data.relationships' => 'prohibited',
            'data.relationships.author' => 'prohibited',
            'data.relationships.author.data' => 'prohibited',
            'data.relationships.author.data.id' => 'prohibited',
            'data.relationships.user' => 'prohibited',
            'data.relationships.user.data' => 'prohibited',
            'data.relationships.user.data.id' => 'prohibited',
        ];

        $user = $this->user();

        // Manager with ReplaceJournal ability is allowed to specify the author
        if ($user && $user->tokenCan(Abilities::ReplaceJournal)) {
            $rules['data.relationships'] = 'required|array';
            $rules['data.relationships.author'] = 'sometimes|array';
            $rules['data.relationships.author.data'] = 'sometimes|array';
            $rules['data.relationships.author.data.id'] = 'sometimes|integer|exists:users,id';
            $rules['data.relationships.user'] = 'sometimes|array';
            $rules['data.relationships.user.data'] = 'sometimes|array';
            $rules['data.relationships.user.data.id'] = 'sometimes|integer|exists:users,id';
            $rules[$userIdAttr] = 'required|integer|exists:users,id';
        }

        return $rules;
    }
}
