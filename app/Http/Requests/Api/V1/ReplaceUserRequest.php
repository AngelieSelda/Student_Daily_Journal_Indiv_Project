<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Validation\Rule;

class ReplaceUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.name' => 'required|string',
            'data.attributes.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],
            'data.attributes.isManager' => 'required|boolean',
            'data.attributes.password' => 'required|string|min:8',
        ];
    }
}
