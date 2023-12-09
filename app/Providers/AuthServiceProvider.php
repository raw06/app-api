<?php

namespace App\Providers;

use App\Models\PassportAuthCode;
use App\Models\PassportClient;
use App\Models\PassportPersonalAccessToken;
use App\Models\PassportRefreshToken;
use App\Models\PassportToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes();
        }

        Passport::useClientModel(PassportClient::class);
        Passport::useAuthCodeModel(PassportAuthCode::class);
        Passport::useTokenModel(PassportToken::class);
        Passport::useRefreshTokenModel(PassportRefreshToken::class);
        Passport::usePersonalAccessClientModel(PassportPersonalAccessToken::class);

        Passport::tokensCan([
            'read_files'   => 'Read Files',
            'write_files'  => 'Write Files',
        ]);

        Passport::tokensExpireIn(now()->addMinutes(1));
        Passport::refreshTokensExpireIn(now()->addMinutes(10));
    }
}
