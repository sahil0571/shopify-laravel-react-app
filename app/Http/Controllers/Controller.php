<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function badRequestJSONResponse($message){
        $response = array(
            "status" => 400,
            "message" => $message
        );

        return $response;
    }

    protected function unathorizedJSONResponse($message){
        $response = array(
            "status" => 403,
            "message" => $message
        );

        return $response;
    }

    protected function createJSONReponse( $message,$responseData=[] ){

        $response = array(
            "status" => 200,
            "message"=>$message
        );

        if (!empty($responseData)) {
            $response["data"] = $responseData;
        }

        return $response;
    }

    protected function retriveJSONReponse($data){
        return response()->json([
                'status' => true,
                'data' => $data
            ],200);
    }

    protected function updateJSONReponse($message){

        return response()->json([
            'status' => true,
            'data' => $message
        ],200);
    }

    protected function operationFailedJSONResponse($exceptionMsg = null){
        return response()->json([
            'status' => false,
            'data' => $exceptionMsg == null ? config('global.json.operation_failed') : $exceptionMsg
        ],403);
    }
}
