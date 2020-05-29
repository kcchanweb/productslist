<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class ValidationException
 * @package App\Exceptions
 */
class ValidationException extends Exception
{
    /** @var array */
    private $errors;

    /**
     * ValidationException constructor.
     * @param array $errors
     * @param string $message
     */
    public function __construct($errors = [], string $message = 'Invalid request')
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * @return JsonResponse
     */
    public function render()
    {
        return response()
            ->json([
                'message' => $this->getMessage(),
                'errors' => $this->errors
            ], JsonResponse::HTTP_BAD_REQUEST);
    }
}
