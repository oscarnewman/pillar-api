<?php

use App\Http\Controllers\UserController;
use App\Mail\Test;
use App\Services\AppleAuthValidator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/auth')->group(function () {
    Route::any('/boop', 'AuthController@boop');
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::middleware('auth:sanctum')->get('/me', 'AuthController@me');
});


Route::post('/verifyapple', function (Request $request, AppleAuthValidator $apple) {
    $request->validate(['id' => 'required|string']);
    $id = $request->id;

    $res =  $apple->validateIdToken($id);

    if ($res == false) {
        return ['ok' => false];
    }

    return response()->json($res);
});
Route::get('/mailtest', function () {
    $user = User::where('email', 'newman.oscar@gmail.com')->first();
    $res = Mail::to($user)->send(new Test());

    return ['ok' => true];
});

Route::resource('/users', 'UserController')->only((['store', 'destroy', 'update']));

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'deviceName' => 'required'
    ]);


    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages(['email' => ['The provided email and password are incorrect']]);
    }

    $token = $user->createToken($request->deviceName)->plainTextToken;
    return [
        'token' => $token
    ];
});
