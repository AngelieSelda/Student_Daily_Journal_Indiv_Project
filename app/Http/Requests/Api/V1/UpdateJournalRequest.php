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
        $userIdAttr = $this->has('data.relationships.user.data.id')
            ? 'data.relationships.user.data.id'
            : 'data.relationships.author.data.id';

        $rules = [
            'data' => 'sometimes|array',
            'data.attributes' => 'sometimes|array',
            'data.attributes.title' => 'sometimes|string|max:255',
            'data.attributes.content' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:submitted,reviewed,approved',

            // Default: prohibited for regular students
            'data.relationships' => 'prohibited',
            'data.relationships.author' => 'prohibited',
            'data.relationships.author.data' => 'prohibited',
            'data.relationships.author.data.id' => 'prohibited',
            'data.relationships.user' => 'prohibited',
            'data.relationships.user.data' => 'prohibited',
            $userIdAttr => 'prohibited',
        ];

        $user = $this->user();

        if ($user?->tokenCan(Abilities::UpdateJournal)) {
            $rules['data.relationships'] = 'sometimes|array';
            $rules['data.relationships.author'] = 'sometimes|array';
            $rules['data.relationships.author.data'] = 'sometimes|array';
            $rules['data.relationships.author.data.id'] = 'sometimes|integer|exists:users,id';
            $rules['data.relationships.user'] = 'sometimes|array';
            $rules['data.relationships.user.data'] = 'sometimes|array';
            $rules[$userIdAttr] = 'sometimes|integer|exists:users,id';
        }

        return $rules;
    }

    public function bodyParameters(): array
    {
        return [
            'data.attributes.title' => [
                'description' => 'The updated title of the journal.',
                'example' => 'Updated Journal Title',
            ],
            'data.attributes.content' => [
                'description' => 'The updated content of the journal.',
                'example' => 'Updated content details goes here.',
            ],
            'data.attributes.status' => [
                'description' => 'The updated status of the journal (submitted, reviewed, or approved).',
                'example' => 'reviewed',
            ],
        ];
    }
}
