<?php

namespace App\Listeners;

use App\Events\AppInstalled;
use App\Services\Product\ProductService;

class AppInstalledListener
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function handle(AppInstalled $event) {
        $shop = $event->getShop();
        $this->productService->fetchProductByInstall($shop);
    }
}
