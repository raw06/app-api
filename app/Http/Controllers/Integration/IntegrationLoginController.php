<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegrationLoginController extends Controller
{
    public function index()
    {
        return response()->json([
            'message'     => 'Welcome to Integration Shopify App Public API',
            'instruction' => 'https://documenter.getpostman.com/view/16942369/2s9YsT589g'
        ]);
    }

    public function autoLogin(Request $request)
    {
        $shopDomain = $request->query('shop_domain');
        $authorizeUrl = $request->query('authorize_url');
        $host = getShopHost($shopDomain);
        $params = [
            'shop'       => $shopDomain,
            'host'       => $host,
            'redirectTo' => $authorizeUrl
        ];
        return redirect(
            "/login?" . http_build_query($params)
        );
    }
}
