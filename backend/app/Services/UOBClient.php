<?php


namespace App\Services;

use GuzzleHttp\Client;

class UOBClient
{
    /**
     * @var Client
     */
    private Client $client;

    public function __construct()
    {
        $baseUrl = 'https://'
            . env('UOB_API_KEY') . ':'
            . env('UOB_API_PASS') . '@'
            . env('UOB_API_HOST');

        $this->client = new Client([ 'base_uri' => $baseUrl ]);
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        $response = $this->client->request('get',
            env('UOB_API_URL_PREFIX') . env('UOB_API_VERSION') . '/products.json');

        return json_decode($response->getBody(), true);
    }

    /**
     * @param int $sinceId
     * @return array
     */
    public function getOrders(int $sinceId = 1): array
    {
        $response = $this->client->request('get',
            env('UOB_API_URL_PREFIX') . env('UOB_API_VERSION')
            . '/orders.json?fields=line_items&limit=' . env('UOB_ORDERS_LIMIT')
            . '&since_id=' . $sinceId
        );

        return json_decode($response->getBody(), true);
    }
}
