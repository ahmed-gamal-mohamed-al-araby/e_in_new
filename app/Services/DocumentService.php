<?php

namespace App\Services;

use stdClass;

class DocumentService{

    // public static function etaApi() {
    //     $api=array(
    //         "apiBaseUrl"=>"https://api.preprod.invoicing.eta.gov.eg",
    //         "idSrvBaseUrl"=>"https://id.preprod.eta.gov.eg"
    //         );
    //     $ETA=new stdClass();
    //     $ETA->loginUrl=$api['idSrvBaseUrl']."/connect/token";
    //     $ETA->SubmitDocument=$api['apiBaseUrl']."/api/v1/documentsubmissions";
    //     $ETA->getDocument= $api['apiBaseUrl']."/api/v1/documents";
    //     $ETA->getDocumentSubmission= $api['apiBaseUrl']."/api/v1/documentSubmissions";

    //     return $ETA;

    // }  // ETA Preproduction Urls API

    public static function etaApi() {
        $api=array(
            "apiBaseUrl"=>"https://api.invoicing.eta.gov.eg",
            "idSrvBaseUrl"=>"https://id.eta.gov.eg"
            );
      $ETA=new stdClass();
      $ETA->loginUrl=$api['idSrvBaseUrl']."/connect/token";
      $ETA->SubmitDocument=$api['apiBaseUrl']."/api/v1/documentsubmissions";
      $ETA->getDocument= $api['apiBaseUrl']."/api/v1/documents";
      $ETA->getDocumentSubmission= $api['apiBaseUrl']."/api/v1/documentSubmissions";

      return $ETA;

    }  // ETA Production Urls API

    // public static function etaClientSetup()
    // {
    //     // Credintials Array
    //     $credintials= array(
    //         "grant_type"=>"client_credentials",
    //         "client_id"=>"07fb9dad-a3e7-4ad2-8cbe-bc171a0827f4",
    //         "client_secret"=>"d8e9128c-f7ae-4699-a9fb-df277b904a18",
    //         "scope"=>"InvoicingAPI",
    //     );
    //     return  http_build_query($credintials, '', '&');

    // } // ETA Preproduction Client Setup

    public static function etaClientSetup()
    {
        // Credintials Array
        $credintials= array(
            "grant_type"=>"client_credentials",
            "client_id"=>"5036c447-6791-4647-8577-f17011635f33",
            "client_secret"=>"1460e541-2678-418e-93b1-380e377cc811",
            "scope"=>"InvoicingAPI",
        );
        return  http_build_query($credintials, '', '&');

    } // ETA Production Client Setup


    public static function login()
    {
        // Get Api Login Api
        $api = DocumentService::etaApi()->loginUrl;

        // dump($api);

        // Get Client Info
        $clientInfo = DocumentService::etaClientSetup();

        // dump($clientInfo);

        // dump($clientInfo);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $clientInfo,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));
        $response = curl_exec($curl);
        // dump($response);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // dump($statusCode);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }

        // return json_decode(json_encode($response));

    } // Get Tokent Function

    public function etaSubmitDocument($invoice, $document_number){

        $invalidSteps = [];

        // Login Function
        $loginresponse = DocumentService::login();

        // Get Api Url for Submission
        $api = DocumentService::etaApi()->SubmitDocument;

        //  Check For Token

        // dump($loginresponse['status']);
        // dd($loginresponse);
        if ($loginresponse['status'] == 200) {
            $token = ($loginresponse['response'])->access_token;
        } else {
            $temp['connection_error'] = "Connection Error, Please try again";
            array_push($invalidSteps, $temp);

            return redirect()->back()->with('messages', $invalidSteps);
        }

        // dump($document_number);

        $curl = curl_init();
        $fp = fopen("./documents/submit.txt", 'w');
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_STDERR, $fp);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $invoice,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer ".$token.""
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }
    } // End of ETA Submission Document

    public function getDocumentStatus($externalId)
    {
        // Get Document Status API
        $api = DocumentService::etaApi()->getDocument;

        // Login Function
        $loginresponse = DocumentService::login();

        // get token
        $token = ($loginresponse['response'])->access_token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api.'/'.$externalId.'/raw',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer ".$token.""
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }

    } // end of Get Document Status by uuid

    public function getDocumentDetails($externalId)
    {
        $invalidSteps = [];
        // Get Document Status API
        $api = DocumentService::etaApi()->getDocument;

        // Login Function
        $loginresponse = DocumentService::login();

        // get token
        // $token = ($loginresponse['response'])->access_token;
        if ($loginresponse['status'] == 200) {
            $token = ($loginresponse['response'])->access_token;
        } else {
            $temp['connection_error'] = "Connection Error, Please try again";
            array_push($invalidSteps, $temp);

            return redirect()->back()->with('messages', $invalidSteps);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api.'/'.$externalId.'/details',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer ".$token.""
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }

    } // end of Get Document detail by uuid

    // $pageNumber, $pageSize
    public function getRecentDocument($pageNumber, $pageSize)
    {
        // Get Document Status API
        $api = DocumentService::etaApi()->getDocument;

        // Login Function
        $loginresponse = DocumentService::login();

        // get token
        // $token = ($loginresponse['response'])->access_token;
        if ($loginresponse['status'] == 200) {
            $token = ($loginresponse['response'])->access_token;
        } else {
            $temp['connection_error'] = "Connection Error, Please try again";
            array_push($invalidSteps, $temp);

            return redirect()->back()->with('messages', $invalidSteps);
        }

        $curl = curl_init();

        // ?pageNo='.$pageNumber.'&pageSize='.$pageSize
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api.'/recent?pageNo='.$pageNumber.'&pageSize='.$pageSize,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer ".$token.""
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }

    } // end of Get recent Documents by uuid

    public function getDocumentPrintout($uuid)
    {
        // Get Document Status API
        $api = DocumentService::etaApi()->getDocument;

        // Login Function
        $loginresponse = DocumentService::login();

        // get token
        $token = ($loginresponse['response'])->access_token;

        $curl = curl_init();

        // ?pageNo='.$pageNumber.'&pageSize='.$pageSize
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api.'/'.$uuid.'/pdf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                // 'Content-Type: application/json',
                "Authorization: Bearer ".$token.""
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }

    } // end of Get Document Printout by uuid

    public function cancelOrRejectDocument($uuid, $status)
    {
        // Get Document Status API
        $api = DocumentService::etaApi()->getDocument;

        // Login Function
        $loginresponse = DocumentService::login();

        // get token
        $token = ($loginresponse['response'])->access_token;

        $curl = curl_init();

        $postField = array("status" => $status, "reason" => "some reason for cancelled document");

        $postFieldToJson = json_encode($postField);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api.'/state'.'/'.$uuid.'/state',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $postFieldToJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer ".$token."",
            ),
          ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }

    } // end of Get Document Printout by uuid

    public function getDocumentBySubmissionId($submissionId)
    {

        // Get Document Status API
        $api = DocumentService::etaApi()->getDocumentSubmission;

        // Login Function
        $loginresponse = DocumentService::login();

        // get token
        $token = ($loginresponse['response'])->access_token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api.'/'.$submissionId.'?PageSize=1',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER=> false,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer ".$token.""
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            // echo $response;
            return array('response'=>json_decode($response),'status'=>$statusCode);
        }
    } // get documets submitted by submission id

} // End of Document Services Class

