<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponses;

    public function index(DashboardRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $search = trim($filters['search'] ?? '');
        $categoryId = $filters['category'] ?? null;
        $sort = $filters['sort'] ?? 'newest';

        $statistics = Product::query()->toBase()
            ->selectRaw('COUNT(*) AS total_products, COALESCE(SUM(price), 0) AS total_value, COALESCE(AVG(price), 0) AS average_price')
            ->first();

        $categories = Category::query()->withCount('products')->orderBy('name')->get();

        $products = Product::query()->with('category:id,name')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->when($categoryId, function (Builder $query) use ($categoryId): void {
                $query->where('category_id', $categoryId);
            });

        match ($sort) {
            'name' => $products->orderBy('name')->orderBy('id'),
            'price_asc' => $products->orderBy('price')->orderBy('id'),
            'price_desc' => $products->orderByDesc('price')->orderBy('id'),
            default => $products->orderByDesc('id'),
        };

        return $this->ok('Dashboard retrieved successfully', [
            'statistics' => $statistics,
            'categories' => $categories,
            'products' => ProductResource::collection($products->paginate(10)->withQueryString())
                ->response()->getData(true),
        ]);
    }
}
