<?php

namespace App\Http\Requests\Api\V1;

class ReplaceJournalRequest extends BaseJournalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * PUT request replaces the entire resource — all fields are required.
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string',
            'data.attributes.content' => 'required|string',
            'data.attributes.status' => 'required|string|in:submitted,reviewed,approved',
            'data.relationships' => 'required|array',
            'data.relationships.author' => 'required|array',
            'data.relationships.author.data' => 'required|array',
            'data.relationships.author.data.id' => 'required|integer|exists:users,id',
        ];
    }
}
