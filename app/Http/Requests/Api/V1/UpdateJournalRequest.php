<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class UpdateJournalRequest extends BaseJournalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'data' => 'sometimes|array',
            'data.attributes' => 'sometimes|array',
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.content' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:submitted,reviewed,approved',
            'data.relationships' => 'sometimes|array',
            'data.relationships.author' => 'sometimes|array',
            'data.relationships.author.data' => 'sometimes|array',
            'data.relationships.author.data.id' => 'sometimes|integer|exists:users,id',
        ];

        $user = $this->user();

        // I-check kung ang user ay may restriction na update-own-journal
        if ($user && $user->tokenCan(Abilities::UpdateOwnJournal)) {
            $rules['data.relationships.author'] = 'prohibited';
            $rules['data.relationships.author.data'] = 'prohibited';
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }

        return $rules;
    }
}
