<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::query()->with('user')->latest()->get();

        return response()->json($posts->map(fn (Post $post): array => $this->postPayload($post))->values());
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::query()->create($request->validated())->load('user');

        return response()->json([
            'message' => 'Post created successfully',
            'data' => $this->postPayload($post),
        ], 201);
    }

    /**
     * @return array{id: int, message: ?string, user: array{name: string}|null, created_at: string|null}
     */
    private function postPayload(Post $post): array
    {
        return [
            'id' => $post->id,
            'message' => $post->message,
            'user' => $post->user === null ? null : ['name' => $post->user->name],
            'created_at' => $post->created_at?->toISOString(),
        ];
    }
}
