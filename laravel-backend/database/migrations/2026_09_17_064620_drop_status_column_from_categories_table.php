<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('status')->after('description');
        });
    }
};

//

/*
Create factory for the following models:
Category Factory:
'name' => $this->faker->unique()->word(),
'description', => $this->faker->sentence(),

database seeder:
category::factory(10)->create();
category factory
public function index() {
$categories = Category::all();

return response()->json([
    'status' => true,
    'message' => 'Categories retrieved successfully.',
    'data' => $categories
], 200);}

*/