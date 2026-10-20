<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponses;

    public function show(Category $category): JsonResponse
    {
        return $this->ok('Category retrieved successfully', [
            'type' => 'category',
            'id' => $category->id,
            'attributes' => [
                'name' => $category->name,
                'description' => $category->description,
            ],
            'links' => [
                'self' => route('categories.show', $category),
            ],
        ]);
    }
}
