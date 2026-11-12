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
        $userIdAttr = $this->routeIs('journals.store')
            ? ($this->has('data.relationships.user.data.id')
                ? 'data.relationships.user.data.id'
                : 'data.relationships.author.data.id')
            : 'user';

        $userRule = 'required|integer|exists:users,id';

        $user = $this->user();

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string|max:255',
            'data.attributes.content' => 'required|string',
            'data.attributes.status' => 'sometimes|string|in:submitted,reviewed,approved',
            // Null-safe para sa Scribe generation kahit walang active user
            $userIdAttr => $user ? $userRule : 'required|integer',
        ];

        if ($user?->tokenCan(Abilities::CreateOwnJournal)) {
            $rules[$userIdAttr] = 'required|integer|size:' . $user->id;
        }

        if ($user?->tokenCan(Abilities::CreateJournal)) {
            $rules[$userIdAttr] = $userRule;
        }

        return $rules;
    }

    public function prepareForValidation(): void
    {
        if ($this->routeIs('users.journals.store')) {
            $this->merge([
                'user' => $this->route('user')?->getKey(),
            ]);
        }
    }

    public function bodyParameters(): array
    {
        return [
            'data.attributes.title' => [
                'description' => 'The title of the student journal entry.',
                'example' => 'My Learning Journey in API Development',
            ],
            'data.attributes.content' => [
                'description' => 'The main body content of the student journal.',
                'example' => 'Today we successfully implemented API documentation using Scribe.',
            ],
            'data.attributes.status' => [
                'description' => 'The status of the journal (submitted, reviewed, or approved).',
                'example' => 'submitted',
            ],
            'data.relationships.author.data.id' => [
                'description' => 'The ID of the author/user this journal belongs to.',
                'example' => 1,
            ],
        ];
    }
}
