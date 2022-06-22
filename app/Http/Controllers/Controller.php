<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result,$message,$code = 200){
        $response = [
            'success' => true,
            'code' => $code,
            'data' => $result,
            'message' => $message
        ];

        return response()->json($response,$code);
    }

    public function sendError($errorMessage,$code = 404,$data = []){
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $errorMessage
        ];
        if(!empty($data)){
            $response['data'] = $data;
        }

        return response()->json($response,$code);
    }
}
