<?php
namespace included;
use Illuminate\Support\Facades\Validator;
/*
 * send response
 * @var array $data
 * @var string $massage
 * @var number $status
 * @return mixed
 */
function sendResponse($data, $message, $status = 1 ) {
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];

    return response()->json( $response );
}

function sendError( $errorData, $message, $status = 200 ) {
    $response = [];
    $response['status'] = 0;
    $response[ 'message' ] = $message;
    if ( !empty( $errorData ) ) {
        $response[ 'data' ] = $errorData;
    }

    return response()->json( $response, $status );
}

function customValidation(array $data,array $rule){ 

    $validator = Validator::make($data,$rule);
    if (count($validator->errors()) > 0) {
        return ['errors'=>$validator->errors(), 'msg'=>'validation error', 'status'=>0];
    }
    return ['errors'=>[], 'msg'=>'success', 'status'=>1];

}

function getStatus($status) :string
{
    $str = 'pending';
    if ($status ==1)
        $str = 'accepted';
    else if ($status ==2)
        $str = 'refused';
    return $str;
}
function getStatusCode($status):string
{
    $str = '3';
    if ($status =='accepted')
        $str = '1';
    else if ($status =='refused')
        $str = '2';
    else if ($status =='pending')
        $str = '0';
    return $str;
}
