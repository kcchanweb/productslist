<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('external_variant_id')->nullable(false)->unique('external_id')->index('external_id');
            $table->bigInteger('external_product_id')->nullable(false)->index('external_product_id');
            $table->string('product_name')->nullable(false);
            $table->string('variant_name')->nullable(false);
            $table->string('image')->nullable(true);
            $table->decimal('price')->nullable(false);
            $table->integer('stock_level')->nullable(false)->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('external_order_id')->nullable(false)->index('external_order_id');
            $table->bigInteger('external_product_id')->nullable(false)->index('external_product_id');
            $table->bigInteger('external_variant_id')->nullable(false)->index('external_variant_id');
            $table->decimal('price')->nullable(false);
            $table->integer('quantity')->nullable(false)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('orders');
    }
}
