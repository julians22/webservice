<?php

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\AuthController;
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

Route::post('login', [AuthController::class, 'login']);


Route::group(['prefix' => 'auction', 'as' => 'auction.'], function() {
    Route::get('/', [AuctionController::class, 'index']);
    Route::get('/{auction}', [AuctionController::class, 'show']);
    Route::get('search', [AuctionController::class, 'search']);
    Route::post('/', [AuctionController::class, 'store']);
    Route::put('/{auction}', [AuctionController::class, 'update']);
    Route::delete('/{auction}', [AuctionController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/user/auctions', [AuctionController::class, 'userAuctions']);

    Route::group(['prefix' => 'bid', 'as' => 'bid.'], function() {
        Route::post('/', [AuctionController::class, 'bid']);

        Route::get('/{id}', [AuctionController::class, 'showBid']);
    });
});
