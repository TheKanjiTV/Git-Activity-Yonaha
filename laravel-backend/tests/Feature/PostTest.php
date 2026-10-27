<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('posts can be listed and created', function () {
    $this->getJson('/api/posts')
        ->assertSuccessful()
        ->assertJsonCount(0);

    $this->postJson('/api/posts', ['message' => 'Hello world'])
        ->assertCreated()
        ->assertJsonPath('data.message', 'Hello world');

    $this->getJson('/api/posts')
        ->assertSuccessful()
        ->assertJsonCount(1)
        ->assertJsonPath('0.message', 'Hello world');
});

test('a post message is required', function () {
    $this->postJson('/api/posts', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('message');
});

test('the post factory matches the posts table', function () {
    $post = Post::factory()->create();

    expect($post->message)->not->toBeEmpty();
});
