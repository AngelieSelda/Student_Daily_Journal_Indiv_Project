<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'type' => 'journal',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'content' => $this->content,
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => (string) $this->user_id,
                    ],
                ],
            ],
            'links' => [
                'self' => route('journals.show', ['journal' => $this->id]),
            ],
        ];
    }
}
