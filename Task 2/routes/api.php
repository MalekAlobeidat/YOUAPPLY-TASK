<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\TaskManagementController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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

Route::group(['middleware' => ['auth:sanctum','phone_verified']], function () {
    // Route::apiResource('/task/management', TaskManagementController::class); //not working
    Route::get('task-management', [TaskManagementController::class, 'index']);
    Route::post('task-management', [TaskManagementController::class, 'store']);
    Route::get('task-management/{id}', [TaskManagementController::class, 'show']);
    Route::put('task-management/{id}', [TaskManagementController::class, 'update']);
    Route::delete('task-management/{id}', [TaskManagementController::class, 'destroy']);
});
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verification', [AuthController::class, 'verifyPhoneNumber']);
Route::post('/forget-password', [UserController::class, 'forgetPassword']);
