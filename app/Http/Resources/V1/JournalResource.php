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
                // Itago ang content kapag nasa list/index endpoint ito
                'content' => $this->when(
                    !$request->routeIs(['journals.index', 'users.journals.index']),
                    $this->content
                ),
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
                    'links' => [
                        'self' => route('authors.show', ['author' => $this->user_id]),
                    ],
                ],
            ],
            'includes' => [
                'author' => new UserResource($this->whenLoaded('author')),
            ],
            'links' => [
                'self' => route('journals.show', ['journal' => $this->id]),
            ],
        ];
    }
}