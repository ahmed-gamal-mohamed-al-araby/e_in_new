<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Services\DocumentService;
use App\Services\TokenSigner;


class DocumentController extends Controller
{
    use ApiResponseTrait;

    private $api;
    private $clientInfo;
    private $login;
    private $documentservices;
    // private $tokensinger;

    public function __construct(DocumentService $documentservice, TokenSigner $tokensinger)
    {
        $this->api = $documentservice->etaApi();
        $this->clientInfo = $documentservice->etaClientSetup();
        $this->login = $documentservice->login();
        $this->documentservices = $documentservice;
        // $this->tokensinger = $tokensinger;
    }

    public function index()
    {
        $documents = DocumentResource::collection(Document::paginate($this->paginateNumber));
        return $this->apiResponse($documents, 200);
        // return 'hell?o from api';
    } // end of index

    public function show($id)
    {
        // $documentItems = [];

        // $document = Document::find($id);

        // $purchasedocument = $document->purchaseOrder;

        // $client = $purchasedocument->businessClient;

        // $company = $document->company;

        // $document_items = $document->items;

        // foreach ($document_items as $index=>$document_item){
        //     $itemData = $document_item->basicItemData;
        //     $itemProduct = $itemData->product;

        //     $item_taxes = $itemData->purchaseOrderTaxes;

        //     $documentTax = $document_item->DocumentTaxes;
        // }

        // if ($document) {
        //     // return response($document, 200);
        //     return $this->apiResponse($document);
        // }
        // // return $this->notFoundResponse();
        // return $this->apiResponse(null ,'we have not this document', 404);
    } // end of show

    public function documentsdetails($id)
    {

        // Event::listen(StatementPrepared::class, function ($event) {
        //     $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        // });

        $document = Document::find($id);
        // $documentToArray = DB::table('documents')->find($id);;


        // Event::listen(StatementPrepared::class, function ($event) {
        //     $event->statement->setFetchMode(PDO::FETCH_CLASS);
        // });
        // $document =  DB::table('documents')->find($id); // array of arrays instead of objects
        // dump($document);
        if ($document) {

            $result = json_encode($this->apiResponse(new DocumentResource($document))->original, JSON_UNESCAPED_UNICODE);

            // Create a file .txt for invoice
            $myfiletotext = fopen("./documents/".$document->document_number."-"."invoice.txt", "w") or die("Unable to open file!");
            $myfiletojson = fopen("./documents/".$document->document_number."-"."invoice.json", "w") or die("Unable to open file!");
            fwrite($myfiletotext, $result);
            fwrite($myfiletojson, $result);
            fclose($myfiletotext);
            fclose($myfiletojson);
            // echo $result;
            return $this->apiResponse(new DocumentResource($document));
            // return json_decode(json_encode(new DocumentResource($document)));

        }
        return $this->apiResponse(null ,'we have not this document', 404);

    } // end of documentsdetails

    public function etaLogin()
    {
        // Get Api login api

        $loginresponse = $this->login;
        // $api = $this->api->loginUrl;
        dump($loginresponse);

        // dd('aaaa');

        $token = ($loginresponse['response'])->access_token;

        // Get Client Info
        // $clientInfo = $this->clientInfo;

        dd($token);
        // echo $token;
    }

    public function etaSubmitDocument($id)
    {
        // Get Document by Id
        $document = Document::find($id);

        // Convert Invoice to Json

        // $invoice = json_encode($this->apiResponse(new DocumentResource($document))->original, JSON_UNESCAPED_UNICODE);
        $invoice = $this->getSignature($id);
        // dump($invoice);

        $document_number = $document->document_number;
        // dump($document_number);

        $submision = $this->documentservices->etaSubmitDocument($invoice, $document_number);

        // dd($submision);

    } // Submit Document By Id

    public function getDocumentStatus()
    {
        $externalId = "SV28YHEGRBJGWJWGY9ZRFR7F10";

        $status = $this->documentservices->getDocumentStatus($externalId);

        dd($status);

        $transformation = json_decode(json_encode($status));

    } // end of Get Document Status

    public function getDocumentBySubmissionId()
    {
        $submissionId = "E4ZEDGV06S4VYDF3Q7G1RJ7F10";

        $status = $this->documentservices->getDocumentBySubmissionId($submissionId);

        dd($status);
        $transformation = json_decode(json_encode($status));

    } // End Of Get Document Status

    public function getSignature($id)
    {
        $document = Document::find($id);

        $result = $this->apiResponse(new DocumentResource($document))->original;
        $transformation = json_decode(json_encode($result))->documents[0];
        // dump(json_decode(json_encode($result))->documents);

        $responseAsJson = json_encode($transformation, JSON_UNESCAPED_UNICODE);
        // dump($responseAsJson);

        $myfiletojson = fopen("./documents/SourceDocumentJson.json", "w") or die("Unable to open file!");
        fwrite($myfiletojson, $responseAsJson);
        fclose($myfiletojson);

        // dd(shell_exec("C:/laragon/www/eecinvoice/EECInvoicingSigner.exe"));
        shell_exec("C:/laragon/www/eecinvoice/EECInvoicingSigner.exe");
        $path ="./documents/FullSignedDocument.json"; // ie: /var/www/laravel/app/storage/json/filename.json
        $fullSignedDocument = json_decode(file_get_contents($path), true);
        // dump($fullSignedDocument);
        return $fullSignedDocument;
    } // End Of Get Signature

} // end of controller
