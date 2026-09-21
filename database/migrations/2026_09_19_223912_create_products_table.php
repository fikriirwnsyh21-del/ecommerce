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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->index();
            $table->longText('description');
            $table->decimal('price', 12, 2)->index();
            $table->decimal('discount_price', 12, 2)->nullable()->index();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('weight')->default(200); // grams
            $table->enum('condition', ['new', 'used'])->default('new');
            $table->decimal('rating_avg', 3, 2)->default(0.00)->index();
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedBigInteger('sales_count')->default(0)->index();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
