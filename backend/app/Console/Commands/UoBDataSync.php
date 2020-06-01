<?php

namespace App\Console\Commands;

use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Services\UOBClient;
use Illuminate\Console\Command;

class UoBDataSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uob:datasync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull products and orders data from UoB, aggregate and save';

    /**
     * @var UOBClient
     */
    private $uobClient;

    /**
     * @var ProductsRepository
     */
    private $productsRepository;

    /**
     * @var OrdersRepository
     */
    private $ordersRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->uobClient = new UOBClient();
        $this->productsRepository = new ProductsRepository();
        $this->ordersRepository = new OrdersRepository();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->persistAllProducts();
        $this->persistAllOrders();

        // data aggregated through the use of DB view
    }

    /**
     * Fetch all products from API, flatten products/variants and persist to DB
     *
     * @return void
     */
    private function persistAllProducts(): void
    {
        $sinceId = 1;
        $batch = [];

        do {
            $products = $this->uobClient->getProducts($sinceId);
            foreach ($products as $product) {
                // loop through variants
                foreach ($product['variants'] as $variant) {

                    echo "inserting product ${product['id']} - variant ${variant['id']}\n";
                    $batch[] = $this->buildProductInsertRow($product, $variant);

                    if (count($batch) === config('app.db_batch_size')) {
                        $this->productsRepository->batchUpsert($batch);
                        // reset after each insert
                        $batch = [];
                    }
                }
            }
            $sinceId = end($products)['id'];
        } while (count($products) === config('services.uob.results_limit'));

        // insert remaining products in non-full batch
        if (count($batch)) {
            $this->productsRepository->batchUpsert($batch);
            unset($batch);
        }
    }

    /**
     * Return string of product input data for sql query
     *
     * @param array $product
     * @param array $variant
     * @return string
     */
    private function buildProductInsertRow(array $product, array $variant): string
    {
        return '("'
            . implode('","', [
                    $variant['id'],
                    $variant['product_id'],
                    $product['title'],
                    $variant['title'],
                    $product['image'] && $product['image']['src'] ? $product['image']['src'] : null,
                    $variant['price'],
                    $variant['inventory_quantity'],
                    now(),
                    now()
                ]
            ) . '")';
    }

    /**
     * Return string of order input data for sql query
     *
     * @param array $order
     * @param array $item
     * @return string
     */
    private function buildOrderInsertRow(array $order, array $item): string
    {
        return '("'
            . implode('","', [
                $order['id'],
                $item['product_id'],
                $item['variant_id'],
                $item['price'],
                $item['quantity'],
                now(),
                now()
            ]
        ) . '")';
    }

    /**
     * Fetch all orders from API and persist to DB
     *
     * @returns void
     */
    private function persistAllOrders(): void
    {
        $sinceId = 1;
        $batch = [];


        do {
            $orders = $this->uobClient->getOrders($sinceId);
            foreach ($orders as $order) {
                // loop through line_items
                foreach ($order['line_items'] as $item) {

                    // some orders have null ids ?
                    if (is_numeric($item['product_id'])
                        && is_numeric($item['variant_id'])
                        && is_numeric($order['id'])) {

                        echo "inserting order ${order['id']} - variant ${item['variant_id']}\n";
                        $batch[] = $this->buildOrderInsertRow($order, $item);
                    }

                    if (count($batch) === config('app.db_batch_size')) {
                        $this->ordersRepository->batchUpsert($batch);
                        // reset after each insert
                        $batch = [];
                    }
                }
            }
        } while (count($orders) === config('services.uob.results_limit'));

        // insert remaining products in non-full batch
        if (count($batch)) {
            $this->ordersRepository->batchUpsert($batch);
            unset($batch);
        }
    }
}
