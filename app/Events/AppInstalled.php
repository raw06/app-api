<?php

namespace App\Events;

use App\Models\Shop;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppInstalled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }
}
