<?php

namespace App\Providers;

use App\Lib\DbSessionStorage;
use App\Services\ShopSettingApi;
use Illuminate\Support\ServiceProvider;
use Shopify\ApiVersion;
use Shopify\Context;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $host = str_replace('https://', '', env('APP_HOST', 'not_defined'));

        Context::initialize(
            env('SHOPIFY_API_KEY', 'not_defined'),
            env('SHOPIFY_API_SECRET', 'not_defined'),
            env('SHOPIFY_PERMISSIONS', 'not_defined'),
            $host,
            new DbSessionStorage(),
            ApiVersion::LATEST,
            true,
            false,
            null,
            '',
            null,
        );
        $this->app->bind(\App\Repositories\File\FileRepository::class, \App\Repositories\File\FileRepositoryEloquent::class);
        $this->app->bind(\App\Services\Integration\IntegrationService::class, \App\Services\Integration\IntegrationServiceImp::class);
        $this->app->bind(\App\Services\File\FileService::class, \App\Services\File\FileServiceImp::class);

    }

}
