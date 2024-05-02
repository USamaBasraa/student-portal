<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// public route
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

// protected route (after login router)
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[UserController::class,'logout']);
    Route::get('/loggeduser',[UserController::class,'logged_user']);
    // yaa after login hota hai
    Route::post('/changepassword',[UserController::class,'change_password']);
    // before login hum reset krty hai not change
});