<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('the dashboard API returns statistics categories and paginated products for React', function () {
    $category = Category::factory()->create(['name' => 'Electronics']);
    Product::factory(12)->for($category)->create(['price' => 25]);

    $response = $this->getJson('/api/v1/dashboard')->assertSuccessful()
        ->assertJsonPath('status', 200)
        ->assertJsonPath('data.statistics.total_products', 12)
        ->assertJsonPath('data.categories.0.name', 'Electronics')
        ->assertJsonPath('data.categories.0.products_count', 12)
        ->assertJsonCount(10, 'data.products.data')
        ->assertJsonPath('data.products.meta.total', 12)
        ->assertJsonStructure(['data' => ['products' => ['data' => ['*' => ['attributes' => ['name', 'description', 'price']]], 'meta', 'links']]]);

    expect((float) $response->json('data.statistics.total_value'))->toBe(300.0);
    expect((float) $response->json('data.statistics.average_price'))->toBe(25.0);
});

test('search category filtering and sorting work together without changing global statistics', function () {
    $books = Category::factory()->create(['name' => 'Books']);
    $electronics = Category::factory()->create(['name' => 'Electronics']);
    Product::factory()->for($books)->create(['name' => 'Laravel Guide', 'price' => 20]);
    Product::factory()->for($books)->create(['name' => 'Laravel Handbook', 'price' => 10]);
    Product::factory()->for($books)->create(['name' => 'Other Book', 'price' => 5]);
    Product::factory()->for($electronics)->create(['name' => 'Laravel Keyboard', 'price' => 30]);

    $this->getJson('/api/v1/dashboard?'.http_build_query(['search' => 'Laravel', 'category' => $books->id, 'sort' => 'price_asc']))
        ->assertSuccessful()
        ->assertJsonPath('data.products.data.0.attributes.name', 'Laravel Handbook')
        ->assertJsonPath('data.products.data.1.attributes.name', 'Laravel Guide')
        ->assertJsonCount(2, 'data.products.data')
        ->assertJsonPath('data.statistics.total_products', 4);
});

test('pagination retains the filters and sort order', function () {
    $category = Category::factory()->create();
    Product::factory(12)->for($category)->create(['name' => 'Matching Product']);

    $response = $this->getJson('/api/v1/dashboard?'.http_build_query(['search' => 'Matching', 'category' => $category->id, 'sort' => 'name', 'page' => 2]))
        ->assertSuccessful()
        ->assertJsonPath('data.products.meta.current_page', 2)
        ->assertJsonCount(2, 'data.products.data');

    expect($response->json('data.products.links.prev'))->toContain('search=Matching', 'category='.$category->id, 'sort=name');
});

test('the dashboard returns an empty catalog and empty filtered results', function () {
    $response = $this->getJson('/api/v1/dashboard')
        ->assertSuccessful()
        ->assertJsonCount(0, 'data.products.data')
        ->assertJsonPath('data.statistics.total_products', 0);

    expect((float) $response->json('data.statistics.total_value'))->toBe(0.0);

    Product::factory()->create(['name' => 'Existing product']);

    $this->getJson('/api/v1/dashboard?search=nonexistent')
        ->assertSuccessful()
        ->assertJsonCount(0, 'data.products.data')
        ->assertJsonPath('data.statistics.total_products', 1);
});

test('dashboard query parameters are validated', function (array $query, string $field) {
    $this->getJson('/api/v1/dashboard?'.http_build_query($query))
        ->assertUnprocessable()
        ->assertJsonValidationErrors($field);
})->with([
    'unknown sort' => [['sort' => 'invalid'], 'sort'],
    'array search' => [['search' => ['bad']], 'search'],
    'long search' => [['search' => str_repeat('a', 101)], 'search'],
    'unknown category' => [['category' => 99999], 'category'],
    'invalid page' => [['page' => 0], 'page'],
]);

test('the dashboard returns product and category names as JSON data', function () {
    $category = Category::factory()->create(['name' => '<script>alert(1)</script>']);
    Product::factory()->for($category)->create(['name' => '<img src=x onerror=alert(1)>']);

    $this->getJson('/api/v1/dashboard')
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('data.categories.0.name', $category->name)
        ->assertJsonPath('data.products.data.0.attributes.name', '<img src=x onerror=alert(1)>');
});

test('the old dashboard URL redirects to the React frontend', function () {
    $this->get('/dashboard')->assertRedirect(config('app.frontend_url'));
});
