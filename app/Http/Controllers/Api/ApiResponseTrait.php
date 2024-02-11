<?php


namespace App\Http\Controllers\Api;


trait ApiResponseTrait
{

    public $paginateNumber = 10;

    public function apiResponse($data = null, $error = null, $code = 200)
    {
        $array = [
            'documents' => $data,
        ];

        return response($array, $code);
        // return $array;
    } // API Response

    public function successCode()
    {
        return [
            200, 201, 202
        ];
    } // End of Status Code

} // End Of ApiResponseTrait
