<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Http\Filters\V1\AuthorFilter;

class AuthorsController extends APIController
{
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::filter($filters)->paginate()
        );
    }

    public function show(User $author)
    {
        if ($this->include('journals')) {
            return new UserResource($author->load('journals'));
        }

        return new UserResource($author);
    }
}