<?php

namespace App\Http\Requests\Api\V1;

class UpdateJournalRequest extends BaseJournalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'sometimes|array',
            'data.attributes' => 'sometimes|array',
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.content' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:approved,pending,rejected',
            'data.relationships' => 'sometimes|array',
            'data.relationships.author' => 'sometimes|array',
            'data.relationships.author.data' => 'sometimes|array',
            'data.relationships.author.data.id' => 'sometimes|integer|exists:users,id',
        ];
    }
}