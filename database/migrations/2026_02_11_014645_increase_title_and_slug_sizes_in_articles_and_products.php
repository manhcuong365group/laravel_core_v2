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
        if (
            Schema::hasTable('articles') &&
            (Schema::hasColumn('articles', 'title') ||
                Schema::hasColumn('articles', 'slug') ||
                Schema::hasColumn('articles', 'meta_title') ||
                Schema::hasColumn('articles', 'meta_keywords'))
        ) {
            Schema::table('articles', function (Blueprint $table) {
                if (Schema::hasColumn('articles', 'title')) {
                    $table->string('title', 500)->change();
                }
                if (Schema::hasColumn('articles', 'slug')) {
                    $table->string('slug', 500)->change();
                }
                if (Schema::hasColumn('articles', 'meta_title')) {
                    $table->string('meta_title', 500)->change();
                }
                if (Schema::hasColumn('articles', 'meta_keywords')) {
                    $table->string('meta_keywords', 500)->change();
                }
            });
        }

        if (
            Schema::hasTable('products') &&
            (Schema::hasColumn('products', 'name') ||
                Schema::hasColumn('products', 'slug') ||
                Schema::hasColumn('products', 'meta_title') ||
                Schema::hasColumn('products', 'meta_keywords'))
        ) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'name')) {
                    $table->string('name', 500)->change();
                }
                if (Schema::hasColumn('products', 'slug')) {
                    $table->string('slug', 500)->change();
                }
                if (Schema::hasColumn('products', 'meta_title')) {
                    $table->string('meta_title', 500)->change();
                }
                if (Schema::hasColumn('products', 'meta_keywords')) {
                    $table->string('meta_keywords', 500)->change();
                }
            });
        }

        if (
            Schema::hasTable('pages') &&
            (Schema::hasColumn('pages', 'title') || Schema::hasColumn('pages', 'slug'))
        ) {
            Schema::table('pages', function (Blueprint $table) {
                if (Schema::hasColumn('pages', 'title')) {
                    $table->string('title', 500)->change();
                }
                if (Schema::hasColumn('pages', 'slug')) {
                    $table->string('slug', 500)->change();
                }
            });
        }

        if (
            Schema::hasTable('branches') &&
            (Schema::hasColumn('branches', 'name') || Schema::hasColumn('branches', 'slug'))
        ) {
            Schema::table('branches', function (Blueprint $table) {
                if (Schema::hasColumn('branches', 'name')) {
                    $table->string('name', 500)->change();
                }
                if (Schema::hasColumn('branches', 'slug')) {
                    $table->string('slug', 500)->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: this migration only changes column lengths.
    }
};
