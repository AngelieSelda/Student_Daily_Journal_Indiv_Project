<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreJournalRequest extends BaseJournalRequest
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
        $userIdAttr = 'data.relationships.author.data.id';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string',
            'data.attributes.content' => 'required|string',
            'data.attributes.status' => 'required|string|in:submitted,reviewed,approved',
            $userIdAttr => 'required|integer|exists:users,id',
        ];

        // Kung ang user ay may restriction na create-own-journal lamang, kailangang tumugma ang id sa sarili niya
        $user = $this->user();
        if ($user && $user->tokenCan(Abilities::CreateOwnJournal)) {
            $rules[$userIdAttr] .= '|size:' . $user->id;
        }

        return $rules;
    }
}
