<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
http://127.0.0.1:8000/api/login | POST method | in Headers add =>>> key:Accept and value:application/json

example:
{
    "email": "purdy.una@example.org",
    "password": "password"
}
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('events', EventController::class)->only(['index', 'show']);
Route::apiResource('events', EventController::class)->except(['index', 'show'])->middleware('auth:sanctum')->middleware('throttle:api');

Route::apiResource('events.attendees', AttendeeController::class)->scoped()->except(['update'])->only(['index', 'show']) ; 
Route::apiResource('events.attendees', AttendeeController::class)->scoped()->only(['destroy', 'store'])->middleware('auth:sanctum')->middleware('throttle:api'); 
