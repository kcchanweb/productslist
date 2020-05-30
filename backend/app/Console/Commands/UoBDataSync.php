<?php

namespace App\Console\Commands;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $uobClient = new UOBClient();
//        $products = $uobClient->getProducts();

        $orders = [];
        do {
            $ordersResponse = $uobClient->getOrders();
            //$orders = array_merge($orders, $ordersResponse['orders']);
            foreach ($ordersResponse['orders'] as $order) {
                foreach ($order['line_items'] as $item) {
                    $orders[] = 
                }
            }
        } while (count($ordersResponse['orders']) < env('UOB_ORDERS_LIMIT'));



//        dd([$products, $orders]);
    }

}
