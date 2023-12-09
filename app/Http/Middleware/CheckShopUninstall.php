<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Services\Shop\ShopService;
use Closure;
use Illuminate\Http\Request;

class CheckShopUninstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $shopDomain = $request->get('shop_domain');
        $authorizeUrl = $request->fullUrl();

        $shop = Shop::query()->select()->where('shop', $shopDomain)->first();

        if ($shop && !$shop->access_token) {
            $host = getShopHost($shopDomain);
            $params = [
                'shop'       => $shopDomain,
                'host'       => $host,
                'redirectTo' => $authorizeUrl
            ];
            return redirect(
                '/login?' . http_build_query($params)
            );
        }
        return $next($request);
    }
}
