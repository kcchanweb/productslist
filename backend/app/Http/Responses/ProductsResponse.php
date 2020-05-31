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
    private array $productMetrics;

    /**
     * ProductsResponse constructor.
     * @param int $offset
     * @param int $limit
     * @param int $total
     * @param array $productMetrics
     */
    public function __construct(int $offset = 0, int $limit = 0, int $total = 0, array $productMetrics = [])
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->total = $total;
        $this->productMetrics = $productMetrics;
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
            'data' => $this->productMetrics,
            'links' => [
                'next' => $this->total <= $this->offset + $this->limit ? null : route('store.product-metrics', [
                    'offset' => $this->offset + $this->limit,
                    'limit' => $this->limit
                ]),
                'prev' => 0 > $this->offset - $this->limit ? null : route('store.product-metrics', [
                    'offset' => $this->offset - $this->limit,
                    'limit' => $this->limit
                ]),
            ]
        ]);
    }
}
