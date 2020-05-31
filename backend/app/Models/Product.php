<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    /**
     * The table associated with this model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Up/sert products in batch
     * Eloquent doesn't have a bulk upsert ¯\_(ツ)_/¯
     *
     * @param array $batch
     */
    public static function batchUpsert(array $batch)
    {
        $sql = 'insert into products (external_variant_id, external_product_id, product_name, variant_name, image, price, stock_level, created_at, updated_at) values '
            . implode(',', $batch)
            . 'on duplicate key update product_name = values(product_name), '
            . 'variant_name = values(variant_name), '
            . 'image = values(image), '
            . 'price = values(price), '
            . 'stock_level = values(stock_level), '
            . 'updated_at = values(updated_at), '
            . 'price = values(price)';

        DB::insert($sql);
    }

}
