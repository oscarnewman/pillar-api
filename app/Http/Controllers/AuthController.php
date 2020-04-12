<?php

namespace App\Http\Controllers;

use App\Http\Responses\AuthResponse;
use App\Services\AppleAuthValidator;
use App\Services\AuthService;
use App\User;
use Exception;
use Illuminate\Http\Request;
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

    public function register(Request $request, AuthService $auth)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email',
            'idToken' => 'required|string',
            'deviceName' => 'required|string',
        ]);

        return $auth->registerWithApple(
            $request->firstName,
            $request->lastName,
            $request->email,
            $request->idToken,
            $request->deviceName
        );
    }

    public function login(Request $request, AuthService $auth)
    {
        $request->validate(['idToken' => 'required|string', 'deviceName' => 'required|string']);

        return $this->success($auth->loginWithApple($request->idToken, $request->deviceName));
    }
}
