<?php

use App\Facades\ShopSetting;

if (!function_exists('shopNameFromDomain')) {
    /**
     * Extract shop name from shopify domain by removing .myshopify.com suffix
     *
     * @param string $domain shop domain
     * @return string
     */
    function shopNameFromDomain(string $domain): string
    {
        return substr($domain, 0, -14);
    }
}

if (!function_exists('domainFromShopName')) {
    /**
     * Create shop domain from shopify name by add suffix .myshopify.com
     *
     * @param string $shopName
     *
     * @return string
     */
    function domainFromShopName(string $shopName): string
    {
        return "{$shopName}.myshopify.com";
    }
}

if (!function_exists('getShopHost')) {
    function getShopHost(string $shopDomain): string
    {
        return base64_encode("$shopDomain/admin");
    }
}


if (!function_exists('isValidWebhookRequest')) {
    /**
     * Verify if webhook is called from Shopify.
     *
     * @param  $request
     *            request textual content
     *
     * @return boolean true if request is valid
     */
    function isValidWebhookRequest(\Illuminate\Http\Request $request): bool
    {
        $hmacHeader = $request->header('HTTP_X_SHOPIFY_HMAC_SHA256');
        if (!$hmacHeader || empty ($hmacHeader)) {
            $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        }
        if (!$hmacHeader | empty ($hmacHeader)) {
            $hmacHeader = $request->header('X-Shopify-Hmac-SHA256');
        }
        if (!$hmacHeader | empty ($hmacHeader)) {
            $hmacHeader = $request->server('X-Shopify-Hmac-SHA256');
        }

        $calculatedHmac = base64_encode(hash_hmac('sha256', $request->getContent(), config('shopify.shared_secret'), true));

        return $hmacHeader == $calculatedHmac;
    }
}
