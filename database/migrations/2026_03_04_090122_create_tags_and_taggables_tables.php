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
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('type')->default('general');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('taggables')) {
            Schema::create('taggables', function (Blueprint $table) {
                $table->foreignId('tag_id')->constrained()->onDelete('cascade');
                $table->string('taggable_type');
                $table->unsignedBigInteger('taggable_id');
                $table->primary(['tag_id', 'taggable_id', 'taggable_type']);
                $table->index(['taggable_type', 'taggable_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
    }
};
