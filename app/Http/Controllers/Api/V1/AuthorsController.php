<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class AuthorsController extends APIController // <-- Palitan din dito kung 'extends Controller' pa
{
    public function index()
    {
        if ($this->include('journals')) {
            return UserResource::collection(User::has('journals')->with('journals')->paginate());
        }

        return UserResource::collection(User::has('journals')->paginate());
    }

    public function show(User $author)
    {
        if ($this->include('journals')) {
            return new UserResource($author->load('journals'));
        }

        return new UserResource($author);
    }
}