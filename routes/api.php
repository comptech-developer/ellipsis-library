<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BooksController;

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
    Route::post('edituser',[AuthController::class,'EditUser'])->name('edituser');
    Route::get('deleteuser/{user_id}',[AuthController::class,'DeleteUser'])->name('deleteuser');
    Route::get('getuser/{user_id}',[AuthController::class,'ViewUser'])->name('getuser');
    Route::get('books',[BooksController::class,'LoadBook'])->name('books');
    Route::post('addbook',[BooksController::class,'AddBook'])->name('addbook');
    Route::post('editbook',[BooksController::class,'EditBook'])->name('editbook');
    Route::get('getbook/{book_id}',[BooksController::class,'ViewBook'])->name('getbook');
    Route::get('deletebook/{book_id}',[BooksController::class,'DeleteBook'])->name('deletebook');
    Route::get('getpopularbooks',[BooksController::class,'LoadBookWithMoreLike'])->name('getpopularbooks');
        });
    
    });