<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 7/01/18
 * Time: 2:16
 */

namespace App\Helpers;
use Response;

class ApiResponseHelper
{
    public static function success(array $data = [], $message = '', $responseCode = 200)
    {
        $returndata = [];
        $returnData['success'] = true;
        if($message){
            $returnData['message'] = $message;
        }
        foreach($data as $key => $dataItem){
            $returnData[$key] = $dataItem;
        }
        return Response::json($returnData, $responseCode);
    }
    public static function error($errorMessage, $responseCode = 400)
    {
        return Response::json([
            'success' => false,
            'error' => $errorMessage
        ], $responseCode);
    }
}