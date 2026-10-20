<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // INDEX
    public function index()
{
    $products = Product::all();

    $result = [];

    foreach ($products as $product) {
        $result[] = [
            'id' => $product->id,
            'name' => $this->formatProductName($product->name),
            'slug' => $product->slug,
            'category' => $product->category,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'status' => $product->status,
        ];
    }

    return response()->json([
        'status' => true,
        'message' => 'Products retrieved successfully.',
        'data' => $result
    ], 200);
}

    // SHOW USING SLUG
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully.',
            'data' => [
                'id' => $product->id,
                'name' => $this->formatProductName($product->name),
                'slug' => $product->slug,
                'category' => $product->category,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'status' => $product->status,
            ]
        ], 200);
    }

    // CREATE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|string|max:255',
        ]);

        $name = $this->formatProductName($validated['name']);

        $slug = Str::slug($name);

        if (Product::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'category' => $this->formatProductName($validated['category']),
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully.',
            'data' => $product
        ], 201);
    }

    // UPDATE
    public function update(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
            'status' => 'sometimes|required|string|max:255',
        ]);

        if (isset($validated['name'])) {
            $validated['name'] = $this->formatProductName(
                $validated['name']
            );

            $validated['slug'] = Str::slug($validated['name']);

            if (
                Product::where('slug', $validated['slug'])
                    ->where('id', '!=', $product->id)
                    ->exists()
            ) {
                $validated['slug'] .= '-' . time();
            }
        }

        if (isset($validated['category'])) {
            $validated['category'] = $this->formatProductName(
                $validated['category']
            );
        }

        $product->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully.',
            'data' => $product
        ], 200);
    }

    // DELETE
    public function destroy(string $slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.'
        ], 200);
    }

    // USER-DEFINED FUNCTION
    private function formatProductName($name)
    {
        return ucwords(
            strtolower(
                trim($name)
            )
        );
    }
}