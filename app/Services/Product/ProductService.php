<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Shop;
use App\Services\Base\BaseServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ProductService extends BaseServiceInterface
{
    /**
     * @param $shopId
     * @param $search
     * @param $hasQuestions
     * @param  null  $ids
     * @param  int  $page
     * @param  int  $limit
     * @param  string|null  $searchBy
     * @return Builder
     */
    public function handleFilter(
        $shopId,
        $search,
        $hasQuestions,
        $ids = null,
        int $page = 1,
        int $limit = 10,
        ?string $searchBy = 'title'
    );

    public function getProduct($shopId, $productId);
//
//    /**
//     * @param $shopId
//     * @param $shopifyId
//     *
//     * @return mixed
//     */
//
//    /**
//     * @param $shopId
//     * @return mixed
//     */
//    public function countProductHasReviews($shopId): int;
//
//    /**
//     * @param $product
//     * @param $shopId
//     *
//     * @return Product
//     */
//
//    public function createOrUpdateProductFromShopify($product, $shopId);
//
//    /**
//     * @param $shopId
//     *
//     * @return boolean $bool
//     */
//    public function fetchProduct($shopId);
//
//    /**
//     * @param  Request  $request
//     * @param $shopName
//     *
//     * @return Product|null
//     */
//    public function handleHookCreate($request, $shopName);
//
//    /**
//     * @param  Request  $request
//     * @param $shopName
//     *
//     * @return Product|null
//     */
//    public function handleHookUpdate($request, $shopName);

//    /**
//     * @param  Request  $request
//     * @param $shopName
//     *
//     * @return Product|null
//     */
//    public function handleHookRemove($request, $shopName);

//    /**
//     * get and update latest all products
//     *
//     * @param  Shop  $shop
//     * @param  bool  $isFirstTime
//     */
//    public function fetchLatestProducts($shop, $isFirstTime = false);
//
//    /**
//     * Get all related products of reviews
//     *
//     * @param  Shop  $shop
//     * @param  Collection  $reviews
//     *
//     * @return Collection
//     */
//    public function getRelatedProducts(SecomappShop $shop, Collection $reviews);

    /**
     * fetch product while install app
     *
     * @param Shop $shop
     */
    public function fetchProductByInstall(Shop $shop);

//    /**
//     * Defined Register Hook product
//     * @param $shopId
//     */
//    public function defineHookProduct($shopId);
//
//    /**
//     * @param  Shop  $shop
//     * @param  Order  $order
//     * @return mixed
//     */
//    public function getProductsByOrder(Shop $shop, Order $order);
//
//    /**
//     * @param  int  $shopId
//     * @param  int  $offset
//     * @param  int  $limit
//     * @return mixed
//     */
//    public function getProductsForShow(int $shopId, int $offset, int $limit);
//
//    /**
//     * @param  Shop  $shop
//     * @return int
//     */
//    public function getNumberOfImportableProducts(Shop $shop);
//
//    /**
//     * @param  Plan  $plan
//     * @return int
//     */
//    public function getPlanLimitImportableProducts(Plan $plan): int;
//
//    /**
//     * @param  int  $shopId
//     * @param  int|null  $productGroupId
//     * @return Collection
//     */
//    public function getProductsByGroup(int $shopId, ?int $productGroupId);
//
//    /**
//     * @param  int  $shopId
//     * @param  array  $productGroupIds
//     * @return Collection
//     */
//    public function getProductsByGroups(int $shopId, array $productGroupIds);
//
//    /**
//     * @param  Shop  $shop
//     * @param $collection
//     * @return mixed
//     */
//    public function updateCollections(Shop $shop, $collection);
//
//    /**
//     * @param  Shop  $shop
//     * @return mixed
//     */
//    public function registerCollectionWebhook($shop);
//
//    /**
//     * @param  Shop  $shop
//     * @return mixed
//     */
//    public function removeCollectionWebhook($shop);
//
//    /**
//     * @param  Shop  $shop
//     * @param  array  $fields
//     * @return mixed
//     */
//    public function addFieldsProductUpdateWebhook($shop, $fields);
//
//    /**
//     * @param  Shop  $shop
//     * @param  array  $fields
//     * @return mixed
//     */
//    public function removeFieldsProductUpdateWebhook($shop, $fields);
//
//    /**
//     * @return mixed
//     */
//    public function getProductByShop(int $shopId, int $productId);
//
//    /**
//     * Get product mapping from csv file
//     * @param  int  $shopId
//     * @param  array|null  $productIds
//     * @param  array|null  $productNames
//     * @param  array|null  $productHandles
//     * @return Collection
//     */
//    public function getProductMappingFromFile(
//        int $shopId,
//        ?array $productIds,
//        ?array $productNames,
//        ?array $productHandles
//    );
//
//    public function getProductByShopifyId(int $shopId, int $productShopifyId);
//
//    /**
//     * @param  int  $shopId
//     * @param  array  $productShopifyIds
//     * @return mixed
//     */
//    public function getProductByShopifyIds(int $shopId, array $productShopifyIds);
//
//    /**
//     * @param  int  $shopId
//     * @param  array  $productIds
//     * @return mixed
//     */
//    public function getProductByIds(int $shopId, array $productIds);
//
//    public function getProductById(int $shopId, int $productId);

}
