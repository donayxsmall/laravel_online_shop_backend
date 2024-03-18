<?php
namespace App\Helpers;

class ApiResponse
{
    // public static function message($status, $message)
    // {
    //     return response()->json([
    //         'icon' => $status == 'success' ? 'success' : 'error',
    //         'title' => ucfirst($status) . '!',
    //         'message' => $message
    //     ], $status == 'success' ? 200 : 400);
    // }

    public static function success($message,$data=[],$code=200){
        $response = array_merge(['status' => 'success', 'message' => $message], $data);
        return response()->json($response,$code);
    }

    public static function successPagination($message,$data,$keyFieldData , $code=200){
        $response = [
            'status' => 'success',
            'message' => $message,
            'current_page' => $data->currentPage(),
            $keyFieldData => $data->items(),
            'first_page_url' => $data->url(1),
            'from' => $data->firstItem(),
            'last_page' => $data->lastPage(),
            'last_page_url' => $data->url($data->lastPage()),
            'links' => $data->links(),
            'next_page_url' => $data->nextPageUrl(),
            'path' => $data->url($data->currentPage()),
            'per_page' => $data->perPage(),
            'prev_page_url' => $data->previousPageUrl(),
            'to' => $data->lastItem(),
            'total' => $data->total(),
        ];
        // $response = array_merge(['status' => 'success', 'message' => $message], $data);
        return response()->json($response,$code);
    }

    public static function error($message,$error=null,$code=400){
        if($error == null) $error = $message;
        return response()->json([
            'status' => 'error',
            'message' =>  $message,
            'error' => $error,
        ],$code);
    }
}
