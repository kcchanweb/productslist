<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductsController extends Controller
{
    /**
     * Get a paginated list of products and return JSON
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'offset' => 'sometimes|numeric|min:0',
            'limit' => 'sometimes|numeric|min:0|max:100'
        ]);

        if ($validate->errors()->getMessages()) {
            throw new ValidationException($validate->errors()->getMessages());
        }

        $offset = (int)$request->get('offset', env('DEFAULT_PRODUCTS_OFFSET'));
        $limit = (int)$request->get('limit', env('DEFAULT_PRODUCTS_LIMIT'));

//        app()->get('')
        $total = 0;

        // todo create ProductsResponse class to format output so that we can extend it to output data in other formats
        return response()->json([
            'pagination' => [
                'offset' => $offset,
                'limit' => $limit,
                'total' => $total
            ],
            'data' => [],
            'links' => [
                'next' => $total <= $offset + $limit ? null : route('store.products', [
                    'offset' => $offset + $limit,
                    'limit' => $limit
                ]),
                'prev' => 0 > $offset - $limit ? null : route('store.products', [
                    'offset' => $offset - $limit,
                    'limit' => $limit
                ]),
            ]
        ]);
    }
}
