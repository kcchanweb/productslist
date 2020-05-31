<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Http\Responses\ProductsResponse;
use App\Models\ProductMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductsController extends Controller
{
    /**
     * Get a paginated list of products and metrics and return JSON
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function listProductMetrics(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'offset' => 'sometimes|numeric|min:0',
            'limit' => 'sometimes|numeric|min:0|max:100'
        ]);

        if ($validate->errors()->getMessages()) {
            throw new ValidationException($validate->errors()->getMessages());
        }

        $offset = (int)$request->get('offset', config('app.default_page_offset_size'));
        $limit = (int)$request->get('limit', config('app.default_page_limit_size'));

        $total = ProductMetric::all()->count();
        $productMetrics = ProductMetric::offset($offset)->limit($limit)->get()->toArray();

        $res = new ProductsResponse($offset, $limit, $total, $productMetrics);
        return $res->json();
    }
}
