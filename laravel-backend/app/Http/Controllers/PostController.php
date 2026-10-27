<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Post::query()->latest()->get());
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::query()->create($request->validated());

        return response()->json([
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }
}
