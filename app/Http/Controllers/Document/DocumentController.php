<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Api\ApiResponseTrait;
use App\Services\DocumentService;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Item\ItemController;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentResource2;
use App\Http\Resources\RecentDocumentResource;
use App\Imports\CreateSpecialImport;
use App\Models\BusinessClient;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\DocumentTax;
use App\Models\ForeignerClient;
use App\Models\Notification;
use App\Models\PersonClient;
use App\Models\PurchaseOrder;
use \Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Jobs\GetDocumentStatus;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{

    use ApiResponseTrait;

    public function __construct(DocumentService $documentservice)
    {
        $this->documentservices = $documentservice;
    }

    public function index()
    {

        $sent = false;
        $archive = false;
        $waitingApprove = false;
        $documents = Document::where('submit_status', false)->where('archive', false)->where('approved', 1)->where('document_number', "!=", null)->get();

        // Get total amount of document
        $totalDocument = 0;
        foreach ($documents as $document) {
            $totalDocument += $this->documentTotalwithoutTaxes($document);
            $totalDocument -= $document->extra_invoice_discount;
        }

        return view('pages.documents.index', compact('sent', 'archive', 'waitingApprove', 'documents', 'totalDocument'));
    } // End of index
    public function index_document_request()
    {

        $sent = false;
        $archive = false;
        $waitingApprove = false;
        $documents = Document::where('submit_status', false)->where('archive', false)->where('approved', 1)->where('document_number', null)->get();

        // Get total amount of document
        $totalDocument = 0;
        foreach ($documents as $document) {
            $totalDocument += $this->documentTotalwithoutTaxes($document);
            $totalDocument -= $document->extra_invoice_discount;
        }

        return view('pages.documents.index', compact('sent', 'archive', 'waitingApprove', 'documents', 'totalDocument'));
    } // End of index

    public function indexOFSentDocument()
    {
        $sent = true;
        $archive = false;
        $waitingApprove = false;
        $documents = Document::where('submit_status', true)->orderBy('date', 'DESC')->get();

        // // Get total amount of document
        // $totalDocument = 0;
        // foreach ($documents as $document){
        //     foreach ($document->items as $item){
        //         $totalDocument += $item->total_amount;
        //     }
        //     $totalDocument -= $document->extra_invoice_discount;
        // }

        return view('pages.documents.index', compact('sent', 'archive', 'waitingApprove', 'documents'));
    } // End of index of set document

    public function indexOFarchiveDocument()
    {
        $sent = false;
        $archive = true;
        $waitingApprove = false;
        $documents = Document::where('archive', true)->orderBy('date', 'DESC')->get();

        // Get total amount of document
        $totalDocument = 0;
        foreach ($documents as $document) {
            $totalDocument += $this->documentTotalwithoutTaxes($document);
            $totalDocument -= $document->extra_invoice_discount;
        }

        return view('pages.documents.index', compact('sent', 'archive', 'waitingApprove', 'documents', 'totalDocument'));
    } // End of index of archive Document

    public function indexOWaitingApproveDocument()
    {
        $sent = false;
        $archive = false;
        $waitingApprove = true;
        $documents = Document::where('approved', 0)->get();

        // Get total amount of document
        $totalDocument = 0;
        foreach ($documents as $document) {
            $totalDocument += $this->documentTotalwithoutTaxes($document);
            $totalDocument -= $document->extra_invoice_discount;
        }

        return view('pages.documents.index', compact('sent', 'archive', 'waitingApprove', 'documents', 'totalDocument'));
    } // End of index of Waiting Approve Document


    public function newDocument()
    {
        $company = auth()->user()->company;

        return view('pages.documents.create', compact('company'));
    } //End of new document
    public function newDocument2()
    {
        $company = auth()->user()->company;

        $businessClients = BusinessClient::all();
        return view('pages.documents.create2', compact('company', 'businessClients'));
    } //End of new document

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        ItemController::set_document_id('DocumentId');

        ItemController::store('current item');
        ItemController::store('next item');
    } // End of create

    public function store(Request $request)
    {
        // return $request;
        $invoiceItems = collect();
        $basicInvoiceData = collect();

        // Prepare collection for Invoice Data
        $tem = array_map(function ($v) {
            return [$v['name'] => $v['value']];
        }, $request->basicData);

        foreach ($tem as $mainKey => $object) {
            foreach ($object as $key => $value) {
                $basicInvoiceData->put($key, $value);
            }
        }

        $basicInvoiceData['purchase_order_id'] = PurchaseOrder::where('purchase_order_reference', $basicInvoiceData['purchase_order_id'])->pluck('id')[0]; // Get purchase order if from purchase order reference

        // Here create invoice record using $basicInvoiceData
        $basicInvoiceData = $basicInvoiceData->toArray();

        try {
            unset($basicInvoiceData['client_type']);
            unset($basicInvoiceData['client_id']);
            unset($basicInvoiceData['items_counter']);

            $document = Document::create($basicInvoiceData);
            // $document->update([
            //     'approved'=>1,
            // ]);
            $items = $request->items;
            foreach ($items as $key => $item) {
                unset($item['product_code']);
                unset($item['product_name']);
                unset($item['currency']);
                unset($item['unit']);
                unset($item['sales_amount']);
                $items[$key] = $item;
            }
            $invoiceItems->put('items', $items);

            ItemController::set_document_id($document->id);
            ItemController::store($invoiceItems);

            $response = array(
                'status' => 'success',
            );

            if (!isset($basicInvoiceData['document_number'])) {
                $response = array(
                    'status' => 'success1',
                );
            }

            // Notification part
            $url = route('show_document_approve', $document->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'documents',
                'record_id' => $document->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ مستند ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$document->document_number</a>",
            ]);

            DB::commit();

            return response()->json($response);
            Toastr::success(trans('site.document_success_add'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            dump($e->getMessage());
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
        }
    } // End of Store

    public function show($id)
    {
        $document = Document::findOrFail($id);

        $notification_id = request()->query('n_id');
        if ($notification_id) {
            $notification = Notification::find($notification_id);
            if ($notification->view_status == 0 && $notification->user_id != auth()->user()->id && $notification->type == 'n') {
                app('App\Http\Controllers\Notification\NotificationController')->changeViewStatus($notification_id);
            }
        }
        return view('pages.documents.show', compact('document'));
    } // End of show

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $company = $document->company;
        $purchaseOrder = $document->purchaseOrder;
        $foreignerClients = [];
        $client = null;
        if ($purchaseOrder->client_type == 'b')
            $client = BusinessClient::find($purchaseOrder->client_id);
        else if ($purchaseOrder->client_type == 'p')
            $client = PersonClient::find($purchaseOrder->client_id);
        else if ($purchaseOrder->client_type == 'f') {
            $foreignerClients = ForeignerClient::select('id', 'company_name')->get();
            $client = ForeignerClient::find($purchaseOrder->client_id);
        }

        $items = [];

        $itemCollection = collect([
            'id' => 0,
            'item_id' => 0,
            'product_code' => 0,
            'product_name' => '',
            'description' => '',
            'quantity' => 0,
            'currency' => 0,
            'rate' => 0,
            'unit' => '',
            'item_price' => 0,
            'sales_amount' => 0,
            'discount_items_rate' => 0,
            'discount_items_number' => 0,
            'taxable_fees' => 0,
            'differ_value' => 0,
            'items_discount' => 0,
            'net_total' => 0,
            'total_amount' => 0,
            'tax_items' => [],
            'temp_tax_items' => [],
        ]);

        $itemTaxCollection = collect([
            'tax_type' => '',
            'subtype' => '',
            'tax_rate' => 0,
            'row_total_tax' => 0,
        ]);

        $itemTaxArray = [];

        $itemTempTaxCollection = collect([
            'purchase_order_tax_id' => 0,
            'amount_tax' => 0,
        ]);

        $itemTempTaxArray = [];

        foreach ($document->items as $item) {
            $itemCollection->put('id', $item->id);
            $itemCollection->put('item_id', $item->basicItemData->id);
            $itemCollection->put('product_code', $item->basicItemData->product->product_code);
            $itemCollection->put('product_name', $item->basicItemData->product->product_name);
            $itemCollection->put('description', $item->description);
            $itemCollection->put('quantity', $item->quantity);
            $itemCollection->put('currency', $item->basicItemData->currency);
            $itemCollection->put('rate', $item->rate);
            $itemCollection->put('unit', $item->basicItemData->unit);
            $itemCollection->put('item_price', $item->item_price);
            $itemCollection->put('sales_amount', $item->quantity * $item->item_price);
            $itemCollection->put('discount_items_rate', $item->discount_item_rate);
            $itemCollection->put('discount_items_number', $item->discount_item_amount);
            $itemCollection->put('taxable_fees', $item->taxable_fees);
            $itemCollection->put('differ_value', $item->value_difference);
            $itemCollection->put('items_discount', $item->basicItemData->items_discount);
            $itemCollection->put('net_total', $item->net_total);
            $itemCollection->put('total_amount', $item->total_amount);

            foreach ($item->DocumentTaxes as $tax) {
                $purchaseOrderTax = $tax->purchaseOrderTax;

                // Purchase Order Tax
                $itemTaxCollection->put('tax_type', $purchaseOrderTax->tax_type);
                $itemTaxCollection->put('subtype', $purchaseOrderTax->subtype);
                $itemTaxCollection->put('tax_rate', $purchaseOrderTax->tax_rate);
                $itemTaxCollection->put('row_total_tax', $purchaseOrderTax->amount_tax);

                // Document Tax
                $itemTempTaxCollection->put('purchase_order_tax_id', $tax->purchase_order_tax_id);
                $itemTempTaxCollection->put('amount_tax', $tax->amount_tax);

                array_push($itemTaxArray, $itemTaxCollection->toArray());
                array_push($itemTempTaxArray, $itemTempTaxCollection->toArray());
            }
            $itemCollection->put('tax_items', $itemTaxArray);
            $itemCollection->put('temp_tax_items', $itemTempTaxArray);
            // Clear array
            unset($itemTaxArray);
            $itemTaxArray = array();
            // Clear array
            unset($itemTempTaxArray);
            $itemTempTaxArray = array();
            array_push($items, clone $itemCollection);
        }

        $_items = json_encode($items);
        return view('pages.documents.edit', compact('document', 'company', 'purchaseOrder', 'client', 'foreignerClients', 'items', '_items'));
    } // End of Edit

    public function update(Request $request, $id)
    {
        $basicInvoiceData = collect();

        // Prepare collection for Invoice Data
        $tem = array_map(function ($v) {
            return [$v['name'] => $v['value']];
        }, $request->basicData);

        foreach ($tem as $mainKey => $object) {
            foreach ($object as $key => $value) {
                $basicInvoiceData->put($key, $value);
            }
        }

        try {
            $document = Document::find($id);

            if (isset($basicInvoiceData['document_number'])) {
                $document->update([
                    'date' => $basicInvoiceData['date'],
                    'document_number' => $basicInvoiceData['document_number'],
                    'extra_invoice_discount' => $basicInvoiceData['extra_invoice_discount'],
                ]);
            } else {
                $document->update([
                    'date' => $basicInvoiceData['date'],
                    'extra_invoice_discount' => $basicInvoiceData['extra_invoice_discount'],
                ]);
            }



            $oldItemsID = []; // ids of items before edit
            $itemsID = []; // ids of items sent by edit
            $oldItems = $document->items;

            for ($i = 0; $i < count($oldItems); $i++) {
                array_push($oldItemsID, $oldItems[$i]->id);
            }

            for ($i = 0; $i < count($request->items); $i++) {
                $id = $request->items[$i]['id'];
                if ($id)
                    array_push($itemsID, $id);
            }

            $deletedIDs = array_diff($oldItemsID, $itemsID);

            // delete deleted document items
            DocumentItem::whereIn('id', $deletedIDs)->delete();


            for ($i = 0; $i < count($request->items); $i++) {
                $id = $request->items[$i]['id'];
                if ($id) {  // update
                    DocumentItem::where('id', $id)->update([
                        'description' => $request->items[$i]['description'],
                        'quantity' => $request->items[$i]['quantity'],
                        'rate' => $request->items[$i]['rate'],
                        'item_price' => $request->items[$i]['item_price'],
                        'rate' => $request->items[$i]['rate'],
                        'discount_item_rate' => $request->items[$i]['discount_items_rate'] ?? 0,
                        'discount_item_amount' => $request->items[$i]['discount_items_number'] ?? 0,
                        'taxable_fees' => $request->items[$i]['taxable_fees'] ?? 0,
                        'value_difference' => $request->items[$i]['differ_value'] ?? 0,
                        'net_total' => $request->items[$i]['net_total'],
                        'total_amount' => $request->items[$i]['total_amount'],
                    ]);
                    $item = DocumentItem::find($id);
                    $item->DocumentTaxes()->delete();
                } else {
                    $item = DocumentItem::create([
                        'document_id' => $document->id,
                        'item_id' => $request->items[$i]['item_id'],
                        'description' => $request->items[$i]['description'],
                        'quantity' => $request->items[$i]['quantity'],
                        'item_price' => $request->items[$i]['item_price'],
                        'rate' => $request->items[$i]['rate'],
                        'discount_item_rate' => $request->items[$i]['discount_items_rate'] ?? 0,
                        'discount_item_amount' => $request->items[$i]['discount_items_number'] ?? 0,
                        'taxable_fees' => $request->items[$i]['taxable_fees'] ?? 0,
                        'value_difference' => $request->items[$i]['differ_value'] ?? 0,
                        'net_total' => $request->items[$i]['net_total'],
                        'total_amount' => $request->items[$i]['total_amount'],
                    ]);
                }
                foreach ($request->items[$i]['temp_tax_items'] as $taxIndex => $tax) {
                    $tax['document_item_id'] = $item->id;
                    DocumentTax::create($tax);
                }
            }
            $response = array(
                'status' => 'success',
            );

            // Notification part
            $url = route('show_document_approve', $document->id);

            if (isset($basicInvoiceData['document_number'])) {
                $document->update([
                    'approved' => 0,
                ]);
            } else {
                $document->update([
                    'approved' => 1,
                ]);
                $response = array(
                    'status' => 'success1',
                );
            }



            Notification::where('table_name', 'documents')->where('record_id', $document->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'documents',
                'record_id' => $document->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بتعديل وثيقة ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$document->document_number</a>",
            ]);

            DB::commit();

            return response()->json($response);
            Toastr::success(trans('site.document_success_add'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
        }
    } // End of update

    public function submitToAPI(Request $request)
    {
        $acceptedDocuments = [];
        // Check for External ID & submission_id & log_id
        // & hash_key & documentStatus & dateTimeReceived & signatureValue
        $documents = Document::whereIn('id', [$request->document_id])->get();
        // Convert Invoices to Json
        $invoices = $this->getSignatureForMultiDocuments([$request->document_id]);
        $signatureValue = json_decode($invoices)->documents[0]->signatures[0]->value;

        $document_number = $documents->first()->document_number;

        $submision = $this->documentservices->etaSubmitDocument($invoices, $document_number);

        // dump($submision);
        if ($submision['status'] == 200 || $submision['status'] == 202) {
            // dump($submision['response']->submissionId);
            if ($submision['response']->submissionId == null) {
                $message = $submision['response']->rejectedDocuments[0]->error->details[0]->message;
                Toastr::error($message);
                return redirect()->back();
            } elseif ($submision['response']->submissionId != null && $submision['response']->acceptedDocuments > 0) {
                $submission_id = $submision['response']->submissionId;
                // dump($submission_id);
                $acceptedDocuments = $submision['response']->acceptedDocuments;
                // dump($acceptedDocuments);
                foreach ($acceptedDocuments as $key => $acceptedDocument) {
                    // dump($acceptedDocument);
                    $document = Document::where('document_number', $acceptedDocument->internalId)->first();
                    if (!$document->uuid) {
                        $document->update([
                            'submit_status' => true,
                            'submission_id' => $submission_id,
                            'uuid' => $acceptedDocument->uuid,
                            'log_id' => $acceptedDocument->longId,
                            'hash_key' => $acceptedDocument->hashKey,
                            'document_status' => "Sending",
                            'signature_value' => $signatureValue,
                        ]);
                        // GetDocumentStatus::dispatch($acceptedDocument->uuid)->delay(now()->addSeconds(30));
                    }
                    // else {
                    //     GetDocumentStatus::dispatch($document->uuid)->delay(now()->addSeconds(30));
                    //     // dump($document->uuid . ' Send To Job');
                    // }
                    Toastr::success(trans('site.documents_sent'), trans("site.success"));
                    return redirect()->route("documents.indexOFSentDocument");
                }
            }
        } elseif ($submision['status'] == 400) { // Check For Bad Structure
            $message = $submision['response']->error->details[0]->message;
            Toastr::error($message);
            return redirect()->back();
        } elseif ($submision['status'] == 422) {
            $message = $submision['response']->error;
            Toastr::error($message);
            return redirect()->back();
        }
        // Get Status Of Submitted Document

        // Upadate Status & With Valid

        // $url = route('documents.show', $document->id);

        // Notification::where('table_name', 'documents')->where('record_id', $document->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        // $notification = Notification::create([
        //     'content' => '',
        //     'user_id' => auth()->user()->id,
        //     'table_name' => 'documents',
        //     'record_id' => $document->id,
        // ]);

        // set content
        // $notification->update([
        //     'view_status' => 1,
        //     'content' => auth()->user()->username . ' ارسل مستند' . "<br><a class=\"btn btn-sm btn-success m-1\" target=\"_blank\" href=\"$url\">$document->document_number</a>",
        // ]);

        // return redirect()->route("documents.indexOFSentDocument");
    } // End of Submit One Documents Function
    public function submitToAPI2(Request $request)
    {
        $acceptedDocuments = [];
        // Check for External ID & submission_id & log_id
        // & hash_key & documentStatus & dateTimeReceived & signatureValue
        $documents = Document::whereIn('id', [$request->document_id])->get();
        // Convert Invoices to Json
        $invoices = $this->getSignatureForMultiDocuments2([$request->document_id]);
        $signatureValue = json_decode($invoices)->documents[0]->signatures[0]->value;

        $document_number = $documents->first()->document_number;

        $submision = $this->documentservices->etaSubmitDocument($invoices, $document_number);
        if ($submision['status'] == 200 || $submision['status'] == 202) {
            // dump($submision['response']->submissionId);
            if ($submision['response']->submissionId == null) {
                $message = $submision['response']->rejectedDocuments[0]->error->details[0]->message;
                Toastr::error($message);
                return redirect()->back();
            } elseif ($submision['response']->submissionId != null && $submision['response']->acceptedDocuments > 0) {
                $submission_id = $submision['response']->submissionId;
                // dump($submission_id);
                $acceptedDocuments = $submision['response']->acceptedDocuments;
                // dump($acceptedDocuments);
                foreach ($acceptedDocuments as $key => $acceptedDocument) {
                    // dump($acceptedDocument);
                    $document = Document::where('document_number', $acceptedDocument->internalId)->first();
                    if (!$document->uuid) {
                        $document->update([
                            'submit_status' => true,
                            'submission_id' => $submission_id,
                            'uuid' => $acceptedDocument->uuid,
                            'log_id' => $acceptedDocument->longId,
                            'hash_key' => $acceptedDocument->hashKey,
                            'document_status' => "Sending",
                            'signature_value' => $signatureValue,
                        ]);
                        // GetDocumentStatus::dispatch($acceptedDocument->uuid)->delay(now()->addSeconds(30));
                    }
                    // else {
                    //     GetDocumentStatus::dispatch($document->uuid)->delay(now()->addSeconds(30));
                    //     // dump($document->uuid . ' Send To Job');
                    // }
                    Toastr::success(trans('site.documents_sent'), trans("site.success"));
                    return redirect()->route("documents.indexOFSentDocument");
                }
            }
        } elseif ($submision['status'] == 400) { // Check For Bad Structure
            $message = $submision['response']->error->details[0]->message;
            Toastr::error($message);
            return redirect()->back();
        } elseif ($submision['status'] == 422) {
            $message = $submision['response']->error;

            Toastr::error($message);
            return redirect()->back();
        }
        // Get Status Of Submitted Document

        // Upadate Status & With Valid

        // $url = route('documents.show', $document->id);

        // Notification::where('table_name', 'documents')->where('record_id', $document->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        // $notification = Notification::create([
        //     'content' => '',
        //     'user_id' => auth()->user()->id,
        //     'table_name' => 'documents',
        //     'record_id' => $document->id,
        // ]);

        // set content
        // $notification->update([
        //     'view_status' => 1,
        //     'content' => auth()->user()->username . ' ارسل مستند' . "<br><a class=\"btn btn-sm btn-success m-1\" target=\"_blank\" href=\"$url\">$document->document_number</a>",
        // ]);

        // return redirect()->route("documents.indexOFSentDocument");
    } // End of Submit One Documents Function

    public function getSignature($id)
    {
        // Get Document By ID
        $document = Document::where('id', $id)->get();
        // Transform Document To Json Response
        $result = DocumentResource::collection($document);

        $resultresource = $this->apiResponse($result)->getContent();
        // dump($resultresource);
        $transformation = json_decode($resultresource);
        // dump($transformation);
        $responseAsJson = json_encode($transformation, JSON_UNESCAPED_UNICODE);
        // dump($responseAsJson);
        // Write document as Json In SourceDocumentJson.json file
        $myfiletojson = fopen("./documents/SourceDocumentJson.json", "w") or die("Unable to open file!");
        fwrite($myfiletojson, $responseAsJson);
        fclose($myfiletojson);

        // Execute EInvoicingSigner.exe App to sign document
        shell_exec("C:/laragon/www/eecinvoicelast/EInvoicingSigner.exe");
        // dd('----');
        // get content of FullSignedDocument.json
        $path = "./documents/FullSignedDocument.json";
        $fullSignedDocument = file_get_contents($path);
        $fullSignedDocumentToArray = json_decode(file_get_contents($path), true);

        // Return Full Signed Document
        return $fullSignedDocument;
    } // End of full Signed Document

    public function submitMultiDocuments(Request $request)
    {
        $acceptedDocuments = [];

        // Check for External ID & submission_id & log_id
        // & hash_key & documentStatus & dateTimeReceived & signatureValue

        $documents = Document::whereIn('id', $request->ids)->get();
        // dump($documents);
        // Convert Invoices to Json
        $invoices = $this->getSignatureForMultiDocuments($request->ids);

        $signatureValues = json_decode($invoices)->documents;

        foreach ($signatureValues as $key => $signatureValue) {
            Document::where('document_number', $signatureValue->internalID)->update([
                'signature_value' => $signatureValue->signatures[0]->value
            ]);
        } // update documents with signature values

        $document_number = $documents->first()->document_number;

        $submision = $this->documentservices->etaSubmitDocument($invoices, $document_number);

        // dump($submision);
        if ($submision['status'] == 200 || $submision['status'] == 202) {
            if ($submision['response']->submissionId == null) {
                $message = $submision['response']->rejectedDocuments[0]->error->details[0]->message;
                Toastr::error($message);
            } elseif ($submision['response']->submissionId != null && $submision['response']->acceptedDocuments > 0) {

                $submission_id = $submision['response']->submissionId;

                $acceptedDocuments = $submision['response']->acceptedDocuments;
                foreach ($acceptedDocuments as $key => $acceptedDocument) {
                    $document = Document::where('document_number', $acceptedDocument->internalId)->first();
                    if (!$document->uuid) {
                        $document->update([
                            'submit_status' => true,
                            'submission_id' => $submission_id,
                            'uuid' => $acceptedDocument->uuid,
                            'log_id' => $acceptedDocument->longId,
                            'hash_key' => $acceptedDocument->hashKey,
                            'document_status' => "Sending",
                            // 'signature_value' => $signatureValue,
                        ]);
                        // GetDocumentStatus::dispatch($acceptedDocument->uuid)->delay(now()->addSeconds(30));
                    }
                    // else {
                    //     GetDocumentStatus::dispatch($document->uuid)->delay(now()->addSeconds(30));
                    //     // dump($document->uuid . ' Send To Job');
                    // }
                    Toastr::success(trans('site.documents_sent'), trans("site.success"));
                }
            }
        } elseif ($submision['status'] == 400) { // Check For Bad Structure
            $message = $submision['response']->error->details[0]->message;
            Toastr::error($message);
        } elseif ($submision['status'] == 422) {
            $message = $submision['response']->error;
            Toastr::error($message);
        }
    } // End of Submit Multi Documents Function

    public function getSignatureForMultiDocuments($ids)
    {

        // Get Documents By ID
        $documents = Document::whereIn('id', $ids)->get();
        // dump($documents);
        $result = DocumentResource::collection($documents);
        // dump($resultresource);
        $resultresource = $this->apiResponse($result)->getContent();
        // dump($resultresource2);
        $transformation = json_decode($resultresource);
        // dump($transformation);
        $responseAsJson = json_encode($transformation, JSON_UNESCAPED_UNICODE);
        // dump($responseAsJson);

        // dd('aaaa');
        // Transform Document To Json Response
        // Write document as Json In SourceDocumentJson.json file
        $myfiletojson = fopen("./documents/SourceDocumentJson.json", "w") or die("Unable to open file!");
        fwrite($myfiletojson, $responseAsJson);
        fclose($myfiletojson);

        // Execute EInvoicingSigner.exe App to sign document
        shell_exec("C:/laragon/www/eecinvoicelast/EInvoicingSigner.exe");

        // dump(shell_exec("C:/laragon/www/EInvoicingSigner/EInvoicingSigner/bin/Debug/netcoreapp3.1/EInvoicingSigner.exe"));
        // get content of FullSignedDocument.json
        $path = "./documents/FullSignedDocument.json";
        // dd('aaa');
        $fullSignedDocument = file_get_contents($path);
        $fullSignedDocumentToArray = json_decode(file_get_contents($path), true);
        // Return Full Signed Document
        // dump($fullSignedDocument);
        return $fullSignedDocument;
    } // End of full Signed Documents

    public function getSignatureForMultiDocuments2($ids)
    {
        // Get Documents By ID
        $documents = Document::whereIn('id', $ids)->get();
        // dump($documents);
        $result = DocumentResource2::collection($documents);
        // dump($resultresource);
        $resultresource = $this->apiResponse($result)->getContent();
        // dump($resultresource2);
        $transformation = json_decode($resultresource);
        // dump($transformation);
        $responseAsJson = json_encode($transformation, JSON_UNESCAPED_UNICODE);
        // dump($responseAsJson);

        // dd('aaaa');
        // Transform Document To Json Response
        // Write document as Json In SourceDocumentJson.json file
        $myfiletojson = fopen("./documents/SourceDocumentJson.json", "w") or die("Unable to open file!");
        fwrite($myfiletojson, $responseAsJson);
        fclose($myfiletojson);


        // Execute EInvoicingSigner.exe App to sign document
        shell_exec("C:/laragon/www/eecinvoicelast/EInvoicingSigner.exe");

        // dump(shell_exec("C:/laragon/www/EInvoicingSigner/EInvoicingSigner/bin/Debug/netcoreapp3.1/EInvoicingSigner.exe"));
        // get content of FullSignedDocument.json
        $path = "./documents/FullSignedDocument.json";
        // dd('aaa');
        $fullSignedDocument = file_get_contents($path);
        $fullSignedDocumentToArray = json_decode(file_get_contents($path), true);
        // Return Full Signed Document
        // dump($fullSignedDocument);
        return $fullSignedDocument;
    } // End of full Signed Documents

    public function getDocumentStatusFromServer($uuid)
    {
        //  Get Document Status
        $status = $this->documentservices->getDocumentStatus($uuid);

        // Return Server Response
        return $status;
    } // end of Get Document Status

    public function getRecentDocuments(Request $request)
    {
        return view('pages.documents.recentDocuments');
    } // get recent documents

    public function getRecentDocumentsFromServer(Request $request)
    {
        // $request->pageNumber, $request->pageSize
        $pageSize = $request->pageSize;
        $currentPage = $request->pageNo ? $request->pageNo : 1;
        $nextPage = $currentPage + 1;
        $prevPage = $currentPage - 1;
        $lastPage = null;
        $InternalIdDocuments = [];
        $invalidSteps = [];

        if ($request->ajax()) {
            $response = $this->documentservices->getRecentDocument($currentPage ?? null, $pageSize ?? null);
            if ($response['status'] == 200) {
                $documents = $response['response']->result;
                $totalPages = $response['response']->metadata->totalPages;
                $totalCount = $response['response']->metadata->totalCount;
                $lastPage = intval(ceil($totalCount / $pageSize));
                $numOfEstimatedPage = ceil($totalCount / $pageSize);
                $paginator = new Paginator($documents, $totalCount, $pageSize, $currentPage);

                // Update DataBase Section
                foreach ($documents as $key => $document) {
                    if ($document->issuerId == "311045022") {
                        array_push($InternalIdDocuments, $document->internalId);
                    }
                }
                $mergedArray = array_unique($InternalIdDocuments);
                $documentsFromDB = Document::whereIn('document_number', $mergedArray)->where(function ($query) {
                    $query->where('document_status', '<>', 'Valid')
                        ->orWhere('document_status', NULL);
                })->get();

                foreach ($documentsFromDB as $key => $documentFromDB) {
                    $matchedDocuments = array_filter($documents, function ($_document, $_key) use ($documentFromDB) {
                        if ($_document->internalId == $documentFromDB->document_number)
                            return $_document;
                    }, ARRAY_FILTER_USE_BOTH);

                    $targetDocument = current($matchedDocuments);
                    $submitStatus = true;
                    $uuid = $targetDocument->uuid;
                    $invalid_steps = null;
                    if ($targetDocument->status == "Invalid") {
                        $submitStatus = false;
                        $uuid = null;
                        $status = $this->documentservices->getDocumentDetails($targetDocument->uuid);
                        if ($status['response']->status == "Invalid") {
                            foreach ($status['response']->validationResults->validationSteps as $key => $step) {
                                if ($step->status == "Invalid") {
                                    $temp['Step_Name'] = $step->stepName;
                                    $temp['Step_Satus'] = $step->status;
                                    $temp['Error_Messsage'] = $step->error->error;
                                    $temp['inner_Error'] = $step->error->innerError[0]->error;
                                    array_push($invalidSteps, $temp);
                                }
                            }
                            $invalid_steps = $invalidSteps;
                        }
                        $documentFromDB->documentinvalidsteps()->updateOrCreate([
                            'document_id' => $documentFromDB->id,
                        ], [
                            'invalid_steps' => $invalid_steps,
                        ]);
                    }
                    if ($targetDocument->dateTimeReceived > $documentFromDB->date_time_received) {

                        Document::where('document_number', $targetDocument->internalId)->update([
                            'submit_status' => $submitStatus,
                            'publicUrl' => $targetDocument->publicUrl,
                            'document_status' => $targetDocument->status,
                            'date_time_received' => date('Y-m-d h:i:s', strtotime($targetDocument->dateTimeReceived)),
                            'uuid' => $uuid,
                            'submission_id' => $targetDocument->submissionUUID,
                            'log_id' => $targetDocument->longId,
                        ]);

                        if ($targetDocument->status == "Valid" && $documentFromDB->documentinvalidsteps) {
                            $documentFromDB->documentinvalidsteps->delete();
                        }
                    }
                }
            }
        }
        return view('pages.documents.recentDocumentsFromServer', compact('documents', 'totalPages', 'totalCount', 'numOfEstimatedPage', 'paginator'));
    } // getRecentDocumentsFromServer

    public function getRecentDocumentsReceived(Request $request)
    {
        return view('pages.documents.recentDocumentsReceived');
    } // get recent documents

    public function getRecentDocumentsReceivedFromServer(Request $request)
    {
        // $request->pageNumber, $request->pageSize
        $pageSize = $request->pageSize;
        $currentPage = $request->pageNo ? $request->pageNo : 1;
        $nextPage = $currentPage + 1;
        $prevPage = $currentPage - 1;
        $lastPage = null;

        if ($request->ajax()) {
            dump($request->all());
            $response = $this->documentservices->getRecentDocument($currentPage ?? null, $pageSize ?? null);
            if ($response['status'] == 200) {
                $documents = $response['response']->result;
                // $documents->;
                // $collection = collect($documents);
                $totalPages = $response['response']->metadata->totalPages;
                // dump($totalPages);
                $totalCount = $response['response']->metadata->totalCount;
                $lastPage = intval(ceil($totalCount / $pageSize));
                $numOfEstimatedPage = ceil($totalCount / $pageSize);

                // dump($currentPage);

                $paginator = new Paginator($documents, $totalCount, $pageSize, $currentPage, [
                    // 'path'  => route('documents.getRecentDocumentsFromServer', ['pageNo'=>$currentPage, 'pageSize'=>$pageSize]),
                    // 'query' => $request->query(),
                ]);
                // dump($paginator);
            }
        }

        return view('pages.documents.recentDocumentsReceivedFromServer', compact('documents', 'totalPages', 'totalCount', 'numOfEstimatedPage', 'paginator'));
    } // getRecentDocumentsFromServer

    public function cancelOrRejectDocument(Request $request)
    {
        if ($request->ajax()) {
            $uuid = $request->uuid;
            $status = $request->status;
            $response = $this->documentservices->cancelOrRejectDocument($uuid, $status);
            if ($response['status'] == 400) {
                $errorCode = $response['response']->error->code;
                // dump($errorCode);
                $errorDetails = $response['response']->error->details[0]->message;
                // dump($errorDetails);
                Toastr::error($errorDetails);
            } elseif ($response['status'] == 200) {
                if ($status == 'cancelled') {
                    $message = trans('site.document_cancelled_successfully');
                } elseif ($status == 'rejected') {
                    $message = trans('site.document_rejected_successfully');
                }
                Toastr::success($message);
            }
        }
    } // Cancel Or Reject Function

    public function test(Request $request)
    {
        // / Get Documents By ID
        $documents = Document::whereIn('id', [369])->get();
        // dump($documents);
        $result = DocumentResource::collection($documents);
        // dump($result);
        $resultresource = $this->apiResponse($result)->getContent();
        dump($resultresource);
        $transformation = json_decode($resultresource);
        // dump($transformation);
        // ob_start('ob_gzhandler');
        $responseAsJson = json_encode($transformation, JSON_UNESCAPED_UNICODE);
        dump($responseAsJson);
        // $size = strlen(serialize($responseAsJson));
        # `strlen` returns number of chars in a string. Each char is 1 byte.
        # So to get size in bits, multiply `strlen` results by 8. Divide by
        # 1024 for KB or KiB. Divide by 1000 for kB.
        // print($size * 8 / 1000);
        // dd($size /1000);

        // dd('aaaa');
        // Transform Document To Json Response
        // Write document as Json In SourceDocumentJson.json file
        $myfiletojson = fopen("./documents/SourceDocumentJson.json", "w") or die("Unable to open file!");
        fwrite($myfiletojson, $responseAsJson);
        fclose($myfiletojson);


        // Execute EInvoicingSigner.exe App to sign document
        shell_exec("C:/laragon/www/eecinvoicelast/EInvoicingSigner.exe");

        // dump(shell_exec("C:/laragon/www/EInvoicingSigner/EInvoicingSigner/bin/Debug/netcoreapp3.1/EInvoicingSigner.exe"));
        // get content of FullSignedDocument.json
        $path = "./documents/FullSignedDocument.json";
        //  dd('aaa');
        $fullSignedDocument = file_get_contents($path);
        $fullSignedDocumentToArray = json_decode(file_get_contents($path), true);
        // Return Full Signed Document
        //  dump($fullSignedDocument);
        // $size = strlen(serialize($fullSignedDocument));
        # `strlen` returns number of chars in a string. Each char is 1 byte.
        # So to get size in bits, multiply `strlen` results by 8. Divide by
        # 1024 for KB or KiB. Divide by 1000 for kB.
        // print($size * 8 / 1000);
        // dd($size /1024);
        //  dd('aaa');
        return $fullSignedDocument;
        // dump($execution_time2);
    } // for test purpose

    public function destroy(Request $request)
    {
        try {
            $data = Document::findOrFail($request->document_id);
            $deletedDocument = clone $data;

            $data->delete();

            // Notification part
            Notification::where('table_name', 'documents')->where('record_id', $deletedDocument->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'documents',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف وثيقة ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Document number: $deletedDocument->document_number</div>",
            ]);

            Toastr::success(trans('site.document_success_deleted'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans('site.document_delete_error'), trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
        }
        return redirect()->route('documents.index');
    } // end of destroy

    public function check_document_number(Request $request)
    {
        if ($request->id)
            return Document::where('document_number', $request->document_number)->where('id', '<>', $request->id)->count();
        else
            return Document::where('document_number', $request->document_number)->count();
    }

    public function print(Request $request, $id)
    {
        $tafqeet = $request->tafqeet ?? false;
        $type = $request->print_type;
        $document = Document::find($id);
        $columnsName = [];
        $firstItemDescription = $document->items[0]->description;
        $tempDescription = [];
        if (strpos($document->items[0]->description, ':') != false) { // this mean description is formatted
            $tempDescription = explode('|', $firstItemDescription);
            foreach ($tempDescription as $row) {
                array_push($columnsName, explode(':', $row)[0]);
            }
        }

        $currancyCorresponding = [
            'EGP' => 'EGP',
            'EGP_ar' => 'ج.م',
            'USD' => '$',
            'EUR' => '€',
            'SAR' => 'SAR',
            'RUB' => 'RUB',
            'JPY' => '¥',
            'GBP' => '£',
            'CHF' => 'CHF',
            'CAD' => 'CAD',
            'AUD/NZD' => 'AUD/NZD',
            'ZAR' => 'ZAR',
        ];
        if ($document->items[0]->basicItemData->currency == 'EGP' && ($type == 'public' || $type == 'army'))
            $currency = $currancyCorresponding['EGP_ar'];
        else
            $currency = $currancyCorresponding[$document->items[0]->basicItemData->currency];

        $company = $document->company;
        $accounting_and_auditing_director = $request->accounting_and_auditing_director;
        $finance_and_administration_director = $request->finance_and_administration_director;

        $percentage = (float)$request->invoicePercentage;

        if ($type == 'special') {
            return view('pages.documents.print.special_print', compact('document', 'company', 'tafqeet', 'accounting_and_auditing_director', 'finance_and_administration_director', 'columnsName', 'currency'));
        } elseif ($type == 'public') {
            return view('pages.documents.print.public_print', compact('document', 'company', 'tafqeet', 'accounting_and_auditing_director', 'finance_and_administration_director', 'columnsName', 'currency'));
        } elseif ($type == 'army') {
            return view('pages.documents.print.army_print', compact('document', 'company', 'tafqeet', 'accounting_and_auditing_director', 'finance_and_administration_director', 'columnsName', 'currency'));
        } elseif ($type == 'percentage') {
            return view('pages.documents.print.percentage_print', compact('document', 'company', 'tafqeet', 'percentage', 'accounting_and_auditing_director', 'finance_and_administration_director', 'columnsName', 'currency'));
        }
    }

    public function showForApprove(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $notification = Notification::findOrFail($request->query('n_id'));

        return view('pages.documents.approve-show', compact('document', 'notification'));
    }

    public function approved(Request $request, $id)
    {
        // dd($request->all());
        $notification = Notification::findOrFail($request->n_id); // update old notification view status

        $notification->update([
            'view_status' => 1,
            'type' => 'n',
            'content' => $notification->content . '<br><b>' . auth()->user()->username . '</b>' . ' شاهد هذا الاشعار',
        ]);

        // Subtract one second to make order of notification logic
        $updated_at = new DateTime($notification->updated_at);
        $updated_at->modify("-1 second");

        $notification->updated_at = $updated_at;
        $notification->save(['timestamps' => false]);

        $document = Document::findOrFail($id);
        $document->update([
            'approved' => 1,
        ]);

        $url = route('documents.show', $document->id);

        Notification::where('table_name', 'documents')->where('record_id', $document->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'table_name' => 'documents',
            'record_id' => $document->id,
        ]);

        // set content
        $notification->update([
            'view_status' => 1,
            'content' => auth()->user()->username . ' وافق على إنشاء/تعديل مستند' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url\">$document->document_number</a>",
        ]);

        Toastr::success(trans('site.approved_success'), trans('site.success'));
        return redirect()->route('documents.index');
    }

    public function virtualSubmit(Request $request)
    {
        // Here Send To E-Invoice Server
        // Get Document by Id
        $document = Document::findOrFail($request->document_id);
        $document->update([
            'submit_status' => 1,
        ]);

        // Notification part
        $url = route('documents.show', $document->id);

        Notification::where('table_name', 'documents')->where('record_id', $document->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);


        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'n',
            'table_name' => 'documents',
            'record_id' => $document->id,
        ]);

        // set content
        $notification->update([
            'content' => auth()->user()->username . ' قام بإرسال وهمى لوثيقة ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$document->document_number</a>",
        ]);
        return redirect()->route('documents.indexOFSentDocument');
    }

    public function document_archive(Request $request)
    {
        $document = Document::find($request->document_id);

        $document->update([
            'archive' => 1,
        ]);
        Toastr::success(trans('site.document_archive_success'), trans('site.success'));
        return redirect()->route('documents.indexOFarchiveDocument');

        Toastr::error(trans('site.document_delete_error'), trans("site.sorry"));
        return redirect()->back();
    }

    public function document_restore(Request $request)
    {
        $document = Document::find($request->document_id);
        $document->update([
            'archive' => 0,
        ]);

        Toastr::success(trans('site.document_restore_success'), trans('site.success'));
        return redirect()->route('documents.index');
    }

    public function create_special()
    {
        $company = auth()->user()->company;

        return view('pages.documents.create-special', compact('company'));
    } //End of new document

    public function store_special(Request $request)
    {
        // dd($request->items);
        // dd($request->all());
        $invoiceItems = collect();
        $basicInvoiceData = collect();

        // Prepare collection for Invoice Data
        $tem = array_map(function ($v) {
            return [$v['name'] => $v['value']];
        }, $request->basicData);

        foreach ($tem as $mainKey => $object) {
            foreach ($object as $key => $value) {
                $basicInvoiceData->put($key, $value);
            }
        }
        $basicInvoiceData['purchase_order_id'] = PurchaseOrder::where('purchase_order_reference', $basicInvoiceData['purchase_order_id'])->pluck('id')[0]; // Get purchase order if from purchase order reference

        // Here create invoice record using $basicInvoiceData
        $basicInvoiceData = $basicInvoiceData->toArray();

        try {

            $document = Document::create($basicInvoiceData);
            $items = $request->items;
            foreach ($items as $key => $item) {
                unset($item['availableToSubmit']);
                $items[$key] = $item;
            }
            $invoiceItems->put('items', $items);

            ItemController::set_document_id($document->id);
            ItemController::store($invoiceItems);

            $response = array(
                'status' => 'success',
            );

            // Notification part
            $url = route('show_document_approve', $document->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'documents',
                'record_id' => $document->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ مستند ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$document->document_number</a>",
            ]);

            DB::commit();

            return response()->json($response);
            Toastr::success(trans('site.document_success_add'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            dump($e->getMessage());
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
        }
    } // End of Store

    // Document Total without Taxes
    public function documentTotalwithoutTaxes($document)
    {
        $sign = 1;
        if ($document->type == 'C')
            $sign = -1;

        return floatval($document->items()->select(DB::raw('sum(quantity * item_price) as totalWithoutTax'))->first()->totalWithoutTax) * $sign;
    } // End of Document Total without Taxes

    public function importExcelForCreateSpecial(Request $request)
    {

        if (!$request->file) {
            return back()->with('error', 'Can not upload empty file.');
        }
        Excel::import(new CreateSpecialImport, request()->file('file'));

        Toastr::success(trans('site.document_success_add'), trans("site.success"));
        return redirect()->back();
    }

} // End of Controller
