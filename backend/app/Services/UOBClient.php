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
            . config('services.uob.key') . ':'
            . config('services.uob.password') . '@'
            . config('services.uob.host');

        $this->client = new Client([ 'base_uri' => $baseUrl ]);
    }

    /**
     * @param string $path
     * @param int $sinceId
     * @param array $otherParameters
     * @return string
     */
    private function getEndpointPath(string $path, int $sinceId, array $otherParameters = []): string
    {
        $parameters = array_merge($otherParameters, [
            'limit' => config('services.uob.results_limit'), // both endpoints needed have these parameters
            'since_id' => $sinceId
        ]);

        return config('services.uob.url_prefix') . config('services.uob.api_version') . $path
            . '?' . http_build_query($parameters);
    }

    /**
     * @param int $sinceId
     * @return array
     */
    public function getProducts(int $sinceId = 1): array
    {
        $endpoint = $this->getEndpointPath('/products.json', $sinceId, ['fields' => 'id,title,variants,image,inventory_quantity']);
        $response = $this->client->request('get', $endpoint);

        $products = json_decode($response->getBody(), true);
        return $products['products'] ?: [];
    }

    /**
     * @param int $sinceId
     * @return array
     */
    public function getOrders(int $sinceId = 1): array
    {
        $endpoint = $this->getEndpointPath('/orders.json', $sinceId, ['fields' => 'id,line_items']);
        $response = $this->client->request('get', $endpoint);

        $orders = json_decode($response->getBody(), true);
        return $orders['orders'] ?: [];
    }
}
