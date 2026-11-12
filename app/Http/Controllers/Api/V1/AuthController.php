<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/**
 * @group Authentication
 *
 * APIs for registering, logging in, and managing auth tokens.
 */
class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Register a new user
     *
     * Registers a new student account and returns an access token.
     *
     * @unauthenticated
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::guard('api')->login($user);

        return $this->ok('Registered successfully', $this->formatToken($token));
    }

    /**
     * Log in
     *
     * Authenticates credentials and returns a JWT access token.
     *
     * @unauthenticated
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->ok('Login successful', $this->formatToken($token));
    }

    /**
     * Get current user
     *
     * Returns the currently authenticated user's profile.
     */
    public function me()
    {
        return $this->ok('User retrieved', Auth::guard('api')->user());
    }

    /**
     * Refresh token
     *
     * Refreshes the current JWT access token.
     */
    public function refresh()
    {
        return $this->ok('Token refreshed', $this->formatToken(Auth::guard('api')->refresh()));
    }

    /**
     * Log out
     *
     * Invalidates the active JWT access token.
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return $this->ok('Successfully logged out', null);
    }

    protected function formatToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ];
    }
}
