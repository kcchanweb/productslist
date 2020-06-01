<?php


namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class OrdersRepository
{
    /**
     * Up/sert orders in batch
     * Eloquent doesn't have a bulk upsert ¯\_(ツ)_/¯
     *
     * @param array $batch
     */
    public function batchUpsert(array $batch)
    {
        $sql = 'insert into orders (external_order_id, external_product_id, external_variant_id, price, quantity, created_at, updated_at) values '
            . implode(',', $batch)
            . 'on duplicate key update price = values(price), '
            . 'quantity = values(quantity), '
            . 'updated_at = values(updated_at)';

        DB::insert($sql);
    }
}
