<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ShopInfoController;
use App\Lib\AuthRedirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Shopify\Utils;
use App\Models\Session;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\LogInController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/auth', function (Request $request) {
    $shop = Utils::sanitizeShopDomain($request->query('shop'));

    // Delete any previously created OAuth sessions that were not completed (don't have an access token)
    Session::where('shop', $shop)->where('access_token', null)->delete();

    return AuthRedirection::redirect($request);
});
Route::get('/auth/install/callback', [AppController::class, 'authInstallCallback']);
Route::get('/auth/callback', [AppController::class, 'authCallback']);
Route::get('/login', [AppController::class, 'loginAuth'])
    ->name('shopify_login');

Route::group([
    'middleware' => ['shopify.auth:offline'],
    'prefix' => 'api'
], function () {
    Route::get('shop-info', [ShopInfoController::class, 'getShopInfo']);
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('file/{id}', [FileController::class, 'show'])->name('files.show');
    Route::post('files/create', [FileController::class, 'store'])->name('files.create');
    Route::delete('/file/{id}', [FileController::class, 'destroy'])->name('files.delete');
    Route::get('integrations', [IntegrationController::class, 'index']);
});

Route::post('/api/uninstall', [AppController::class, 'uninstalledWebhook'])->middleware('webhook-request');

Route::fallback([LogInController::class, 'index'])
    ->middleware('shopify.installed');
