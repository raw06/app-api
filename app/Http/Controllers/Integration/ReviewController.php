<?php

namespace App\Http\Controllers\Integration;

use App\Helper\FileHelper\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\AddReview;
use App\Http\Requests\Review\CountReviewRequest;
use App\Http\Requests\Review\GetReviewRequest;
use App\Http\Resources\Review\ReviewCollection;
use App\Jobs\Integration\CreateReviewJob;
use App\Services\Elasticsearch\ElasticsearchSearchService;
use App\Services\Product\ProductService;
use App\Services\Review\ReviewService;
use App\Services\Shop\ShopService;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;

class ReviewController extends Controller
{
    private function checkInvalidShopRequest(
        $shopDomainInRequest,
        $accessToken
    ): bool {
        $shopDomainInToken = Configuration::forUnsecuredSigner()->parser()
            ->parse($accessToken)->claims()->get(
                'shop_domain'
            );
        if ($shopDomainInToken) {
            return $shopDomainInRequest == $shopDomainInToken;
        }
        return false;
    }

    public function index(
        GetReviewRequest $request,
        ElasticsearchSearchService $elasticsearchSearchService,
        ProductService $productService,
        ShopService $shopService
    ) {
        try {
            $shopDomain = $request->get('shop');
            if (!$shopDomain
                || !$this->checkInvalidShopRequest(
                    $shopDomain,
                    $request->bearerToken()
                )
            ) {
                logger()->error(
                    "Api integration: Invalid input shop"
                );
                return response()->json([
                    'data' => [],
                    'meta' => [
                        'total'        => 0,
                        'current_page' => 1,
                        'per_page'     => 0,
                        'last_page'    => 0
                    ]
                ]);
            }
            $shopName = shopNameFromDomain($shopDomain);
            $shopId = $shopService->getShopByShopName($shopName)->id;
            $product_ids = $request->validationData()['productIds'];

            if (collect($product_ids)->count() == 0) {
                logger()->error(
                    "$shopName request api integration:  Missing product shopify id"
                );
                return response()->json([
                    'data' => [],
                    'meta' => [
                        'total'        => 0,
                        'current_page' => 1,
                        'per_page'     => 0,
                        'last_page'    => 0
                    ]
                ]);
            }

            $productShopifyIds = $productService->getProductIdByShopifyIds(
                $shopId,
                $product_ids
            );
            $params = [
                'rating'     => $request->validationData()['rating'],
                'productIds' => $productShopifyIds,
                'status'     => 'approved'
            ];
            $reviews = $elasticsearchSearchService->getReviews(
                $shopName,
                $params
            );
            $reviews['data'] = $reviews['data']->map(function ($review) {
                return collect($review)->except(
                    [
                        'product_id',
                        'shop_id',
                        'review_id',
                        'product',
                        'is_pinned',
                        'is_spam',
                        'has_photo',
                        'has_content',
                        'has_reply',
                        'has_video',
                        'photos'
                    ]
                )->toArray();
            });
            return new ReviewCollection($reviews);
        } catch (\Throwable $exception) {
            logger()->error(
                "{$exception->getMessage()} {$exception->getTraceAsString()}"
            );
            return response()->json([
                'data' => [],
                'meta' => [
                    'total'        => 0,
                    'current_page' => 1,
                    'per_page'     => 0,
                    'last_page'    => 0
                ]
            ]);
        }
    }


    public function store(
        AddReview $request,
        ReviewService $reviewService,
        ProductService $productService,
        ShopService $shopService,
        FileHelper $fileHelper
    ) {
        $shopDomain = $request->get('shop');
        if (!$shopDomain
            || !$this->checkInvalidShopRequest(
                $shopDomain,
                $request->bearerToken()
            )
        ) {
            logger()->error(
                "Api integration: Invalid input shop"
            );
            return failedResult(
                "$shopDomain.:  Invalid input"
            );
        }
        $shopName = shopNameFromDomain($shopDomain);
        $productShopifyId = $request->get('product_id');
        $shop = $shopService->getShopByShopName($shopName);
        $shopId = $shop->id;

        $files = $request->file('files');

        $images = null;
        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                $images[] = $fileHelper->uploadBinary($file, "files/$shopName");
            }
        }

        $prepareData = $request->validationData() + [
                'photos' => json_encode($images)
            ];
        $randomId = auth('integration')->user()->tokens->first()->client_id
            . now()->timestamp;
        $product = $productService->getProductByShopifyId(
            $shopId,
            $productShopifyId
        );
        $transformReview = $reviewService->transformReview(
            $prepareData,
            $randomId,
            $product,
            $shopId,
            config('secom.review.status.approved'),
            config('secom.review.source.integration')
        );

        CreateReviewJob::dispatch(
            $shopName,
            $product,
            $transformReview
        );
    }


    public function show(
        $id,
        ReviewService $reviewService,
        Request $request,
        ShopService $shopService
    ): \Illuminate\Http\JsonResponse {
        $shopDomain = $request->get('shop');
        if (!$shopDomain
            || !$this->checkInvalidShopRequest(
                $shopDomain,
                $request->bearerToken()
            )
        ) {
            logger()->error(
                "Api integration: Invalid input shop"
            );
            return failedResult(
                "$shopDomain.:  Invalid input"
            );
        }
        $shopName = shopNameFromDomain($shopDomain);
        $shop = $shopService->getShopByShopName($shopName);
        $shopId = $shop->id;

        $review = collect($reviewService->getReview($shopId, $id))->except([
            'product_id',
            'shop_id',
            'review_id',
            'product',
            'is_pinned',
            'is_spam',
            'has_photo',
            'has_content',
            'has_reply',
            'has_video',
            'photos'
        ]);
        return response()->json([
            'review' => $review
        ]);
    }

    public function count(
        CountReviewRequest $request,
        ShopService $shopService,
        ElasticsearchSearchService $elasticsearchSearchService,
        ProductService $productService
    ): \Illuminate\Http\JsonResponse {
        $shopDomain = $request->get('shop');
        if (
            !$this->checkInvalidShopRequest(
                $shopDomain,
                $request->bearerToken()
            )
        ) {
            return failedResult(
                "Invalid shop in request"
            );
        }
        $shopName = shopNameFromDomain($shopDomain);
        $shop = $shopService->getShopByShopName($shopName);
        $shopId = $shop->id;

        $rating = $request->get('rating');
        $productShopifyId = $request->get('product_id');
        $productId = $productService->getProductByShopifyId(
            $shopId,
            $productShopifyId
        )->id;
        $response = $elasticsearchSearchService->countReviewsByShop($shopName, [
            'product_id' => $productId,
            'rating'     => $rating,
            'status'     => [1]
        ]);
        return response()->json($response);
    }


}
