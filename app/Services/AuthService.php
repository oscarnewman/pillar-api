<?php

namespace App\Services;

use App\User;
use Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{

    private AppleAuthValidator $apple;

    public function __construct(AppleAuthValidator $apple)
    {
        $this->apple = $apple;
    }

    public function loginWithApple(string $idToken, string $deviceName)
    {
        // Validate the ID token FIRST
        $token = $this->apple->validateIdToken($idToken);

        $user = User::where('apple_id', $token['sub'])->first();

        if (!$user) {
            $emailUser = User::where('email', $token['email'])->first();
            if ($emailUser) {
                if ($emailUser->email_verified_at == NULL) {
                    $emailUser->email_verified_at == now();
                }

                $emailUser->appleId = $token['sub'];
                $emailUser->save();

                return $this->loginOnceAuthenticated($emailUser, $deviceName);
            }

            throw ValidationException::withMessages(['apple_id' => 'No user with this Apple ID or Email exists']);
        }

        return $this->loginOnceAuthenticated($user, $deviceName);
    }

    public function registerWithApple(string $firstName, string $lastName, string $email, string $idToken, string $deviceName)
    {
        // Validate the ID token FIRST
        $token = $this->apple->validateIdToken($idToken);

        // Check if we've already added this user by apple id
        $appleId = $token['sub'];
        $appleUser = User::where('apple_id', $appleId)->first();
        if ($appleUser) {
            // We already have an apple user, so try to log in and update any relevant fields
            return $this->loginOnceAuthenticated($appleUser, $deviceName);
        }

        $emailUser = User::where('email', $email)->first();
        if ($emailUser) {
            // They've signed in with email (but not apple) before, so merge accounts
            // This assume's we're not dealing with a relay email (which can't be merged)

            if ($emailUser->email_verified_at == NULL) {
                $emailUser->email_verified_at == now();
            }

            $emailUser->appleId = $appleId;
            $emailUser->save();

            return $this->loginOnceAuthenticated($emailUser, $deviceName);
        }


        // We're actually registering a new user
        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'apple_id' => $appleId,
        ]);
        $user->email_verified_at = now();
        $user->save();

        return $this->loginOnceAuthenticated($user, $deviceName);
    }

    public function registerPassword(string $email, string $password, string $firstName, string $lastName)
    {

        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make($password)
        ]);


        return $user;
    }

    /**
     * ONLY CALL THIS ONCE TOKEN IS VERIFIED
     */
    private function loginOnceAuthenticated($user, $deviceName)
    {

        $existingTokens = $user->tokens->where('name', $deviceName);
        $existingTokens->each(fn ($token) => $token->delete());

        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'token' => $token
        ];
    }
}
