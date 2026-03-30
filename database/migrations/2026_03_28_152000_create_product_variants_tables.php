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
        // 1. Attributes Table: e.g. "Color", "Size"
        if (!Schema::hasTable('attributes')) {
            Schema::create('attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('button'); // e.g. 'button', 'color', 'image', 'select'
                $table->integer('display_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Attribute Values Table: e.g. "Red", "Blue", "S", "M"
        if (!Schema::hasTable('attribute_values')) {
            Schema::create('attribute_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
                $table->string('value');
                $table->string('meta_value')->nullable(); // e.g. color hex code #FF0000
                $table->integer('display_order')->default(0);
                $table->timestamps();
            });
        }

        // 3. Product Variants Table: e.g. Product #1 with attributes "Red + Size M"
        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                
                // Unique SKU for this specific variant
                $table->string('sku')->nullable()->index();
                
                // Decimal prices as per standard
                $table->decimal('price', 15, 2)->nullable();
                $table->decimal('sale_price', 15, 2)->nullable();
                
                $table->integer('stock_quantity')->default(0);
                $table->string('image_path')->nullable(); // Overridden image
                
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Pivot Table for Variant and Attribute Values Map
        if (!Schema::hasTable('product_variant_attribute_value')) {
            Schema::create('product_variant_attribute_value', function (Blueprint $table) {
                $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
                $table->foreignId('attribute_value_id')->constrained('attribute_values')->cascadeOnDelete();
                
                $table->primary(['product_variant_id', 'attribute_value_id'], 'pv_av_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_attribute_value');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
    }
};
