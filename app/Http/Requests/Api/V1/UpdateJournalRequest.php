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
            'data.attributes.title' => 'sometimes|string|max:255',
            'data.attributes.content' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:submitted,reviewed,approved',

            // Principle of Least Privilege: Deny by default
            // Regular users cannot modify or pass relationships (prohibited)
            'data.relationships' => 'prohibited',
            'data.relationships.author' => 'prohibited',
            'data.relationships.author.data' => 'prohibited',
            'data.relationships.author.data.id' => 'prohibited',
            'data.relationships.user' => 'prohibited',
            'data.relationships.user.data' => 'prohibited',
            'data.relationships.user.data.id' => 'prohibited',
        ];

        $user = $this->user();

        // Kung manager (may broad UpdateJournal ability), pinapayagang palitan ang author/user
        if ($user && $user->tokenCan(Abilities::UpdateJournal)) {
            $rules['data.relationships'] = 'sometimes|array';
            $rules['data.relationships.author'] = 'sometimes|array';
            $rules['data.relationships.author.data'] = 'sometimes|array';
            $rules['data.relationships.author.data.id'] = 'sometimes|integer|exists:users,id';
            $rules['data.relationships.user'] = 'sometimes|array';
            $rules['data.relationships.user.data'] = 'sometimes|array';
            $rules['data.relationships.user.data.id'] = 'sometimes|integer|exists:users,id';
        }

        return $rules;
    }
}
