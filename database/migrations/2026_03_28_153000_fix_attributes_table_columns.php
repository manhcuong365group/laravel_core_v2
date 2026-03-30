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
        if (Schema::hasTable('attributes')) {
            Schema::table('attributes', function (Blueprint $table) {
                if (!Schema::hasColumn('attributes', 'slug')) {
                    $table->string('slug')->nullable()->after('name');
                }
                if (!Schema::hasColumn('attributes', 'type')) {
                    $table->string('type')->default('button')->after('slug');
                }
                if (!Schema::hasColumn('attributes', 'display_order')) {
                    $table->integer('display_order')->default(0)->after('type');
                }
            });
        }

        if (Schema::hasTable('attribute_values')) {
            Schema::table('attribute_values', function (Blueprint $table) {
                if (!Schema::hasColumn('attribute_values', 'slug')) {
                    $table->string('slug')->nullable()->after('value');
                }
                if (!Schema::hasColumn('attribute_values', 'meta_value')) {
                    $table->string('meta_value')->nullable()->after('slug');
                }
                if (!Schema::hasColumn('attribute_values', 'display_order')) {
                    $table->integer('display_order')->default(0)->after('meta_value');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn(['display_order', 'type']);
        });
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->dropColumn(['display_order', 'meta_value']);
        });
    }
};
