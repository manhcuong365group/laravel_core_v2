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
        Schema::table('products', function (Blueprint $table) {
            $table->string('featured_image', 1000)->nullable()->after('content');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('featured_image', 1000)->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('featured_image');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('featured_image');
        });
    }
};
