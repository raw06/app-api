<?php

namespace App\Http\Controllers;

use App\Traits\InstalledShop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopInfoController extends Controller
{
    use InstalledShop;
    public function getShopInfo(): JsonResponse
    {
        $shop = $this->shop();

        return response()->json([
            'id' => $shop->id,
            'info' => [
                'name' => $shop->shop
            ]
        ]);
    }
}
