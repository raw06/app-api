<?php

namespace App\Http\Controllers;

use App\Events\AppInstalled;
use App\Lib\AuthRedirection;
use App\Models\Session;
use App\Models\Shop;
use App\Models\User;
use App\Services\Integration\IntegrationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Shopify\Auth\OAuth;
use Shopify\Utils;
use Shopify\Webhooks\Registry;
use Shopify\Webhooks\Topics;

class AppController extends Controller
{
    public function loginAuth(Request $request)
    {
        if (!$request->query('shop')) {
            return redirect()->route('add_app');
        }
        $shopDomain = Utils::sanitizeShopDomain($request->query('shop'));
        info("$shopDomain: loginAuth");

        // Delete any previously created OAuth sessions that were not completed (don't have an access token)
        Session::where('shop', $shopDomain)->where('access_token', null)->delete();

        return AuthRedirection::redirect($request);
    }

    public function authCallback(Request $request)
    {
        info('authCallback');
        $session = OAuth::callback(
            $request->cookie(),
            $request->query(),
            ['App\Lib\CookieHandler', 'saveShopifyCookie']
        );

        $shopDomain = Utils::sanitizeShopDomain($request->query('shop'));
        $shopName = shopNameFromDomain($shopDomain);

        /** @var Shop $shop */
        $shop = Shop::where('shop', $shopDomain)->first();
        $session->setAccessToken($shop->access_token);

        $user = User::where('shop_id', $shop->id)->first();
        auth()->login($user);

        $host = $request->query('host');

        try {
            $redirectTo = cache("redirectTo_$shopName");
            info("redirectTo= $redirectTo get");
            if ($redirectTo) {
                cache()->forget("redirectTo_$shopName");
            }
        } catch (Exception $e) {
            $redirectTo = false;
            logger()->error("failed to get from cache $e");
        }


        if ($redirectTo) {
            $existParams = Str::contains($redirectTo, '?');
            $symbol = $existParams ? "&" : "?";
            info("redirect to $redirectTo$symbol".http_build_query(['host' => $host, 'shop' => $shop->shop]));
            return redirect("$redirectTo$symbol".http_build_query(['host' => $host, 'shop' => $shop->shop]));
        } else {
            $redirectUrl = Utils::getEmbeddedAppUrl($host);
            info("redirect to $redirectUrl");
            return redirect($redirectUrl);
        }
    }

    public function authInstallCallback(Request $request) {
        $session = OAuth::callback(
            $request->cookie(),
            $request->query(),
            ['App\Lib\CookieHandler', 'saveShopifyCookie'],
        );

        $host = $request->query('host');
        $shopDomain = Utils::sanitizeShopDomain($request->query('shop'));
        $accessToken = $session->getAccessToken();

        /** @var Shop $shop */
        $shop = Shop::where('shop', $shopDomain)->first();
        if ($shop) {
            if (!$shop->uninstalled()) {
                $this->uninstallApp($shop, false);
            }
            $shop->install($accessToken)->save();
        } else {
            $shop = Shop::create([
                'shop'         => $shopDomain,
                'access_token' => $accessToken,
                'installed_at' => new Carbon(),
            ]);
        }
        $shop->createShopInstall();

        $user = $this->findOrCreateUser($shop);
        auth()->login($user);

        try {
            event(new AppInstalled($shop));
        } catch (Exception $e) {
            logger()->error("{$shop->shop}: AppInstalled failed {$e}");
        }
        $response = Registry::register(
            '/api/uninstall',
            Topics::APP_UNINSTALLED,
            $shopDomain,
            $session->getAccessToken()
        );
        if ($response->isSuccess()) {
            info("Registered APP_UNINSTALLED webhook for shop $shopDomain");
        } else {
            logger()->error("Failed to register APP_UNINSTALLED webhook for shop $shopDomain with response body: " .
                print_r($response->getBody(), true)
            );
        }

        $redirectUrl = Utils::getEmbeddedAppUrl($host);
        try {
            event(new AppInstalled($shop));
        } catch (Exception $e) {
            logger()->error("{$shop->shop}: AppInstalled failed {$e}");
        }

        return redirect($redirectUrl);
    }

    public function uninstalledWebhook(Request $request) {
        $content = $request->getContent();
        $domain = json_decode($content)->myshopify_domain;
        $shopName = shopNameFromDomain($domain);
        Log::info("{$shopName}: uninstalled webhook");

        $shop = Shop::where('shop', $domain)->first();
        if (!$shop) {
            Log::warning("{$shopName}: uninstalled webhook can't find shop with content: {$content}");
            return;
        }
        $userId = $shop->appUser->id;
        /** @var IntegrationService $integrationService */
        $integrationService = app(IntegrationService::class);
        $integrationService->removeTokenIntegration($userId, $shop->shop);
        $this->uninstallApp($shop);
        return response()->json([]);
    }

    private function uninstallApp(Shop $shop)
    {
        $shop->uninstall()->deactivate()->save();

        Session::query()->where('shop', $shop->shop)->delete();
    }

    private function findOrCreateUser($shop): User
    {
        /** @var User $user */
        $user = User::firstOrCreate([
            'shop_id'   => $shop->id,
            'shop_name' => shopNameFromDomain($shop->shop),
        ]);

        return $user;
    }
}
