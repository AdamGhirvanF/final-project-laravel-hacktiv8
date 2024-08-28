<?php

namespace App\Helpers;

class ResponseHelper
{
    public function responseMessage($message){
        return response()->json([
          'status' => 'success',
          'message' => $message
        ],200);
      }

      public function responseMessageData($message,$data){
        return response()->json([
          'status' => 'success',
          'message' => $message,
          'data' => $data
        ],200);
      }

      public function responseData($data){
        return response()->json([
          'status' => 'success',
          'data' => $data
        ],200);
      }

      public function responseError($message){
        return response()->json([
          'status' => 'error',
          'message' => $message
        ],400);
      }

}
