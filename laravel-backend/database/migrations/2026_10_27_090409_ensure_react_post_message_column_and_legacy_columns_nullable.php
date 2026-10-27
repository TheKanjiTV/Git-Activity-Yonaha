<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            if (! Schema::hasColumn('posts', 'message')) {
                $table->string('message')->nullable();
            }

            if (Schema::hasColumn('posts', 'post')) {
                $table->string('post')->nullable()->change();
            }

            if (Schema::hasColumn('posts', 'description')) {
                $table->text('description')->nullable()->change();
            }

            if (Schema::hasColumn('posts', 'status')) {
                $table->string('status')->nullable()->change();
            }

            if (Schema::hasColumn('posts', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep the compatibility columns to avoid discarding posts written by the API.
    }
};
