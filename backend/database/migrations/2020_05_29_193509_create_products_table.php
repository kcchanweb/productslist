<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->bigInteger('external_id')->nullable(false)->unique('external_id');
            $table->string('name')->nullable(false);
            $table->decimal('price')->nullable(false);
            $table->integer('times_purchased')->nullable(false)->default(0);
            $table->integer('stock_level')->nullable(false)->default(0);
            $table->decimal('orders_value')->nullable(false)->default(0);
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
    }
}
