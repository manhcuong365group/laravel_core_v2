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
            (Schema::hasColumn('articles', 'meta_title') || Schema::hasColumn('articles', 'meta_keywords'))
        ) {
            Schema::table('articles', function (Blueprint $table) {
                if (Schema::hasColumn('articles', 'meta_title')) {
                    $table->string('meta_title', 500)->nullable()->change();
                }
                if (Schema::hasColumn('articles', 'meta_keywords')) {
                    $table->string('meta_keywords', 500)->nullable()->change();
                }
            });
        }

        if (
            Schema::hasTable('products') &&
            (Schema::hasColumn('products', 'meta_title') || Schema::hasColumn('products', 'meta_keywords'))
        ) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'meta_title')) {
                    $table->string('meta_title', 500)->nullable()->change();
                }
                if (Schema::hasColumn('products', 'meta_keywords')) {
                    $table->string('meta_keywords', 500)->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: this migration only changes metadata column lengths.
    }
};
