<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class AppBaseController extends Controller
{
    public function sendResponse($result, $message, $code = 200)
    {
        return Response::json(
            [
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ],
            $code
        );
    }

    public function sendError($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }

}
