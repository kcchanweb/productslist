<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductMetricView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('create view product_metrics as
            select
                p.id,
                p.external_product_id,
                p.external_variant_id,
                concat(p.product_name, \' (\', p.variant_name, \')\') as name,
                p.image,
                p.stock_level,
                sum(o.quantity) as purchased,
                sum(o.price*o.quantity) as orders_value
            from products p left
            join orders o
                on o.external_product_id = p.external_product_id
                and o.external_variant_id=p.external_variant_id
            group by p.external_product_id, p.external_variant_id
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW product_metrics');
    }
}
