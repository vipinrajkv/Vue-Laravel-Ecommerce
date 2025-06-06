<?php

namespace App\Classes;
use Illuminate\Http\JsonResponse;
class ApiResponse
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

        /**
     * success response method
     *
     * @param mixed $result The data to be returned
     * @param string $message  $message The success message
     * @return JsonResponse
     */
    public function sendResponse($result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
  
        return response()->json($response, 200);
    }

    /**
     * Sends an error response
     *
     * @param string  $error  The error message to return.
     * @param array $errorMessages Additional error messages or details
     * @param integer $code HTTP status code for the response
     * @return JsonResponse
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
  
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
  
        return response()->json($response, $code);
    }
}
