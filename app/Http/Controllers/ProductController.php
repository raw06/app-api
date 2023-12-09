<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductCollection;
use App\Services\Product\ProductService;
use App\Traits\InstalledShop;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use InstalledShop;

    public function index(Request $request, ProductService $productService) {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $status = $request->get('status', 'all');
        $ids = $request->get('ids')
            ? $request->get('ids')
            : '';
        $ids = $request->exists('ids') ? $ids : null;
        $search = $request->get('search');
        $searchBy = $request->get('searchBy', 'title');

        $products = $productService->handleFilter(
            $this->shopId(),
            $search,
            $status,
            $ids,
            $page,
            $limit,
            $searchBy
        );

        return new ProductCollection($products);
    }

    public function show(int $productId, ProductService $productService) {
        $product = $productService->getProduct($this->shopId(), $productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => "Fail to get review with id $productId",
            ], 500);
        }

        return $product;
    }
}
