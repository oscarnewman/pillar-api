<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyCsrfToken;
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

Route::get('/', function(Request $request) {
    $name = '';
    if ($request->user()) {
        $name = ', ' . $request->user()->first_name;
    }
    return [
        'Hey there' . $name => "Thanks for checking out our API",
        'Apparently you also enjoy' => 'snooping around urls',
        'Wanna know more or come work with us?' => 'devs@pillar.gives'
    ];
});

Route::get('/health', fn () => 'OK');

Route::prefix('/auth')->group(function () {
//    Route::post('/registerApple', 'AuthController@registerApple');
//    Route::post('/loginApple', 'AuthController@loginApple');
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::middleware('auth:sanctum')->get('/me', 'AuthController@me');
});

