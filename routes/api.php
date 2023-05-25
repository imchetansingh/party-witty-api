<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Users Resources and endpoints */

Route::apiResource("users", UserController::class)
        ->missing(function (Request $request) {
            return response()->json(['success' => false,'message' => 'Resource not found',], 404);
        }
    );

// OTP verification
Route::post("users/verify", [UserController::class, 'verify']);

// user login

Route::post("users/login", [UserController::class, 'login']);



/* Artist Resources and endpoints */

Route::post("artist/register", [ArtistController::class, 'store']);




