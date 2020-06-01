<?php

namespace Tests\Feature;

use App\Models\ProductMetric;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery\Mock;
use Tests\TestCase;

/**
 * Class ProductMetricsTest
 * @package Tests\Feature
 *
 * @group functional
 */
class ProductMetricsTest extends TestCase
{
    public function testBasicTest()
    {
        $response = $this->get('/api/product-metrics');

        $response->assertStatus(200);
    }

    public function testInvalidOffsetParameter()
    {
        $response = $this->get('/api/product-metrics?offset=-1');

        $response->assertStatus(400);
    }

    public function testInvalidLimitParameter()
    {
        $response = $this->get('/api/product-metrics?limit=101');

        $response->assertStatus(400);
    }
}
