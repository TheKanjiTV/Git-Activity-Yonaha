<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ApiResponses;

    public function index(): JsonResponse
    {
        $products = Product::with('category')
            ->orderBy('id')
            ->paginate(10);

        return $this->ok(
            'Products retrieved successfully',
            ProductResource::collection($products)
        );
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return $this->ok(
            'Product retrieved successfully',
            new ProductResource($product)
        );
    }

    public function query(): JsonResponse
    {
        // =====================================================
        // A. QUERYING TABLES
        // =====================================================

        // Get the name and price columns.
        $nameAndPrice = Product::select(
            'name',
            'price'
        )->get();

        // Products with category.
        $productsWithCategory = Product::with(
            'category'
        )->get();

        // Product ID and name ordered by price ASC.
        $productsOrderedByPrice = Product::select(
            'id',
            'name'
        )
            ->orderBy('price', 'asc')
            ->get();

        // First 5 products.
        $firstFiveProducts = Product::orderBy('id')->limit(5)
            ->get();

        // =====================================================
        // B. FILTERING DATA
        // =====================================================

        // Products with price >= 3.
        $priceGreaterThanOrEqual = Product::where(
            'price',
            '>=',
            3
        )->get();

        // Electronics products.
        $electronics = Product::whereHas(
            'category',
            function (Builder $q): void {
                $q->where(
                    'name',
                    'Electronics'
                );
            }
        )->get();

        // =====================================================
        // C. AGGREGATING DATA
        // =====================================================

        $totalPrice = Product::sum('price');

        $averagePrice = Product::avg('price');

        $highestPrice = Product::max('price');

        $lowestPrice = Product::min('price');

        // =====================================================
        // D. GROUPING, FILTERING, AND SORTING
        // =====================================================

        // Total price per category.
        $totalPricePerCategory = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
            ->select(
                'categories.name',
                DB::raw(
                    'SUM(products.price) as total_price'
                )
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Average price per category.
        $averagePricePerCategory = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
            ->select(
                'categories.name',
                DB::raw(
                    'AVG(products.price) as avg_price'
                )
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Lowest price per category.
        $lowestPricePerCategory = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
            ->select(
                'categories.name',
                DB::raw(
                    'MIN(products.price) as min_price'
                )
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Average price per category sorted ascending.
        $averagePriceSorted = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
            ->select(
                'categories.name',
                DB::raw(
                    'AVG(products.price) as avg_price'
                )
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('avg_price', 'asc')
            ->get();

        // Electronics and Books average price.
        $electronicsAndBooks = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
            ->whereIn(
                'categories.name',
                ['Electronics', 'Books']
            )
            ->select(
                'categories.name',
                DB::raw(
                    'AVG(products.price) as avg_price'
                )
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // =====================================================
        // RETURN ALL RESULTS
        // =====================================================

        $queryResults = [
            'name_and_price' => $nameAndPrice,

            'products_with_category' => $productsWithCategory,

            'products_ordered_by_price' => $productsOrderedByPrice,

            'first_five_products' => $firstFiveProducts,

            'price_greater_than_or_equal' => $priceGreaterThanOrEqual,

            'electronics' => $electronics,

            'total_price' => $totalPrice,

            'average_price' => $averagePrice,

            'highest_price' => $highestPrice,

            'lowest_price' => $lowestPrice,

            'total_price_per_category' => $totalPricePerCategory,

            'average_price_per_category' => $averagePricePerCategory,

            'lowest_price_per_category' => $lowestPricePerCategory,

            'average_price_sorted' => $averagePriceSorted,

            'electronics_and_books' => $electronicsAndBooks,
        ];

        return $this->success(
            'Query results retrieved successfully',
            $queryResults
        );
    }
}
