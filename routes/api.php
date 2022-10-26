<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::fallback(function () {
    return response()->json(['error' => 'Not Found!,please provide given url as per request'], 422);
});

Route::group(['prefix' => 'v1'] ,function(){ 
    //auth
    Route::post('login',[AuthController::class, 'login'])->name('login');
    Route::post('registration',[AuthController::class, 'UserRegistration'])->name('registration');
    Route::group(['middleware' => ['auth:api']], function()
        {
   
        });
    
    });