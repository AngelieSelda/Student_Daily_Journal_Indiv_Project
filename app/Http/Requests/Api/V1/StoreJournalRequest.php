<?php

namespace App\Http\Requests\Api\V1;

class StoreJournalRequest extends BaseJournalRequest
{
    public function authorize(): bool
    {
        return true; // Ang mismong authorization logic ay hahawakan ng Policy sa mga susunod na step
    }

    public function rules(): array
    {
        $authorIdAttr = $this->routeIs('journals.store')
            ? 'data.relationships.author.data.id'
            : 'user';

        return [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string',
            'data.attributes.content' => 'required|string',
            'data.attributes.status' => 'required|string|in:approved,pending,rejected',
            $authorIdAttr => 'required|integer|exists:users,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->routeIs('users.journals.store')) {
            $this->merge([
                'user' => $this->route('user')->getKey(),
            ]);
        }
    }
}