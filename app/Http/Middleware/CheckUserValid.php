<?php

namespace App\Http\Middleware;

use App\Traits\InstalledShop;
use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;

class CheckUserValid
{
    use InstalledShop;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $shop = $this->shop()->shop;
        $token =  $request->bearerToken();
        if($this->checkInvalidShopRequest($shop, $token)) {
            return $next($request);
        }
        return redirect('/');
    }

    private function checkInvalidShopRequest(
        $shopDomainInRequest,
        $accessToken
    ): bool {
        $shopDomainInToken = Configuration::forUnsecuredSigner()->parser()
            ->parse($accessToken)->claims()->get(
                'shop_domain'
            );
        if ($shopDomainInToken) {
            return $shopDomainInRequest == $shopDomainInToken;
        }
        return false;
    }
}
