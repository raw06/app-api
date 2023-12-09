<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Shop;
use App\Repositories\Product\ProductRepository;
use App\Services\Base\BaseServiceImp;
use App\Services\Elasticsearch\ElasticsearchService;
use App\Traits\InstalledShop;
use Exception;
use Illuminate\Bus\Dispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Psr\Http\Client\ClientExceptionInterface;
use Shopify\Clients\Rest;


class ProductServiceImp extends BaseServiceImp implements ProductService
{
    use InstalledShop;

    protected $_repository;

    public function __construct(ProductRepository $repository)
    {
        $this->_repository = $repository;
    }


    /**
     * @inheritDoc
     */
    public function handleFilter(
        $shopId,
        $search,
        $hasQuestions,
        $ids = null,
        int $page = 1,
        int $limit = 10,
        ?string $searchBy = 'title'
    ) {
        try {
            /** @var Collection $products */
            return $this->_repository
                ->select([
                    'id',
                    'title',
                    'handle',
                    'image'
                ])
                ->where('shop_id', $shopId)
                ->where(function ($query) {
                    $query->where('status', null)
                        ->orWhereIn('status', [
                            'active',
                            'draft',
                            'archived'
                        ]);
                })
                ->when($hasQuestions !== 'all', function ($query) use ($hasQuestions) {
                    /** @var Builder $query */
                    $hasQuestions == "yes"
                        ? $query->has('questions')
                        : $query->doesntHave('questions');
                })
                ->when(strlen($search) > 0 && in_array($searchBy, Product::PRODUCT_SEARCH_FIELDS),
                    function (Builder $filter) use ($search, $searchBy) {
                        $filter->where($searchBy, 'like', "%$search%");
                    })
                ->when(isset($ids), function ($filter) use ($ids) {
                    $ids = explode(',', $ids);
                    /** @var Builder $filter */
                    $filter->whereIn('id', $ids);
                })
                ->withCount('questions')
                ->withCount('tags')
                ->paginate($limit);
        } catch (Exception $e) {
            logger()->error("Error when get products: {$e->getMessage()} {$e->getTraceAsString()}");
            return null;
        }

    }

    /**
     * @param $product
     * @param $shopId
     *
     * @return Product
     */
    public function createOrUpdateProductFromShopify($product, $shopId): Product
    {
        /** @var Product $shopProduct */
        $shopProduct = $this->_repository->updateOrCreate([
            'shop_id' => $shopId,
            'shopify_id' => $product['id'],
        ], [
            'title' => $product['title'],
            'handle' => $product['handle'],
            'vendor' => $product['vendor'],
            'tags' => $product['tags'] ?? null,
            'product_type' => $product['product_type'] ?? '',
            'image' => empty($product['image']) ? '' : $product['image']['src'],
            'status' => $product['status'] ?? null,
            'published_scope' => $product['published_scope'] ?? null,
            'template_suffix' => $product['template_suffix'] ?? null,
        ]);

        return $shopProduct;
    }

    public function fetchProductByInstall(Shop $shop)
    {
        try {
            $client = new Rest($shop->shop, $shop->access_token);
            $productsRes = $client->get('products');

            $products = $productsRes->getDecodedBody()['products'];

            if (!$products) {
                return;
            }

            $dbProducts = collect();
            foreach ($products as $product) {
                $dbProduct = $this->createOrUpdateProductFromShopify($product, $shop->id);
                $dbProducts->push($dbProduct);
            }
        } catch (Exception $e) {
            logger()->error("Fail to fetch product by install: $e");
        }
    }

    public function getProduct($shopId, $productId) {
        try {
            return $this->_repository->select()
                ->where([
                    'id' => $productId,
                    'shop_id' => $shopId
                ])
                ->first();
        } catch (\Exception $e) {
            logger()->error("Fail to get product: $e");
            return null;
        }
    }
}
