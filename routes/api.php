<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => 'auth:api'], function () {
    // Route::get('/documents', 'DocumentController@index');
    // Route::get('/documents/{id}', 'DocumentController@show');
    // Route::get('/documentsdetails/{id}', 'DocumentController@documentsdetails');
    // Route::get('/structure', 'DocumentController@ETA_API');
    // Route::get('/ETAClientSetup', 'DocumentController@ETAClientSetup');
    // Route::get('/ETALogin', 'DocumentController@ETALogin');
    // Route::get('/logintoeta', 'DocumentController@etaLogin');
    // Route::get('/ETASubmitDocument/{id}', 'DocumentController@ETASubmitDocument')->name('submitDocument');
    // Route::get('/getdocumentstatus', 'DocumentController@getDocumentStatus');
    // Route::get('/getDocumentBySubmissionId', 'DocumentController@getDocumentBySubmissionId');
    // Route::get('/serializetoken', 'DocumentController@serializeToken');
// });
