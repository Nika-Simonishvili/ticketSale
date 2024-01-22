<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return Response::success([
            'token' => $user->createToken('Api token')->plainTextToken,
            'user' => UserResource::make($user),
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return Response::error([
                'message' => 'Invalid Credentials',
            ], 422);
        }

        return Response::success([
            'token' => Auth::user()->createToken('Api token')->plainTextToken,
            'user' => UserResource::make(Auth::user()),
        ]);
    }

    public function socialiteAuthRedirect(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function socialiteGoogleAuthCallback()
    {
        $socialiteUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['google_id' => $socialiteUser->id],
            [
                'first_name' => $socialiteUser->user['given_name'],
                'last_name' => $socialiteUser->user['family_name'],
                'email' => $socialiteUser->user['email'],
            ]
        );

        Auth::login($user);

        return Response::success([
            'token' => $user->createToken('Api token')->plainTextToken,
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return Response::success([
            'message' => 'Logged out.',
        ]);
    }
}
