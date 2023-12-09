<?php

declare(strict_types=1);

namespace App\Lib;

use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\OAuth;
use Shopify\Context;
use Shopify\Utils;

class AuthRedirection
{
    public static function redirect(Request $request, bool $isOnline = false): RedirectResponse
    {
        $shop = $request->query("shop");
        if ($shop) {
            $shop = Utils::sanitizeShopDomain($shop);
        } elseif (auth()->check()) {
            $shop = domainFromShopName(auth()->user()->shop_name);
        }
        if (Context::$IS_EMBEDDED_APP && $request->query("embedded", false) === "1") {
            $redirectUrl = self::clientSideRedirectUrl($shop, $request->query());
        } else {
            $redirectTo = $request->query('redirectTo');
            $redirectUrl = self::serverSideRedirectUrl($shop, $isOnline, $redirectTo);
        }

        return redirect($redirectUrl);
    }

    private static function serverSideRedirectUrl($shopDomain, bool $isOnline, ?string $redirectTo): string
    {
        /** @var Shop $shop */
        $shop = Shop::where('shop', $shopDomain)->first();
        if (!$shop || $shop->uninstalled()) {
            info("{$shopDomain}: shop install");

            $installUrl = OAuth::begin(
                $shopDomain,
                '/auth/install/callback',
                $isOnline,
                ['App\Lib\CookieHandler', 'saveShopifyCookie'],
            );
            info("{$shopDomain}: shop install login end");
        } else {
            info("{$shopDomain}: shop login");
            $installUrl = OAuth::begin(
                $shopDomain,
                '/auth/callback',
                $isOnline,
                ['App\Lib\CookieHandler', 'saveShopifyCookie']
            );
            info("{$shopDomain}: shop login end");
        }
        if ($redirectTo) {
            $shopName = shopNameFromDomain($shopDomain);
            try {
                cache(["redirectTo_$shopName" => $redirectTo], now()->addMinute());
            } catch (\Exception $exception) {
                Log::error("Failed to save to cache $exception");
            }
        }

        info("redirect to $installUrl");
        return $installUrl;
    }

    private static function clientSideRedirectUrl($shop, array $query): string
    {
        $appHost = Context::$HOST_NAME;
        $redirectUri = urlencode("https://$appHost/login?shop=$shop");

        $queryString = http_build_query(array_merge($query, ["redirectUri" => $redirectUri]));
        return "/ExitIframe?$queryString";
    }
}
