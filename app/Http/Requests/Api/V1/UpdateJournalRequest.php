<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class UpdateJournalRequest extends BaseJournalRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data' => 'sometimes|array',
            'data.attributes' => 'sometimes|array',
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.content' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:submitted,reviewed,approved',
            'data.relationships.author.data.id' => 'sometimes|integer|exists:users,id',
        ];

        // Kung ang user ay may restriction na update-own-journal, ipinagbabawal ang pagpapalit ng author field
        $user = $this->user();
        if ($user && $user->tokenCan(Abilities::UpdateOwnJournal)) {
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }

        return $rules;
    }
}
