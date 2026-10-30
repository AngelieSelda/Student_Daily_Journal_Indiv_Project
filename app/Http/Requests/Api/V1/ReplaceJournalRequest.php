<?php

namespace App\Http\Requests\Api\V1;

class ReplaceJournalRequest extends BaseJournalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Ang PUT request ay nagre-replace ng buong resource, kaya walang pwedeng makaligtaan na field.
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string',
            'data.attributes.content' => 'required|string',
            'data.attributes.status' => 'required|string|in:approved,pending,rejected',
            'data.relationships' => 'required|array',
            'data.relationships.author' => 'required|array',
            'data.relationships.author.data' => 'required|array',
            'data.relationships.author.data.id' => 'required|integer|exists:users,id',
        ];
    }
}