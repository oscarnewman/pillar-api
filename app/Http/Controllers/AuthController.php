<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\AuthResponse;
use App\Rules\Jwt;
use App\Services\AppleAuthValidator;
use App\Services\AuthService;
use App\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function boop()
    {
        return responder()->success(['boop' => 'boop!']);
    }

    /**
     * Returns the user if logged in
     */
    public function me(Request $request)
    {
        return $this->success($request->user());
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            throw new UnauthorizedException("The email and/or password are not correct");
        }

        return $this->success(auth()->user())->respond(200);
    }

    public function registerApple(Request $request, AuthService $auth)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email',
            'idToken' => ['required', new Jwt()],
            'deviceName' => 'required|string',
        ]);

        return $this->success($auth->registerWithApple(
            $request->firstName,
            $request->lastName,
            $request->email,
            $request->idToken,
            $request->deviceName
        ));
    }

    public function loginApple(Request $request, AuthService $auth)
    {
        $request->validate(['idToken' => ['required', new Jwt()], 'deviceName' => 'required|string']);

        return $this->success($auth->loginWithApple($request->idToken, $request->deviceName));
    }
}
