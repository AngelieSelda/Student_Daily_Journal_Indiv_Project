<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;


class BaseJournalRequest extends FormRequest
{
    /**
     * Isinasalin ang nested JSON:API payload sa mga database column name.
     */
    public function mappedAttributes(array $otherAttributes = []): array
    {
        $attributeMap = array_merge([
            'data.attributes.title' => 'title',
            'data.attributes.content' => 'content',
            'data.attributes.status' => 'status',
            'data.relationships.author.data.id' => 'user_id',
            'user' => 'user_id',
        ], $otherAttributes);

        $attributesToUpdate = [];

        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'data.attributes.status.in' => 'Ang status ay dapat na approved, pending, o rejected.',
        ];
    }
}