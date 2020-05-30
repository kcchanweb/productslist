<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Http\Responses\ProductsResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductsController extends Controller
{
    /**
     * Get a paginated list of products and return JSON
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function list(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'offset' => 'sometimes|numeric|min:0',
            'limit' => 'sometimes|numeric|min:0|max:100'
        ]);

        if ($validate->errors()->getMessages()) {
            throw new ValidationException($validate->errors()->getMessages());
        }

        dd(env('DEFAULT_PRODUCTS_LIMIT'));
        $offset = (int)$request->get('offset', env('DEFAULT_PRODUCTS_OFFSET'));
        $limit = (int)$request->get('limit', env('DEFAULT_PRODUCTS_LIMIT'));

        $total = DB::table('products')->count();
        $products = DB::table('products')->offset($offset)->limit($limit)->get([
            'id', 'name', 'price', 'times_purchased', 'stock_level', 'orders_value'
        ])->all();

        $res = new ProductsResponse($offset, $limit, $total, $products);
        return $res->json();
    }
}
