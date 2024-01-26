
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Integration\IntegrationLoginController;
use App\Http\Controllers\Integration\OauthController;
use App\Http\Controllers\Integration\IntegrationApproveAuthorizationController;
use App\Http\Controllers\Integration\IntegrationDenyAuthorizationController;
//use App\Http\Controllers\Integration\IntegrationAccessTokenController;
use App\Http\Controllers\Integration\FileController;

Route::group([
    'middleware' => ['shop.uninstall', 'web']
], function () {
    Route::get(
        '/autoLogin',
        [IntegrationLoginController::class, 'autoLogin']
    )->name('integration.login');

    Route::get(
        '/oauth/authorize',
        [OauthController::class, 'authorize']
    )->middleware(['auth']);

    Route::post(
        '/oauth/authorize',
        [
            IntegrationApproveAuthorizationController::class,
            'approve'
        ]
    )->name('passport.authorizations.approve');

    Route::delete(
        '/oauth/authorize',
        [
            IntegrationDenyAuthorizationController::class,
            'deny'
        ]
    )->name('passport.authorizations.deny');

//    Route::post(
//        'oauth/token',
//        [
//           IntegrationAccessTokenController::class,
//            'issueToken'
//        ]
//    )->name('passport.token');
});

Route::group([
    'prefix' => 'integration',
], function () {
    Route::get('/', [IntegrationLoginController::class, 'index'])
        ->name('integration.index');

    Route::group([
        'middleware' => [
            'throttle:60,1',
            'auth:integration',
            'valid.user'
        ]
    ], function () {
        Route::group(['middleware'=> 'scope:read_files'], function () {
            Route::get('/files', [FileController::class, 'index'])->name('api.files.index');
        });

        Route::group(['middleware' => 'scope:write_files'], function () {
           Route::post('files/create', [FileController::class, 'store'])->name('api.files.create');
           Route::delete('/file/{id}', [FileController::class, 'destroy'])->name('api.files.delete');
        });

    });

});
