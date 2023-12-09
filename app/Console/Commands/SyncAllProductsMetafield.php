<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Services\Metafield\MetafieldService;
use Illuminate\Console\Command;

class SyncAllProductsMetafield extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:mf-products {--shop=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $shopName = $this->option('shop');

        $shop = Shop::where('shop', $shopName)->first();

        if (!$shop) {
            $this->line("Shop doesn't exist");
        }

        $products = $shop->products;

        if (count($products) > 0) {
            /** @var MetafieldService $metafieldService */
            $metafieldService = app(MetafieldService::class);
            foreach ($products as $product) {
                $metafieldService->updateQAProductMetafield(
                    $shop,
                    $product
                );
            }

            $this->line("Success synced " . count($products) . " answers.");
        } else {
            $this->line("Shop doesn't have any products to sync");
        }
    }
}
