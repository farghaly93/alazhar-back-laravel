<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\newsController;
use App\Http\Controllers\offerController;
use App\Http\Controllers\locationsController;
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

Route::get('/user', function (Request $request) {
    dd("Heelo there");
    return $request->user();
});

Route::post('/login', [authController::class, "login"]);
Route::post('/signup', [authController::class, "signup"]);

Route::post('/addOffer', [offerController::class, "addOffer"])->middleware('jwt.auth');
Route::post('/updateOffer', [offerController::class, "updateOffer"])->middleware('jwt.auth');
Route::post('/addClientOffer', [offerController::class, "addClientOffer"]);
Route::post('/filterOffers/{skip}/{limit}/{type}', [offerController::class, "filterOffers"]);
Route::get('/fetchOffers', [offerController::class, "fetchOffers"]);
Route::get('/getOffer/{id}', [offerController::class, "getOffer"]);
Route::get('/fetchFilters/{type}', [offerController::class, "fetchFilters"]);
Route::delete('/softDeleteOffer/{id}', [offerController::class, "deleteOffer"])->middleware('jwt.auth');

Route::post('/addNews', [newsController::class, 'addNewNews'])->middleware('jwt.auth');
Route::post('/updateNews', [newsController::class, 'updateNews'])->middleware('jwt.auth', 'cors');
Route::get('/fetchNews', [newsController::class, 'fetchNews']);
Route::get('/getNewsPost/{id}', [newsController::class, 'getNewsPost']);
Route::get('/search/{query}', [newsController::class, 'search']);
Route::get('/filterByDate/{date}', [newsController::class, 'filterByDate']);
Route::delete('/softDeleteNews/{id}', [newsController::class, "deleteNews"])->middleware('jwt.auth');


Route::post('/updateInfo', [InfoController::class, 'updateInfo'])->middleware('jwt.auth', 'cors');
Route::get('/fetchInfo', [InfoController::class, 'fetchInfo'])->middleware('cors');
Route::post('/sendMessage', [InfoController::class, 'sendMessage'])->middleware('cors');
Route::get('/fetchMessages', [InfoController::class, 'fetchMessages'])->middleware('cors');
Route::get('/deleteMessage/{id}', [InfoController::class, 'deleteMessage'])->middleware('cors');


Route::post('/addLocation', [locationsController::class, 'addLocation'])->middleware('jwt.auth', 'cors');
Route::get('/getLocations', [locationsController::class, 'getLocations'])->middleware('cors');
Route::post('/updateLocation', [locationsController::class, 'updateLocation'])->middleware('jwt.auth', 'cors');
Route::delete('/deleteLocation/{id}', [locationsController::class, 'deleteLocation'])->middleware('jwt.auth', 'cors');
