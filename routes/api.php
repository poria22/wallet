<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
//ساخت دارایی
Route::post('/asset/store', [\App\Http\Controllers\AssetController::class, 'store']);

Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

//تاریخچه تغییرات دارایی های کاربر
Route::get('/user/assets/histories/{user}', [\App\Http\Controllers\AssetHistoriesController::class, 'userHistories']);

//اعمال تغییرات دارایی
Route::post('/user/assets/IncreaseDecrease', [\App\Http\Controllers\UserAssetController::class, 'IncreaseDecrease']);
Route::post('/user/assets/convert', [\App\Http\Controllers\UserAssetController::class, 'convert']);

//گزارش دارایی های کاربر
Route::get('/user/assets/{user}', [\App\Http\Controllers\AssetController::class, 'getUserAssets']);

