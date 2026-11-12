<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

/**
 * @group User Management
 *
 * APIs for managing users and students (Managers only).
 */
class UserController extends APIController
{
    /**
     * List users
     *
     * Returns a paginated list of all users.
     */
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Create user
     *
     * Creates a new user record.
     */
    public function store(StoreUserRequest $request)
    {
        if ($this->isAble('store', User::class)) {
            $user = User::create($request->mappedAttributes());

            return new UserResource($user);
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * Get user details
     *
     * Returns a single user profile. Supports ?include=journals.
     *
     * @queryParam include string Include related journals. Example: journals
     */
    public function show(User $user)
    {
        if ($this->include('journals')) {
            return new UserResource($user->load('journals'));
        }

        return new UserResource($user);
    }

    /**
     * Update user
     *
     * Updates specific fields of a user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($this->isAble('update', $user)) {
            $user->update($request->mappedAttributes());

            return new UserResource($user);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**
     * Replace user
     *
     * Completely replaces a user record.
     */
    public function replace(ReplaceUserRequest $request, User $user)
    {
        if ($this->isAble('replace', $user)) {
            $user->update($request->mappedAttributes());

            return new UserResource($user);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * Delete user
     *
     * Deletes a user record.
     */
    public function destroy(User $user)
    {
        if ($this->isAble('delete', $user)) {
            $user->delete();

            return $this->ok('User successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }
}
