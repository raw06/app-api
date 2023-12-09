<?php

namespace App\Traits;

use App\Models\Shop;

trait InstalledShop
{
    function shop()
    {
        if (auth()->check()) {
            return Shop::find($this->shopId());
        } else {
            return false;
        }
    }

    function shopId()
    {
        if (auth()->check()) {
            return auth()->user()->shop_id;
        } else {
            return false;
        }
    }
}
