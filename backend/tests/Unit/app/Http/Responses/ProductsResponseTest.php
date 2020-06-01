<?php

namespace Tests\Unit\App\Http\Responses;

use App\Http\Responses\ProductsResponse;
use Tests\TestCase;

class ProductsResponseTest extends TestCase
{
    /**
     * @group response
     */
    public function testNoParameter()
    {
        $productResponse = new ProductsResponse();
        $this->assertEquals([
            'pagination' => [
                'offset' => 0,
                'limit' => 0,
                'total' => 0
            ],
            'data' => [],
            'links' => [
                'next' => null,
                'prev' => null,
            ]
        ], $productResponse->format());
    }

    /**
     * @group response
     */
    public function testWithNextNoPrev()
    {
        $data = ['test', 'test2'];
        $productResponse = new ProductsResponse(0,2,4, $data);
        $this->assertEquals([
            'pagination' => [
                'offset' => 0,
                'limit' => 2,
                'total' => 4
            ],
            'data' => $data,
            'links' => [
                'prev' => null,
                'next' => 'https://local.loopreturns.com/api/product-metrics?offset=2&limit=2'
            ]
        ], $productResponse->format());
    }

    /**
     * @group response
     */
    public function testWithNextAndPrev()
    {
        $data = ['test', 'test2'];
        $productResponse = new ProductsResponse(2,2,6, $data);
        $this->assertEquals([
            'pagination' => [
                'offset' => 2,
                'limit' => 2,
                'total' => 6
            ],
            'data' => $data,
            'links' => [
                'prev' => 'https://local.loopreturns.com/api/product-metrics?offset=0&limit=2',
                'next' => 'https://local.loopreturns.com/api/product-metrics?offset=4&limit=2'
            ]
        ], $productResponse->format());
    }

    /**
     * @group response
     */
    public function testWithPrevNoNext()
    {
        $data = ['test'];
        $productResponse = new ProductsResponse(4,2,6, $data);
        $this->assertEquals([
            'pagination' => [
                'offset' => 4,
                'limit' => 2,
                'total' => 6
            ],
            'data' => $data,
            'links' => [
                'prev' => 'https://local.loopreturns.com/api/product-metrics?offset=2&limit=2',
                'next' => null
            ]
        ], $productResponse->format());
    }

}
