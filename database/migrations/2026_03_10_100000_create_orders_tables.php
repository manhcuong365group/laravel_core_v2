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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('customer_name');
                $table->string('customer_phone', 20);
                $table->string('customer_email')->nullable();
                $table->text('customer_address')->nullable();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('shipping_fee', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->enum('status', ['new', 'completed', 'cancelled'])->default('new')->index();
                $table->text('notes')->nullable();
                $table->text('admin_notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->string('product_name_snapshot');
                $table->string('sku_snapshot')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->unsignedInteger('quantity')->default(1);
                $table->decimal('line_total', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_status_histories')) {
            Schema::create('order_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->string('from_status', 50)->nullable();
                $table->string('to_status', 50);
                $table->text('note')->nullable();
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

