<?php


namespace App\Repositories;


use App\Models\ProductMetric;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ProductMetricsRepository
{
    CONST CACHE_KEY = 'product_metrics';

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getPaginated(int $offset = 0, int $limit = 10): array
    {
        $cacheKey = $this->getCacheKey($offset, $limit);

        return ProductMetric::offset($offset)->limit($limit)->get()->toArray();

//        return \cache()->remember($cacheKey, Carbon::now()->addMinutes(config('database.redis.ttl')), function() use ($offset, $limit) {
//            return ProductMetric::offset($offset)->limit($limit)->get()->toArray();
//        });
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return string
     */
    private function getCacheKey(int $offset = 0, int $limit = 10): string
    {
        return self::CACHE_KEY . ':offset:' . $offset . ':limit:' . $limit;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getCount(): int
    {
        $cacheKey = self::CACHE_KEY . ':count';
        return ProductMetric::all()->count();
//        return \cache()->remember($cacheKey, Carbon::now()->addMinutes(config('database.redis.ttl')), function() {
//            return ProductMetric::all()->count();
//        });
    }

}
