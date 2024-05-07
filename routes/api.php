<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// http://127.0.0.1:8000/api/user/login = its for postmen URL

// public route (before login routes)
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

// protected route (after login router)
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[UserController::class,'logout']);
    Route::get('/loggeduser',[UserController::class,'logged_user']);
    Route::post('/changepassword',[UserController::class,'change_password']);
});