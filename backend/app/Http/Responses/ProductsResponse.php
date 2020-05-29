<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

class ProductsResponse
{
    /** @var int  */
    private int $offset;

    /** @var int  */
    private int $limit;

    /** @var int  */
    private int $total;

    /** @var array  */
    private array $products;

    public function __construct(int $offset = 0, int $limit = 0, int $total = 0, array $products = [])
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->total = $total;
        $this->products = $products;
    }

    /**
     * @return JsonResponse
     */
    public function json(): JsonResponse
    {
        return response()->json([
            'pagination' => [
                'offset' => $this->offset,
                'limit' => $this->limit,
                'total' => $this->total
            ],
            'data' => $this->products,
            'links' => [
                'next' => $this->total <= $this->offset + $this->limit ? null : route('store.products', [
                    'offset' => $this->offset + $this->limit,
                    'limit' => $this->limit
                ]),
                'prev' => 0 > $this->offset - $this->limit ? null : route('store.products', [
                    'offset' => $this->offset - $this->limit,
                    'limit' => $this->limit
                ]),
            ]
        ]);
    }
}
