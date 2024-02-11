<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankRequest;
use App\Models\Bank;
use App\Models\Cheque;
use App\Models\Item;
use App\Models\Deduction;
use App\Models\Document;
use App\Models\Payment;
use App\Models\PaymentDeduction;
use App\Models\BusinessClient;
use App\Models\ForeignerClient;
use App\Models\LettersGuarantee;
use App\Models\PersonClient;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderTax;
use App\Models\WarrantyChecks;
use App\PurchaseOrders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class ReportController extends Controller
{
    public function createvattaxreport(Request $request)
    {
        $date = $request->input('from_date');


        $documents = Document::where('submit_status', true)->when($date, function ($q) use ($request) {
            return $q->whereBetween('date', [$request->from_date, $request->to_date]);
        })->orderBy('date', 'ASC')->get();

        if ($request->input('from_date')) {
            $dateStart = date("Y/m/d", strtotime($request->from_date));
            $dateEnd = date("Y/m/d", strtotime($request->to_date));
        } else {
            $firstDocument = $documents->first();
            $lastDocument = $documents->last();

            $firstDocumentDate = $firstDocument ? $firstDocument->date : '';
            $lastDocumentDate = $lastDocument ? $lastDocument->date : '';

            $dateStart = $firstDocumentDate ? date("Y/m/d", strtotime($firstDocumentDate)) : null;
            $dateEnd = $lastDocumentDate ? date("Y/m/d", strtotime($lastDocumentDate)) : null;
        }

        return view('pages.reports.createvattax', compact('documents', 'dateStart', 'dateEnd'));
    } // End Of Create Vat Tax Report


    public function getDocumentsToVatTaxReport(Request $request)
    {
        $type = 1;
        $tax = 1;
        if (isset($request->subtype)) {
            $type = $request->subtype;
        }
        if (isset($request->tax_rate)) {
            $tax = $request->tax_rate;
        }
        $documents = Document::where('submit_status', true);
        $subtype = [];

        foreach ($documents as $document) {
            foreach ($document->items as $doc_item) {

                if (isset($subtype)) {
                    $subtype = $doc_item->basicItemData->purchaseOrderTaxes->subtype;
                } else
                    echo null;
            }
        }


        if ($request->ajax()) {

            $date = $request->input('fromDate');
            // $subtype1 = $request->input('subtype');


            $documents = Document::where('submit_status', true)->when($date, function ($q) use ($request) {
                return $q->whereBetween('date', [$request->fromDate, $request->toDate]);
            })->orderBy('date', 'ASC')->get();


            if ($date) {
                $dateStart = date("Y/m/d", strtotime($request->fromDate));
                $dateEnd = date("Y/m/d", strtotime($request->toDate));
            } else {
                $firstDocument = $documents->first();
                $lastDocument = $documents->last();

                $firstDocumentDate = $firstDocument ? $firstDocument->date : '';
                $lastDocumentDate = $lastDocument ? $lastDocument->date : '';

                $dateStart = $firstDocumentDate ? date("Y/m/d", strtotime($firstDocumentDate)) : null;
                $dateEnd = $lastDocumentDate ? date("Y/m/d", strtotime($lastDocumentDate)) : null;
            }
        } else {
            $documents = Document::orderBy('date', 'ASC')->get();
            $firstDocument = $documents->first();
            $lastDocument = $documents->last();

            $firstDocumentDate = $firstDocument ? $firstDocument->date : '';
            $lastDocumentDate = $lastDocument ? $lastDocument->date : '';

            $dateStart = $firstDocumentDate ? date("Y/m/d", strtotime($firstDocumentDate)) : null;

            $dateEnd = $lastDocumentDate ? date("Y/m/d", strtotime($lastDocumentDate)) : null;
        }

        return view('pages.reports.getvattaxdocuments', compact('documents', 'dateStart', 'dateEnd', 'tax', 'type'));
    } // Get Documents To Vat Tax Report

    public function createporeport(Request $request)
    {
        return view('pages.reports.createpurchaseorderreport');
    } // End Of Create Purchase Order Report

    public function getClientsFromclientType(Request $request) // get business client
    {
        if ($request->clientType == 'b') {
            return json_encode(json_decode(BusinessClient::where('approved', 1)->get()->pluck('name', 'id')));
            // dump(json_decode(BusinessClient::all()->pluck('name', 'id')));
        } elseif ($request->clientType == 'p') {
            return json_encode(json_decode(PersonClient::where('approved', 1)->get()->pluck('name', 'id')));
        } elseif ($request->clientType == 'f') {

            return json_encode(json_decode(ForeignerClient::where('approved', 1)->get()->pluck('company_name', 'id')));
        }
    } // End Of Get Clients From Client Type

    public function getALLClientsViaClientType(Request $request) // get business client
    {
        if ($request->clientType == 'b') {
            return json_encode(BusinessClient::where('approved', 1)->select('name', 'id', 'tax_id_number as reference')->get());
            // dump(json_decode(BusinessClient::all()->pluck('name', 'id')));
        } elseif ($request->clientType == 'p') {
            return json_encode(PersonClient::where('approved', 1)->select('name', 'id', 'national_id  as reference')->get());
        } elseif ($request->clientType == 'f') {

            return json_encode(ForeignerClient::where('approved', 1)->select('company_name as name', 'id', 'vat_id  as reference')->get());
        }
    } // End Of Get Clients From Client Type

    public function getALLClientsTax(Request $request) // get business client
    {

        if ($request->clientType == 'b') {
            return json_encode(BusinessClient::where('approved', 1)->select('name', 'id', 'tax_id_number')->get());
            // dump(json_decode(BusinessClient::all()->pluck('name', 'id')));
        } elseif ($request->clientType == 'p') {
            return json_encode(PersonClient::where('approved', 1)->select('name', 'id', 'national_id')->get());
        } elseif ($request->clientType == 'f') {

            return json_encode(ForeignerClient::where('approved', 1)->select('company_name as name', 'id', 'vat_id  as reference')->get());
        }
    } // End Of Get Clients From Client Type

    public function getPurchaseOrdersForClient(Request $request)
    {
        // dump($request->all());
        if ($request->clientType == 'b') {
            $client = BusinessClient::where('id', $request->urlInputId)->first()->purchaseOrders()->where('approved', 1)->pluck('purchase_order_reference', 'id');
            return json_encode($client);
        } elseif ($request->clientType == 'p') {
            $client = PersonClient::where('id', $request->urlInputId)->first()->purchaseOrders()->where('approved', 1)->pluck('purchase_order_reference', 'id');
            return json_encode($client);
        } elseif ($request->clientType == 'f') {
            $client = ForeignerClient::where('id', $request->urlInputId)->first()->purchaseOrders()->where('approved', 1)->pluck('purchase_order_reference', 'id');
            return json_encode($client);
        }
    } // End Of Get Purchase Orders For Client

    public function getPurchaseOrderData(Request $request)
    {
        if ($request->ajax()) {
            $purchaseorder = PurchaseOrder::find($request->searchContent);
        } else {
            $purchaseorder = '0';
        }
        // $purchaseOrder = PurchaseOrder::find($request->searchContent);
        return view('pages.reports.getpurchaseorderdata', compact('purchaseorder'));
    } // End Of Get Purchase Orders Date

    public function getDocumentsRelatedTopurchaseOrder(Request $request)
    {
        if ($request->ajax()) {
            $documents = PurchaseOrder::find($request->searchContent)->documents;
        } else {
            $documents = '0';
        }
        return view('pages.reports.getdocumentsrelatedtopo', compact('documents'));
    } // End Of Get Documents Related To purchaseOrder

    public function getDocumentsBelongToPurchaseOrder(Request $request)
    {
        $documents = PurchaseOrder::find($request->purchaseOrder_id)->documents()->where('approved', 1)->pluck('document_number', 'id');
        return json_encode($documents);
    } // End Of Get Documents Belong To PurchaseOrder

    public function getEstimatedPurchaseOrderData(Request $request)
    {
        $totalSumationOfDocuments = 0;
        $totalSumationOfPurchaseOrder = 0;
        $totalEstimatedOfPurchaseOrder = 0;
        $percentage = 20;

        if ($request->ajax()) {
            $purchaseorder = PurchaseOrder::find($request->searchContent);
            foreach ($purchaseorder->items as $item) {
                $totalSumationOfPurchaseOrder += $item->total_amount;
            }
            foreach ($purchaseorder->documents as $key => $document) {

                foreach ($document->items as $index => $item) {
                    if ($document->type == 'C') {
                        if ($document->items[0]->basicItemData->currency != 'EGP') {
                            $totalSumationOfDocuments -= ($item->quantity / ($percentage / 100)) * ($item->item_price) * ($percentage / 100);
                        } else {
                            $totalSumationOfDocuments -= $item->total_amount;
                        }
                    } else {
                        if ($document->items[0]->basicItemData->currency != 'EGP') {
                            $totalSumationOfDocuments += ($item->quantity / ($percentage / 100)) * ($item->item_price) * ($percentage / 100);
                        } else {
                            $totalSumationOfDocuments += $item->total_amount;
                        }
                    }
                }
            }

            $totalEstimatedOfPurchaseOrder = $totalSumationOfPurchaseOrder - $totalSumationOfDocuments;
        } else {
            $purchaseorder = '0';
        }
        return view('pages.reports.getestimatedpo', compact('purchaseorder', 'totalEstimatedOfPurchaseOrder'));
    } // End Of Get Estimated purchaseOrder Data

    // Deduction Report
    public function deductionReportView()
    {
        $deductions = Deduction::all();
        return view('pages.reports.deductionReport', compact('deductions'));
    }

    public function deductionReportData(Request $request)
    {
        $paymentsWithDeduction = collect([]);
        $temp = [
            'client_name' => '',
            'document_number' => '',
            'total_document_with_tax' => '',
            'deduction_value' => '',
            'date' => '',
            'currency' => '',
        ];

        $paymentDeductions = [];
        // dump($request->all());

        $paymentDeductions = PaymentDeduction::where('deduction_id', $request->deductionId)->with('payment');

        $itemTax = PurchaseOrderTax::where('tax_type',4)->get();
        $pos_id = Item::whereIn('id',$itemTax->pluck('item_id'))->pluck('purchase_order_id');

        // If some of filtered data is submitted
        if ($request->fromDate) {
            $request->toDate = $request->toDate ? $request->toDate : date("Y-m-d");
        }

        if ($request->fromDate && $request->toDate) { // filter payments by date
            $paymentDeductions = $paymentDeductions->whereHas('payment', function ($q) use ($request) {
                $q->whereBetween('payment_date', [$request->fromDate, $request->toDate]);
            });
            $documents = Document::with('purchaseOrder')->whereIn('purchase_order_id',$pos_id)->whereBetween('date', [$request->fromDate, $request->toDate])->get();
        } else {
            $documents = Document::with('purchaseOrder')->whereIn('purchase_order_id',$pos_id)->get();
        }

        if ($request->clientType && $request->clientId) { // filter payments by client
            $paymentDeductions = $paymentDeductions->whereHas('payment', function ($q) use ($request) {
                $q->where('client_type', $request->clientType)->where('client_id', $request->clientId);
            });
            $documents = $documents->whereHas('purchaseOrder', function ($q) use ($request) {
                $q->where('client_type', $request->clientType)->where('client_id', $request->clientId);
            })->get();
        }

        $paymentDeductions = $paymentDeductions->get();
        foreach ($documents as $document) {
            $temp['currency'] = 0;
            $temp['document_number'] = 0;
            $temp['total_document_with_tax'] = 0;
            $temp['deduction_value'] = 0;
            if ($document->purchaseOrder->client_type == 'b') {
                $temp['client_name'] = $document->purchaseOrder->businessClient->name;
                $temp['currency'] = $this->getClientCurrency('b', $document->purchaseOrder->client_id);
            } else if ($document->purchaseOrder->client_type == 'f') {
                $temp['client_name'] = $document->purchaseOrder->foreignerClient->company_name;
                $temp['currency'] = $this->getClientCurrency('f', $document->purchaseOrder->client_id);
            } else if ($document->purchaseOrder->client_type == 'p') {
                $temp['client_name'] = $document->purchaseOrder->personClient->name;
                $temp['currency'] = $this->getClientCurrency('p', $document->purchaseOrder->client_id);
            }
            $temp['document_number'] = $document->document_number; // PO PO
            $temp['total_document_with_tax'] += $this->documentTotalwithoutTaxes($document);
            $temp['deduction_value'] += ($temp['total_document_with_tax'] * .01 );
            $paymentsWithDeduction->push($temp);

        }
        // Order result by payment date
        foreach ($paymentDeductions as $paymentDeduction) {
            // client name
            if ($paymentDeduction->payment->client_type == 'b') {
                $temp['client_name'] = $paymentDeduction->payment->businessClient->name;
                $temp['currency'] = $this->getClientCurrency('b', $paymentDeduction->payment->client_id);
            } else if ($paymentDeduction->payment->client_type == 'f') {
                $temp['client_name'] = $paymentDeduction->payment->foreignerClient->company_name;
                $temp['currency'] = $this->getClientCurrency('f', $paymentDeduction->payment->client_id);
            } else if ($paymentDeduction->payment->client_type == 'p') {
                $temp['client_name'] = $paymentDeduction->payment->personClient->name;
                $temp['currency'] = $this->getClientCurrency('p', $paymentDeduction->payment->client_id);
            }

            $temp['document_number'] = $paymentDeduction->payment->table == 'D' ? $paymentDeduction->payment->document->document_number : '_'; // PO PO

            if ($paymentDeduction->payment->table == 'D') {
                $temp['total_document_with_tax'] = $this->documentTotalwithoutTaxes($paymentDeduction->payment->document);
            } else {
                $temp['total_document_with_tax'] = '_';
            }
            $temp['deduction_value'] = floatval($paymentDeduction->value);
            $temp['date'] = $paymentDeduction->payment->payment_date;
            $paymentsWithDeduction->push($temp);
            // array_push($paymentsWithDeduction, );
        }


        $sorted = $paymentsWithDeduction->sortBy('date');

        return json_encode($sorted->values()->all());
    }

    // Deduction Report All
    public function deductionReportAllView()
    {
        $deductions = Deduction::all();
        return view('pages.reports.deductionReportAll', compact('deductions'));
    }

    public function deductionReportAllData(Request $request)
    {
        $paymentsWithDeduction = collect([]);
        $temp = [
            'client_name' => '',
            'document_number' => '',
            'total_document_with_tax' => '',
            'deduction_value' => '',
            'date' => '',
            'currency' => '',
        ];

        $paymentDeductions = [];
        // dump($request->all());

        $paymentDeductions = PaymentDeduction::where('deduction_id', $request->deductionId)->with('payment');


        $itemTax = PurchaseOrderTax::where('tax_type',4)->get();
        $pos_id = Item::whereIn('id',$itemTax->pluck('item_id'))->pluck('purchase_order_id');

        // If some of filtered data is submitted
        if ($request->fromDate) {
            $request->toDate = $request->toDate ? $request->toDate : date("Y-m-d");
        }

        if ($request->fromDate && $request->toDate) { // filter payments by date
            $paymentDeductions = $paymentDeductions->whereHas('payment', function ($q) use ($request) {
                $q->whereBetween('payment_date', [$request->fromDate, $request->toDate]);
            });
            $documents = Document::with('purchaseOrder')->whereIn('purchase_order_id',$pos_id)->whereBetween('date', [$request->fromDate, $request->toDate])->get();;
        } else {
            $documents = Document::with('purchaseOrder')->whereIn('purchase_order_id',$pos_id)->get();;
        }

        if ($request->clientType && $request->clientId) { // filter payments by client
            $paymentDeductions = $paymentDeductions->whereHas('payment', function ($q) use ($request) {
                $q->where('client_type', $request->clientType)->where('client_id', $request->clientId);
            });
            $documents = $documents->whereHas('purchaseOrder', function ($q) use ($request) {
                $q->where('client_type', $request->clientType)->where('client_id', $request->clientId);
            })->get();
        }

        $paymentDeductions = $paymentDeductions->get()->groupBy('payment.client_id');

        foreach ($documents as $document) {
            $temp['currency'] = 0;
            $temp['document_number'] = 0;
            $temp['total_document_with_tax'] = 0;
            $temp['deduction_value'] = 0;
            if ($document->purchaseOrder->client_type == 'b') {
                $temp['client_name'] = $document->purchaseOrder->businessClient->name;
                $temp['currency'] = $this->getClientCurrency('b', $document->purchaseOrder->client_id);
            } else if ($document->purchaseOrder->client_type == 'f') {
                $temp['client_name'] = $document->purchaseOrder->foreignerClient->company_name;
                $temp['currency'] = $this->getClientCurrency('f', $document->purchaseOrder->client_id);
            } else if ($document->purchaseOrder->client_type == 'p') {
                $temp['client_name'] = $document->purchaseOrder->personClient->name;
                $temp['currency'] = $this->getClientCurrency('p', $document->purchaseOrder->client_id);
            }
            $temp['document_number'] = $document->document_number; // PO PO
            $temp['total_document_with_tax'] += $this->documentTotalwithoutTaxes($document);
            $temp['deduction_value'] += ($temp['total_document_with_tax'] * .01 );
            $paymentsWithDeduction->push($temp);

        }

        // return $paymentDeductions;
        // Order result by payment date
        foreach ($paymentDeductions as $paymentDeduction) {
            $temp['currency'] = 0;
            $temp['document_number'] = 0;
            $temp['total_document_with_tax'] = 0;
            $temp['deduction_value'] = 0;

            foreach ($paymentDeduction as $debuction) {
                // client name
                if ($debuction->payment->client_type == 'b') {
                    $temp['client_name'] = $debuction->payment->businessClient->name;
                    $temp['currency'] = $this->getClientCurrency('b', $debuction->payment->client_id);
                } else if ($debuction->payment->client_type == 'f') {
                    $temp['client_name'] = $debuction->payment->foreignerClient->company_name;
                    $temp['currency'] = $this->getClientCurrency('f', $debuction->payment->client_id);
                } else if ($debuction->payment->client_type == 'p') {
                    $temp['client_name'] = $debuction->payment->personClient->name;
                    $temp['currency'] = $this->getClientCurrency('p', $debuction->payment->client_id);
                }
                $temp['document_number'] = $debuction->payment->table == 'D' ? $debuction->payment->document->document_number : 0; // PO PO
                if ($debuction->payment->table == 'D') {
                    $temp['total_document_with_tax'] += $this->documentTotalwithoutTaxes($debuction->payment->document);
                } else {
                    $temp['total_document_with_tax'] = 0;
                }
                $temp['deduction_value'] += floatval($debuction->value);
            }
            // $temp['date'] = $debuction->payment->payment_date;
            if ($temp['total_document_with_tax'] == 0) {
                $temp['total_document_with_tax'] = '_';
            }
            if ($temp['document_number'] == 0) {
                $temp['document_number'] = '_';
            }
            $paymentsWithDeduction->push($temp);
            // array_push($paymentsWithDeduction, );
        }
        // return $paymentsWithDeduction;


        // $sorted = $paymentsWithDeduction->sortBy('date');

        return json_encode($paymentsWithDeduction->values()->all());
    }
    // Deductions Report All
    public function deductionsReportAllView()
    {
        $deductions = Deduction::all();
        return view('pages.reports.deductionsReportAll', compact('deductions'));
    }

    public function deductionsReportAllData(Request $request)
    {
        // return $request;
        $paymentsWithDeduction = collect([]);
        $temp = [
            'name' => '',
            'deductionType' => '',
            'deduction_value_EGP' => '',
            'deduction_value_USD' => '',
            'currency' => '',
        ];

        $paymentDeductions = [];
        // dump($request->all());


        $deductions = Deduction::all();
        // return $paymentDeductions;
        // Order result by payment date

        foreach ($deductions as $deduction) {
            $temp['currency'] = 0;
            $temp['deductionType'] = $deduction->deductionType->name;
            $temp['name'] = $deduction->name;
            $temp['deduction_value_EGP'] = 0;
            $temp['deduction_value_USD'] = 0;
            $paymentDeductions = PaymentDeduction::where('deduction_id', $deduction->id)->with('payment', 'deduction');


            // If some of filtered data is submitted
            if ($request->fromDate) {
                $request->toDate = $request->toDate ? $request->toDate : date("Y-m-d");
            }

            if ($request->fromDate && $request->toDate) { // filter payments by date
                $paymentDeductions = $paymentDeductions->whereHas('payment', function ($q) use ($request) {
                    $q->whereBetween('payment_date', [$request->fromDate, $request->toDate]);
                });
            }

            if ($request->clientType && $request->clientId) { // filter payments by client
                $paymentDeductions = $paymentDeductions->whereHas('payment', function ($q) use ($request) {
                    $q->where('client_type', $request->clientType)->where('client_id', $request->clientId);
                });
            }

            $paymentDeductions = $paymentDeductions->get();
            $sum_EGP=0;
            $sum_USD=0;
            foreach ($paymentDeductions as $paymentDeduction) {
               
                if ($paymentDeduction->payment->client_type == 'b') {
                    $temp['currency'] = $this->getClientCurrency('b', $paymentDeduction->payment->client_id);
                } else if ($paymentDeduction->payment->client_type == 'f') {
                    $temp['currency'] = $this->getClientCurrency('f', $paymentDeduction->payment->client_id);
                } else if ($paymentDeduction->payment->client_type == 'p') {
                    $temp['currency'] = $this->getClientCurrency('p', $paymentDeduction->payment->client_id);
                }

                if ($temp['currency'] =="EGP") {
                    $sum_EGP += floatval($paymentDeduction->value);
                }

                if ($temp['currency'] =="USD") {
                    $sum_USD += floatval($paymentDeduction->value);
                }

                

            }

            $temp['deduction_value_EGP'] = $sum_EGP;
            $temp['deduction_value_USD'] = $sum_USD;
            $paymentsWithDeduction->push($temp);
        }

        // return $paymentsWithDeduction;
      
        // $sorted = $paymentsWithDeduction->sortBy('date');

        return json_encode($paymentsWithDeduction->values()->all());
    }

    public function general(PurchaseOrder $purchaseorder)
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->get();
        $title = trans('site.purchase_orders_report_situation');
        $purchaseOrderTotal = [];
        $totalSumatiom = 0;
        foreach ($purchaseorders as $purchaseorder) {
            $totalSumatiom = 0;
            foreach ($purchaseorder->items as $item)
                $totalSumatiom += $item->total_amount;
            array_push($purchaseOrderTotal, $totalSumatiom);
        }
        return view('pages.reports.general', compact('purchaseorders', 'purchaseorder', 'title', 'purchaseOrderTotal'));
    }


    public function mulityApprovePayment(Request $request)
    {
        // return $request->primary_delivery_status;
        foreach ($request->primary_delivery_status as $key => $value) {

            $PurchaseOrder = PurchaseOrder::where('id', $value)->update([
                "primary_delivery_status" => "1",
            ]);
        }
        foreach ($request->final_delivery_status as $key => $value) {

            $PurchaseOrder = PurchaseOrder::where('id', $value)->update([
                "final_delivery_status" => "1",
            ]);
        }

        foreach ($request->social_insurance_status as $key => $value) {

            $PurchaseOrder = PurchaseOrder::where('id', $value)->update([
                "social_insurance_status" => "1",
            ]);
        }
        foreach ($request->labor_insurance_status as $key => $value) {

            $PurchaseOrder = PurchaseOrder::where('id', $value)->update([
                "labor_insurance_status" => "1",
            ]);
        }

        foreach ($request->tax_rate_letter_report as $key => $value) {

            $PurchaseOrder = PurchaseOrder::where('id', $value)->update([
                "tax_rate_letter_report" => "1",
            ]);
        }
        foreach ($request->tax_exemption_certificate_status as $key => $value) {

            $PurchaseOrder = PurchaseOrder::where('id', $value)->update([
                "tax_exemption_certificate_status" => "1",
            ]);
        }

        foreach ($request->received_final_performance_bond_status as $key => $value) {

            $PurchaseOrder = PurchaseOrder::where('id', $value)->update([
                "received_final_performance_bond_status" => "1",
            ]);
        }


        return redirect()->back()->with(['success' => "PurchaseOrder Updated Successfully"]);
    }


    public function taxRateLetter()
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->where('tax_rate_letter_report', '0')->get();
        $title = trans('site.tax_rate_letter_report');

        return view('pages.reports.letter', compact('purchaseorders', 'title'));
    }

    // Primary Delivery Status Report
    public function primaryDeliveryStatus()
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->where('primary_delivery_status', '0')->get();
        $title = trans('site.primary_delivery_status_report');

        return view('pages.reports.status', compact('purchaseorders', 'title'));
    } // End of Primary Delivery Status Report

    // Final Delivery Status Report
    public function finalDeliveryStatus()
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->where('final_delivery_status', '0')->get();
        $title = trans('site.final_delivery_status_report');

        return view('pages.reports.status', compact('purchaseorders', 'title'));
    } // End of Final Delivery Status Report

    // Social Insurance Status Report
    public function socialInsuranceStatus()
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->where('social_insurance_status', '0')->get();
        $title = trans('site.social_insurance_status_report');

        return view('pages.reports.status', compact('purchaseorders', 'title'));
    } // End of Social Insurance Status Report

    // Labor Insurance Status Report
    public function laborInsuranceStatus()
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->where('labor_insurance_status', '0')->get();
        $title = trans('site.labor_insurance_status_report');

        return view('pages.reports.status', compact('purchaseorders', 'title'));
    } // End of Labor Insurance Status Report

    // Tax Exemption Certificate Status Report
    public function taxExemptionCertificateStatus()
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->where('tax_exemption_certificate_status', '0')->get();
        $title = trans('site.tax_exemption_certificate_status_report');

        return view('pages.reports.status', compact('purchaseorders', 'title'));
    } // End of Tax Exemption Certificate Status Report

    // Received Final Performance Bond Status Report
    public function receivedFinalPerformanceBondStatus()
    {
        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->where('received_final_performance_bond_status', '0')->get();
        $title = trans('site.received_final_performance_bond_status_report');

        return view('pages.reports.status', compact('purchaseorders', 'title'));
    } // End of Received Final Performance Bond Status Report

    // Document Total without Taxes
    public function documentTotalwithoutTaxes($document)
    {
        $sign = 1;
        if ($document->type == 'C')
            $sign = -1;

        $rate = floatval($document->items()->get()[0]->rate);
        // return floatval($document->items()->select(DB::raw('sum(quantity * item_price) as totalWithoutTax'))->first()->totalWithoutTax / $rate) * $sign;
        return floatval($document->items()->select(DB::raw('sum(quantity * item_price) as totalWithoutTax'))->first()->totalWithoutTax) * $sign;
    } // End of Document Total without Taxes

    public function documentTotalwithoutTaxe($document)
    {
        $sign = 1;
        if ($document->type == 'C')
            $sign = -1;

        $rate = floatval($document->items()->get()[0]->rate);
        // return floatval($document->items()->select(DB::raw('sum(quantity * item_price) as totalWithoutTax'))->first()->totalWithoutTax / $rate) * $sign;
        return floatval($document->items()->select(DB::raw('sum(quantity * item_price) as totalWithoutTax'))->first()->totalWithoutTax) * $sign * $rate;
    }

    // Document Total with Taxes
    public function documentTotalwithTaxes($document)
    {
        $sign = 1;
        if ($document->type == 'C')
            $sign = -1;

        $rate = floatval($document->items()->get()[0]->rate);
            return floatval($document->items()->sum('total_amount') / $rate) * $sign; // - $document->extra_invoice_discount);
        return floatval($document->items()->sum('total_amount')) * $sign; // - $document->extra_invoice_discount);
    } // End of Document Total with Taxes

    // Document Taxes
    public function documentTaxes($document)
    {
        $sign = 1;
        if ($document->type == 'C')
            $sign = -1;

        $rate = floatval($document->items()->get()[0]->rate);

        $totalTaxes = 0;
        foreach ($document->items as $documentItem) {
            $totalTaxes += $this->itemTaxes($documentItem);
            $client['_documentTaxes'] = 0;
        }
        return floatval($totalTaxes / $rate) * $sign;
    } // End of Document Taxes

    // Item Taxes
    public function itemTaxes($item)
    {
        return floatval($item->DocumentTaxes()->sum('amount_tax'));
    } // End of Item Taxes


    // Get Client Currency
    public function getClientCurrency($clientType, $client_id)
    {
        $purchaseOrder = PurchaseOrder::where('client_id', $client_id)->where('client_type', $clientType)->first();
        if ($purchaseOrder) {
            return $purchaseOrder->items[0]->currency;
        }
        return '';
    } // End of Get Client Currency

    // Client Anaylsis Report
    public function clientAnaylsisReportView()
    {
        return view('pages.reports.clientAnalysisReport');
    }

    public function clientAnaylsisReportData(Request $request)
    {

        $client = collect([
            'name' => '',
            'taxId_NId_VId' => '', // tax_id_number_or_national_id_or_vat_id
            // 'type' => '', // B, P or F
            'documents' => collect([]),
            'currency' => '',
        ]);

        $clients = [];

        // If some of filtered data is submitted
        if ($request->fromDate && $request->toDate) { // filter payments by date
            $allDocuments = Document::where('submit_status', 1)->whereBetween('date', [$request->fromDate, $request->toDate])->with('purchaseOrder')->get();

            foreach ($allDocuments as $document) {
                $po = $document->purchaseOrder;
                if ($document->purchaseOrder->client_type == 'b') {
                    if (!isset($clients[$po->client_type . $po->businessClient->tax_id_number])) { // if not defined before

                        $clients[$po->client_type . $po->businessClient->tax_id_number] = []; // define
                        // set data
                        $client['name'] = $po->businessClient->name;
                        $client['taxId_NId_VId'] = $po->businessClient->tax_id_number;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->businessClient->tax_id_number] = clone $client;
                    }

                    unset($document->purchaseOrder); // remove purchaseOrder relation data

                    $clients[$po->client_type . $po->businessClient->tax_id_number]['documents']->push($document);
                } else if ($document->purchaseOrder->client_type == 'p') {
                    if (!isset($clients[$po->client_type . $po->personClient->national_id])) { // if not defined before

                        $clients[$po->client_type . $po->personClient->national_id] = []; // define
                        // set data
                        $client['name'] = $po->personClient->name;
                        $client['taxId_NId_VId'] = $po->personClient->national_id;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->personClient->national_id] = clone $client;
                    }

                    unset($document->purchaseOrder); // remove purchaseOrder relation data

                    $clients[$po->client_type . $po->personClient->national_id]['documents']->push($document);
                } else if ($document->purchaseOrder->client_type == 'f') {
                    if (!isset($clients[$po->client_type . $po->foreignerClient->vat_id])) { // if not defined before

                        $clients[$po->client_type . $po->foreignerClient->vat_id] = []; // define
                        // set data
                        $client['name'] = $po->foreignerClient->company_name;
                        $client['taxId_NId_VId'] = $po->foreignerClient->vat_id;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->foreignerClient->vat_id] = clone $client;
                    }

                    unset($document->purchaseOrder); // remove purchaseOrder relation data

                    $clients[$po->client_type . $po->foreignerClient->vat_id]['documents']->push($document);
                }
            }

            $counter = 0;
            foreach ($clients as $client) {

                $documentTotalwithoutTaxes = 0;
                $documentTotalwithoutTaxe = 0;
                $documentTotalwithTaxes = 0;
                $documentTaxes = 0;
                $documentTotalwithoutTaxesUSD = 0;
                $documentTotalwithoutTaxesEGP = 0;
                $documentTotalwithoutTaxesEUR = 0;
                foreach ($client['documents'] as $clientDocument) {
                    if($clientDocument->purchaseOrder->items[0]->currency == "USD") {
                        $documentTotalwithoutTaxesUSD += ($this->documentTotalwithoutTaxes($clientDocument));
                        $client['documentTaxes'] = $documentTotalwithTaxes - $documentTotalwithoutTaxesUSD;
                    } else if($clientDocument->purchaseOrder->items[0]->currency == "EUR") {
                        $documentTotalwithoutTaxesEUR += ($this->documentTotalwithoutTaxes($clientDocument));
                        $client['documentTaxes'] = $documentTotalwithTaxes - $documentTotalwithoutTaxesEUR;
                    } else if($clientDocument->purchaseOrder->items[0]->currency == "EGP") {
                        $documentTotalwithoutTaxesEGP += ($this->documentTotalwithoutTaxes($clientDocument));
                        $client['documentTaxes'] = $documentTotalwithTaxes - $documentTotalwithoutTaxesEGP;
                    } else {
                        $documentTotalwithoutTaxes += 0;
                    }
                    $documentTotalwithoutTaxes += ($this->documentTotalwithoutTaxes($clientDocument)); // sum to calculate total documents without taxes
                    $documentTotalwithTaxes += ($this->documentTotalwithTaxes($clientDocument));  // sum to calculate total documents with taxes
                    $documentTaxes += ($this->documentTaxes($clientDocument));  // sum to calculate total documents taxes
                    $documentTotalwithoutTaxe += ($this->documentTotalwithoutTaxe($clientDocument)); // sum to calculate total documents without taxes
                    // if($counter == 4){
                    //     dump($documentTotalwithoutTaxes);
                    //     dump($documentTotalwithTaxes);
                    // }
                }
                if($client['currency'] == "USD") {
                    $client['documentTaxes'] = $documentTotalwithTaxes - $documentTotalwithoutTaxesUSD;
                }     if($client['currency'] == "EUR") {
                    $client['documentTaxes'] = $documentTotalwithTaxes - $documentTotalwithoutTaxesEUR;
                }     if($client['currency'] == "EGP") {
                    $client['documentTaxes'] = $documentTotalwithTaxes - $documentTotalwithoutTaxesEGP;
                } else {
                    $client['documentTaxes'] = $documentTotalwithTaxes - 0;
                }
                $client['documents'] = count($client['documents']);
                $client['documentTotalwithoutTaxes'] = $documentTotalwithoutTaxes;
                $client['documentTotalwithoutTaxesUSD'] = $documentTotalwithoutTaxesUSD;
                $client['documentTotalwithoutTaxesEGP'] = $documentTotalwithoutTaxesEGP;
                $client['documentTotalwithoutTaxesEUR'] = $documentTotalwithoutTaxesEUR;
                $client['documentTotalwithTaxes'] = $documentTotalwithTaxes;


                $client['documentTotalwithoutTaxe'] = $documentTotalwithoutTaxe;

                $counter++;
            }
        }

        return json_encode(array_values($clients));
    }
    // End of Client Anaylsis Report

    // valid letters guarantee report
    public function validLettersGuaranteeReportView()
    {
        $sides = LettersGuarantee::distinct('side')->pluck('side');
        $banksName = Bank::distinct('bank_name')->pluck('bank_name');

        // $banksName = [];
        // $banks = Bank::select('bank_name')->get()->toArray();
        // foreach ($banks as $key => $bank) {
        //     $banksName[] = $bank["bank_name"];
        // }

        // //  return
        // //  $banks = (array) $banks;
        // $banksName = array_unique($banksName);


        return view('pages.reports.validLettersGuaranteeReport', compact("sides", "banksName"));
    }

    public function validLettersGuaranteeReportData(Request $request)
    {
        // return $request;
         $bank_name = $request->bank_name;
            $bankIds = Bank::where('bank_name', $bank_name)->pluck('id')->toArray(); // p

        if (!isset($request->side) && !isset($request->status) && !isset($request->expiry_date) && !isset($request->type) && count($bankIds) == 0) {

            $lettersGuarantee = LettersGuarantee::all();
        } else {

            $lettersGuarantee = LettersGuarantee::where(function ($q) use ($request, $bankIds) {

                if (isset($request->status)) {
                    if ($request->status == "answered") {
                        $q->whereNotNull("reply_date");
                    } else {
                        $q->whereNull("reply_date");
                    }
                }

                if (isset($request->side)) {
                    $q->where("side", $request->side);
                }
                if (isset($request->type)) {
                    $q->where("type", $request->type);
                }
                if (isset($request->expiry_date)) {
                    $q->where("expiry_date", $request->expiry_date);
                }
                if (count($bankIds) > 0) {
                    $q->whereIn('bank_id', $bankIds);
                }
            })->get();
        }

        // if (isset($request->side) && isset($request->status) && isset($request->type) && count($bankIds) > 0) {

        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereIn('bank_id', $bankIds)->where("type", $request->type)->whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereIn('bank_id', $bankIds)->where("type", $request->type)->whereNull("reply_date")->get();
        //     }
        // } elseif (isset($request->status) && isset($request->type) && count($bankIds) > 0) {

        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::whereIn('bank_id', $bankIds)->where("type", $request->type)->whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::whereIn('bank_id', $bankIds)->where("type", $request->type)->whereNull("reply_date")->get();
        //     }
        // } elseif (isset($request->side) && isset($request->type) && count($bankIds) > 0) {

        //     $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereIn('bank_id', $bankIds)->where("type", $request->type)->get();
        // } elseif (isset($request->side) && isset($request->status)  && count($bankIds) > 0) {

        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereIn('bank_id', $bankIds)->whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereIn('bank_id', $bankIds)->whereNull("reply_date")->get();
        //     }
        // } elseif (isset($request->side) && isset($request->status) && isset($request->type)) {

        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->where("type", $request->type)->whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->where("type", $request->type)->whereNull("reply_date")->get();
        //     }
        // } elseif (isset($request->side) && count($bankIds) > 0) {

        //     $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereIn('bank_id', $bankIds)->get();
        // } elseif (isset($request->type) && count($bankIds) > 0) {

        //     $lettersGuarantee = LettersGuarantee::where("type", $request->type)->whereIn('bank_id', $bankIds)->get();
        // } elseif (isset($request->status) && count($bankIds) > 0) {

        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::whereIn('bank_id', $bankIds)->whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::whereIn('bank_id', $bankIds)->whereNull("reply_date")->get();
        //     }
        // } elseif (isset($request->status) && isset($request->type)) {

        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::where("type", $request->type)->whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::where("type", $request->type)->whereNull("reply_date")->get();
        //     }
        // } elseif (isset($request->side) && isset($request->type)) {


        //     $lettersGuarantee = LettersGuarantee::where("side", $request->side)->where("type", $request->type)->get();
        // } elseif (isset($request->side) && isset($request->status)) {


        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::where("side", $request->side)->whereNull("reply_date")->get();
        //     }
        // } elseif (isset($request->side)) {


        //     $lettersGuarantee = LettersGuarantee::where("side", $request->side)->get();
        // } elseif (count($bankIds) > 0) {


        //     $lettersGuarantee = LettersGuarantee::whereIn('bank_id', $bankIds)->get();
        // } elseif (isset($request->type)) {


        //     $lettersGuarantee = LettersGuarantee::where("type", $request->type)->get();
        // } elseif (isset($request->status)) {


        //     if ($request->status == "answered") {
        //         $lettersGuarantee = LettersGuarantee::whereNotNull("reply_date")->get();
        //     } else {
        //         $lettersGuarantee = LettersGuarantee::whereNull("reply_date")->get();
        //     }
        // } else {


        //     $lettersGuarantee = LettersGuarantee::all();
        // }

        return view('pages.reports.tableValidLettersGuaranteeReport', compact("lettersGuarantee"));
    }
    // End valid letters guarantee report
    // valid letters guarantee report
    public function applicantLettersGuaranteeReportView()
    {
        $businessClients = BusinessClient::all();
        return view('pages/reports/applicantLettersGuaranteeReport', compact("businessClients"));
    }

    public function applicantLettersGuaranteeReportData(Request $request)
    {
        // return $request;

        // $lettersGuarantee = LettersGuarantee::get();
        if (isset($request->client_id) && isset($request->status)) {
            if ($request->status == "answered") {
                $lettersGuarantee = LettersGuarantee::where("client_type", $request->client_type)->where("client_id", $request->client_id)->whereNotNull("reply_date")->get();
            } else {
                $lettersGuarantee = LettersGuarantee::where("client_type", $request->client_type)->where("client_id", $request->client_id)->whereNull("reply_date")->get();
            }
        } elseif (isset($request->client_id)) {
            $lettersGuarantee = LettersGuarantee::where("client_type", $request->client_type)->where("client_id", $request->client_id)->get();
        } elseif (isset($request->status)) {

            if ($request->status == "answered") {
                $lettersGuarantee = LettersGuarantee::whereNotNull("reply_date")->get();
            } else {
                $lettersGuarantee = LettersGuarantee::whereNull("reply_date")->get();
            }
        } else {
            $lettersGuarantee = LettersGuarantee::all();
        }

        return view('pages.reports.tableApplicantLettersGuaranteeReport', compact("lettersGuarantee"));
    }
    // End valid letters guarantee report
    // valid letters guarantee report
    public function vchecksIssuedClientsReportView()
    {

        $project_names = PurchaseOrders::distinct('project_number')->pluck('project_number');
        return view('pages.reports.checksIssuedClientsReport', compact("project_names"));
    }

    public function vchecksIssuedClientsReportData(Request $request)
    {

        if (isset($request->project_name) && isset($request->status)) {
            if ($request->status == "answered") {
                $warrantyChecks = WarrantyChecks::whereNotNull("reply_date")->where("project_number", $request->project_name)->get();
            } else {
                $warrantyChecks = WarrantyChecks::whereNull("reply_date")->where("project_number", $request->project_name)->get();
            }
        } elseif (isset($request->status)) {
            if ($request->status == "answered") {
                $warrantyChecks = WarrantyChecks::whereNotNull("reply_date")->get();
            } else {
                $warrantyChecks = WarrantyChecks::whereNull("reply_date")->get();
            }
        } elseif (isset($request->project_name)) {
            $warrantyChecks = WarrantyChecks::where("project_number", $request->project_name)->get();
        } else {
            $warrantyChecks = WarrantyChecks::all();
        }

        return view('pages.reports.tableChecksIssuedClientsReport', compact("warrantyChecks"));
    }
    // End valid letters guarantee report


    // Client Payments
    public function clientPayments($clientType, $id, $todate)
    {
        // dump("clientType: " . $clientType . ", id: " . $id . ", Todate: " . $todate);
        $total = 0;
        $allDeductionPayments = 0;
        if ($todate)
            $total = floatval(Payment::where('client_type', $clientType)->where('client_id', $id)->where('payment_date', '<', $todate)->sum('value'));
        else
            $total = floatval(Payment::where('client_type', $clientType)->where('client_id', $id)->sum('value'));

        // if ($todate)
        //     dump(Payment::where('client_type', $clientType)->where('client_id', $id)->where('payment_date', '<', $todate)->get());
        // else
        //     dump(Payment::where('client_type', $clientType)->where('client_id', $id)->where('payment_date', '<', $todate)->get());

        // if ($todate)
        //     dump(Payment::where('client_type', $clientType)->where('client_id', $id)->where('payment_method', 'deduction')->where('payment_date', '<', $todate)->get());
        // else
        //     dump(Payment::where('client_type', $clientType)->where('client_id', $id)->where('payment_method', 'deduction')->get());

        if ($todate)
            $allDeductionPayments = Payment::where('client_type', $clientType)->where('client_id', $id)->where('payment_method', 'deduction')->where('payment_date', '<', $todate)->get();
        else
            $allDeductionPayments = Payment::where('client_type', $clientType)->where('client_id', $id)->where('payment_method', 'deduction')->get();

        $sumOfIgnoredDeductionD15 = 0;
        foreach ($allDeductionPayments as $deductionPayment) {
            foreach ($deductionPayment->deductions as $deduction) {
                if ($deduction->deduction->code == 'D15')  // Ignore الدفعات المقدمة
                    $sumOfIgnoredDeductionD15 += $deduction->value;
            }
        }

        // dump("total: " . $total . ' || ' . "sumOfIgnoredDeductionD15: " . $sumOfIgnoredDeductionD15);

        return $total - floatval($sumOfIgnoredDeductionD15);
    } // End of Client Payments

    // Client Balances Report
    public function clientBalancesReport()
    {
        return view('pages.reports.clientBalancesReport');
    }

    public function clientBalancesReportData(Request $request)
    {
        $client = collect([
            'id' => '',
            'name' => '',
            'taxId_NId_VId' => '', // tax_id_number_or_national_id_or_vat_id
            'type' => '', // b, p or f
            'total_extra_invoice_discount' => 0,
            'documents' => collect([]),
            'payments' => 0,
            'currency' => '',
        ]);

        $clients = [];
        $sign = 1;

        // If some of filtered data is submitted
        if ($request->toDate) { // filter payments by date

            // $allDocuments = Document::where('submit_status', 1)->where('date', '>', $request->toDate)->with('purchaseOrder')->get();
            $allDocuments = Document::where('submit_status', 1)->where('date', '<=', $request->toDate)->with('purchaseOrder')->get();
            $purchaseOrders = PurchaseOrder::get();

            foreach ($allDocuments as $document) {
                $po = $document->purchaseOrder;
                if ($document->purchaseOrder->client_type == 'b') {
                    if (!isset($clients[$po->client_type . $po->businessClient->tax_id_number])) { // if not defined before

                        $clients[$po->client_type . $po->businessClient->tax_id_number] = []; // define
                        // set data
                        $client['id'] = $po->businessClient->id;
                        $client['type'] = 'b';
                        $client['name'] = $po->businessClient->name;
                        $client['taxId_NId_VId'] = $po->businessClient->tax_id_number;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->businessClient->tax_id_number] = clone $client;
                    }

                    unset($document->purchaseOrder); // remove purchaseOrder relation data

                    if ($client['documents'] == 'C') { // For Credit document
                        $sign = -1;
                    } else {
                        $sign = 1;
                    }
                    $client['total_extra_invoice_discount'] += ($document->total_extra_invoice_discount * $sign);
                    $clients[$po->client_type . $po->businessClient->tax_id_number]['documents']->push($document);
                } else if ($document->purchaseOrder->client_type == 'p') {
                    if (!isset($clients[$po->client_type . $po->personClient->national_id])) { // if not defined before

                        $clients[$po->client_type . $po->personClient->national_id] = []; // define
                        // set data
                        $client['id'] = $po->personClient->id;
                        $client['type'] = 'p';
                        $client['name'] = $po->personClient->name;
                        $client['taxId_NId_VId'] = $po->personClient->national_id;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->personClient->national_id] = clone $client;
                    }

                    unset($document->purchaseOrder); // remove purchaseOrder relation data

                    if ($client['documents'] == 'C') { // For Credit document
                        $sign = -1;
                    } else {
                        $sign = 1;
                    }
                    $client['total_extra_invoice_discount'] += ($document->total_extra_invoice_discount * $sign);
                    $clients[$po->client_type . $po->personClient->national_id]['documents']->push($document);
                } else if ($document->purchaseOrder->client_type == 'f') {
                    if (!isset($clients[$po->client_type . $po->foreignerClient->vat_id])) { // if not defined before

                        $clients[$po->client_type . $po->foreignerClient->vat_id] = []; // define
                        // set data
                        $client['id'] = $po->foreignerClient->id;
                        $client['type'] = 'f';
                        $client['name'] = $po->foreignerClient->company_name;
                        $client['taxId_NId_VId'] = $po->foreignerClient->vat_id;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->foreignerClient->vat_id] = clone $client;
                    }

                    unset($document->purchaseOrder); // remove purchaseOrder relation data
                    $client['total_extra_invoice_discount'] += ($document->total_extra_invoice_discount);
                    $clients[$po->client_type . $po->foreignerClient->vat_id]['documents']->push($document);
                }
            }
            foreach ($purchaseOrders as $po) {
                if ($po->client_type == 'b') {
                    if (!isset($clients[$po->client_type . $po->businessClient->tax_id_number])) { // if not defined before

                        $clients[$po->client_type . $po->businessClient->tax_id_number] = []; // define
                        // set data
                        $client['id'] = $po->businessClient->id;
                        $client['type'] = 'b';
                        $client['name'] = $po->businessClient->name;
                        $client['taxId_NId_VId'] = $po->businessClient->tax_id_number;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->businessClient->tax_id_number] = clone $client;
                    }

                    unset($po); // remove purchaseOrder relation data

                    if ($client['documents'] == 'C') { // For Credit document
                        $sign = -1;
                    } else {
                        $sign = 1;
                    }
                    $client['total_extra_invoice_discount'] += ($document->total_extra_invoice_discount * $sign);
                    
                } else if ($po->client_type == 'p') {
                    if (!isset($clients[$po->client_type . $po->personClient->national_id])) { // if not defined before

                        $clients[$po->client_type . $po->personClient->national_id] = []; // define
                        // set data
                        $client['id'] = $po->personClient->id;
                        $client['type'] = 'p';
                        $client['name'] = $po->personClient->name;
                        $client['taxId_NId_VId'] = $po->personClient->national_id;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->personClient->national_id] = clone $client;
                    }

                    unset($po); // remove purchaseOrder relation data

                    if ($client['documents'] == 'C') { // For Credit document
                        $sign = -1;
                    } else {
                        $sign = 1;
                    }
                    $client['total_extra_invoice_discount'] += ($document->total_extra_invoice_discount * $sign);
                } else if ($po->client_type == 'f') {
                    if (!isset($clients[$po->client_type . $po->foreignerClient->vat_id])) { // if not defined before

                        $clients[$po->client_type . $po->foreignerClient->vat_id] = []; // define
                        // set data
                        $client['id'] = $po->foreignerClient->id;
                        $client['type'] = 'f';
                        $client['name'] = $po->foreignerClient->company_name;
                        $client['taxId_NId_VId'] = $po->foreignerClient->vat_id;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency($po->client_type, $po->client_id);

                        $clients[$po->client_type . $po->foreignerClient->vat_id] = clone $client;
                    }

                    unset($po); // remove purchaseOrder relation data
                    $client['total_extra_invoice_discount'] += ($document->total_extra_invoice_discount);
                }
            }
            // dd($clients); // contain clients [companies] with its filtered documents by date range
            $clientsNotHaveDocumentsKeys = [];


            $clientsNotHaveDocuments = [];
            // Get client which doesn't have document but have payment (payment on purchase order)
            $otherPayments = Payment::select('client_id', 'client_type')->get();

            foreach ($otherPayments as $otherPayment) {
                if ($otherPayment->client_type == 'b') {
                    if (!array_key_exists($otherPayment->client_type . $otherPayment->businessClient->tax_id_number, $clients) && !in_array($otherPayment->client_type . $otherPayment->businessClient->tax_id_number, $clientsNotHaveDocumentsKeys)) {
                        array_push($clientsNotHaveDocuments, ['type' => $otherPayment->client_type, 'id' => $otherPayment->client_id]);
                        array_push($clientsNotHaveDocumentsKeys, $otherPayment->client_type . $otherPayment->businessClient->tax_id_number);
                    }
                } else if ($otherPayment->client_type == 'p') {
                    if (!array_key_exists($otherPayment->client_type . $otherPayment->personClient->national_id, $clients) && !in_array($otherPayment->client_type . $otherPayment->personClient->national_id, $clientsNotHaveDocumentsKeys)) {
                        array_push($clientsNotHaveDocuments, ['type' => $otherPayment->client_type, 'id' => $otherPayment->client_id]);
                        array_push($clientsNotHaveDocumentsKeys, $otherPayment->client_type . $otherPayment->personClient->national_id);
                    }
                } else if ($otherPayment->client_type == 'f') {
                    if (!array_key_exists($otherPayment->client_type . $otherPayment->foreignerClient->vat_id, $clients) && !in_array($otherPayment->client_type . $otherPayment->foreignerClient->vat_id, $clientsNotHaveDocumentsKeys)) {
                        array_push($clientsNotHaveDocuments, ['type' => $otherPayment->client_type, 'id' => $otherPayment->client_id]);
                        array_push($clientsNotHaveDocumentsKeys, $otherPayment->client_type . $otherPayment->foreignerClient->vat_id);
                    }
                }
            }

            // Get clients have payments but not have documents
            foreach ($clientsNotHaveDocuments as $key => $clientNotHaveDocuments) {
                if ($clientNotHaveDocuments['type'] == 'b') {
                    $currentClient = BusinessClient::where('id', $clientNotHaveDocuments['id'])->first();
                    if (!isset($clients[$clientsNotHaveDocumentsKeys[$key]])) { // if not defined before
                        $clients[$clientsNotHaveDocumentsKeys[$key]] = []; // define
                        // set data
                        $client['id'] = $currentClient->id;
                        $client['type'] = 'b';
                        $client['name'] = $currentClient->name;
                        $client['taxId_NId_VId'] = $currentClient->tax_id_number;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency('b', $currentClient->id);

                        $clients[$clientsNotHaveDocumentsKeys[$key]] = clone $client;
                    }
                } else if ($otherPayment->client_type == 'p') {
                    if (!isset($clients[$clientsNotHaveDocumentsKeys[$key]])) { // if not defined before
                        $currentClient = PersonClient::where('id', $clientNotHaveDocuments['id'])->first();

                        $clients[] = []; // define
                        // set data
                        $client['id'] = $currentClient->id;
                        $client['type'] = 'p';
                        $client['name'] = $currentClient->name;
                        $client['taxId_NId_VId'] = $currentClient->national_id;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency('p', $currentClient->id);

                        $clients[$clientsNotHaveDocumentsKeys[$key]] = clone $client;
                    }
                } else if ($otherPayment->client_type == 'f') {
                    if (!isset($clients[$clientsNotHaveDocumentsKeys[$key]])) { // if not defined before
                        $currentClient = ForeignerClient::where('id', $clientNotHaveDocuments['id'])->first();

                        $clients[$clientsNotHaveDocumentsKeys[$key]] = []; // define
                        // set data
                        $client['id'] = $currentClient->id;
                        $client['type'] = 'f';
                        $client['name'] = $currentClient->company_name;
                        $client['taxId_NId_VId'] = $currentClient->vat_id;
                        $client['total_extra_invoice_discount'] = 0;
                        $client['documents'] = collect([]);
                        $client['currency'] = $this->getClientCurrency('f', $currentClient->id);

                        $clients[$clientsNotHaveDocumentsKeys[$key]] = clone $client;
                    }
                }
            }

            unset($clientsNotHaveDocumentsKeys);

            foreach ($clients as $client) {

                $sign = 1; // to subtract document which its type is Credit
                $documentTotalwithoutTaxes = 0;
                $documentTotalwithTaxes = 0;

                foreach ($client['documents'] as $clientDocument) {
                    $documentTotalwithoutTaxes += ($this->documentTotalwithoutTaxes($clientDocument)); // sum to calculate total documents without taxes
                    $documentTotalwithTaxes += ($this->documentTotalwithTaxes($clientDocument));  // sum to calculate total documents with taxes
                }

                $client['documents'] = count($client['documents']);
                $client['documentTotalwithoutTaxes'] = $documentTotalwithoutTaxes;
                $client['documentTotalwithTaxes'] = $documentTotalwithTaxes;
                $client['payments'] = $this->clientPayments($client['type'], $client['id'], $request->toDate);
            }
        }
//        return $clients;
        return json_encode(array_values($clients));
    }
    // End of Client Balances Report

    // Collections Report
    public function collectionsReport()
    {
        $payments = Payment::orderBy('id', 'DESC')->paginate(env('PAGINATION_LENGTH', 5));
        return view('pages.reports.collections_report', compact('payments'));
        // return view('pages.reports.collections_report');
    }

    public function collectionsReportData(Request $request)
    {
        // dd($request->all());
        $clientWithPayments = [
            'client_name' => '',
            'ref' => '',
            'taxId_NId_VId' => '', // tax_id_number_or_national_id_or_vat_id
            'value' => 0,
            'date' => ''
        ];

        $clients = collect([]);

        $payments = Payment::whereBetween('payment_date', [$request->fromDate, $request->toDate]);

        // If some of filtered data is submitted
        if ($request->clientType && $request->clientId) { // filter payments by client
            $payments = $payments->where('client_type', $request->clientType)->where('client_id', $request->clientId);
        }

        $payments = $payments->get();

        // Order result by payment date
        foreach ($payments as $payment) {
            // client name
            if ($payment->client_type == 'b') {
                $clientWithPayments['client_name'] = $payment->businessClient->name;
                $clientWithPayments['taxId_NId_VId'] = $payment->businessClient->tax_id_number;
            } else if ($payment->client_type == 'f') {
                $clientWithPayments['client_name'] = $payment->foreignerClient->company_name;
                $clientWithPayments['taxId_NId_VId'] = $payment->foreignerClient->vat_id;
            } else if ($payment->client_type == 'p') {
                $clientWithPayments['client_name'] = $payment->personClient->name;
                $clientWithPayments['taxId_NId_VId'] = $payment->personClient->national_id;
            }


            if ($payment->table == 'PO') {
                $clientWithPayments['reff'] = $payment->purchaseOrder->purchase_order_reference;
            } else {
                $clientWithPayments['reff'] = $payment->document->document_number;
            }


            if ($payment->payment_method == 'cashe') {
                $clientWithPayments['payment'] = 'نقدي';
            } elseif ($payment->payment_method == 'bank_transfer') {
                $clientWithPayments['payment'] = 'تحويل بنكي';
            } elseif ($payment->payment_method == 'cheque') {
                $clientWithPayments['payment'] = 'شيك بنكي';
            } else if ($payment->payment_method == 'deduction') {

                // $paymentMethod = $payment->deductions->deduction_id ;
                $clientWithPayments['payment'] = $payment->deductions;

                foreach ($clientWithPayments['payment'] as $deduction) {

                    // $clientWithPayments['payment'] = $deduction->deduction->name;
                    $clientWithPayments['payment'] = "إستقطاع";
                }
            }


            $clientWithPayments['value'] = floatval($payment->value);
            $clientWithPayments['date'] = $payment->payment_date;

            $clients->push($clientWithPayments);
        }

        $sorted = $clients->sortBy('date');

        return json_encode($sorted->values()->all());
    }
    // End of Collections Report


    // Client Balances To Data
    public function clientBalancesToData($toDate, $clientType, $clientId)
    {
        $client = collect([
            'id' => '',
            'name' => '',
            'taxId_NId_VId' => '', // tax_id_number_or_national_id_or_vat_id
            'type' => '', // b, p or f
            'total_extra_invoice_discount' => 0,
            'documents' => collect([]),
            'payments' => 0,
            'documentTotalwithTaxes' => 0,
        ]);

        $client['id'] = $clientId;
        $client['type'] = $clientType;

        $_client = null;
        if ($clientType == 'b') {
            $_client = BusinessClient::find($clientId);

            // set data
            $client['name'] = $_client->name;
            $client['taxId_NId_VId'] = $_client->tax_id_number;
            $client['total_extra_invoice_discount'] = 0;
            $client['documents'] = collect([]);
        } else if ($clientType == 'p') {
            $_client = PersonClient::find($clientId);

            // set data

            $client['name'] = $_client->name;
            $client['taxId_NId_VId'] = $_client->national_id;
            $client['total_extra_invoice_discount'] = 0;
            $client['documents'] = collect([]);
        } else if ($clientType == 'f') {
            $_client = ForeignerClient::find($clientId);

            // set data
            $client['name'] = $_client->company_name;
            $client['taxId_NId_VId'] = $_client->vat_id;
            $client['total_extra_invoice_discount'] = 0;
            $client['documents'] = collect([]);
        }

        $client->id = $clientId;
        $client->type = $clientType;


        $sign = 1;

        // If some of filtered data is submitted
        if ($toDate) { // filter payments by date

            $allDocuments = Document::where('submit_status', 1)->where('date', '<', $toDate)->get();
            foreach ($allDocuments as $document) {
                if ($document->purchaseOrder->client_id != $clientId || $document->purchaseOrder->client_type != $clientType) // skip iteration if not current client
                {
                    continue;
                }

                if ($client['documents'] == 'C') { // For Credit document
                    $sign = -1;
                } else {
                    $sign = 1;
                }
                $client['total_extra_invoice_discount'] += ($document->total_extra_invoice_discount * $sign);
                $client['documents']->push($document);
            }


            $sign = 1; // to subtract document which its type is Credit

            $documentTotalwithTaxes = 0;

            foreach ($client['documents'] as $clientDocument) {
                $documentTotalwithTaxes += ($this->documentTotalwithTaxes($clientDocument));  // sum to calculate total documents with taxes
            }

            $client['documents'] = count($client['documents']);
            $client['documentTotalwithTaxes'] = $documentTotalwithTaxes;
            $client['payments'] = $this->clientPayments($client['type'], $client['id'], $toDate);

            unset($client['documents']);
            unset($client['id']);
            unset($client['name']);
            unset($client['type']);
            unset($client['taxId_NId_VId']);
            unset($client['total_extra_invoice_discount']);
        }

        // dump($client['documentTotalwithTaxes']);
        // dump($client['payments']);

        // return $client;
        return $client['documentTotalwithTaxes'] - $client['payments'];
    } // End of Client Balances To Data

    // Daily Client Balances Report
    public function dailyClientBalancesReport()
    {
        return view('pages.reports.dailyClientBalances');
    }

    public function dailyClientBalancesReportData(Request $request)
    {
        // dump($request->all());
        $temp = [
            'P_or_D' => '', // Payment or document => p or d
            'value' => 0,
            'reference' => '',
            'reff' => '',
            'date' => '',
        ];

        $documentAndPayment = collect([]);
        if ($request->fromDate && $request->clientType && $request->clientId) { // filter payments by date
            if ($request->clientType == 'b') {
                $client = BusinessClient::find($request->clientId);
            } else if ($request->clientType == 'p') {
                $client = PersonClient::find($request->clientId);
            } else if ($request->clientType == 'f') {
                $client = ForeignerClient::find($request->clientId);
            }
            if ($client) {
//                 $purchaseorders = $client->purchaseOrders()->with('documents')->whereBetween('created_at', [$request->fromDate, $request->toDate])->get();
                $purchaseorders = $client->purchaseOrders()->get();

                $sum = 0;
                foreach ($purchaseorders as $purchaseorder) {
                    $allDocuments = [];
                    if ($request->toDate) // filter payments by date
                        $allDocuments = $purchaseorder->documents()->whereBetween('date', [$request->fromDate, $request->toDate])->where('submit_status', 1)->get();
                    else
                        $allDocuments = $purchaseorder->documents()->where('date', '>=', $request->fromDate)->where('submit_status', 1)->get();

                    foreach ($allDocuments as $document) {
                        $temp['P_or_D'] = 'doc';
                        $temp['value'] = $this->documentTotalwithTaxes($document);
                        $temp['reference'] = $document->document_number;
                        $temp['reff'] = '';
                        $temp['date'] = $document->date;
                        $documentAndPayment->push($temp);
                    }
                }

                $payments = [];
                if ($request->toDate) // filter payments by date

                    $payments = $client->payments()->whereBetween('payment_date', [$request->fromDate, $request->toDate])->get();
                else
                    $payments = $client->payments()->where('payment_date', '>=', $request->fromDate)->get();

                foreach ($payments as $payment) {
                    if ($payment->payment_method == 'deduction') {
                        $paymentDeductions = $payment->deductions()->get();
                        foreach ($paymentDeductions as $paymentDeduction) {
                            if ($paymentDeduction->deduction->code == 'D15') // Ignore الدفعات المقدمة
                                continue;
                            $temp['P_or_D'] = 'd';
                            $temp['value'] = floatval($paymentDeduction->value);
                            // want edit +
                            $temp['reference'] = $paymentDeduction->deduction->name;

                            if ($payment->table == 'PO') {
                                $temp['reff'] = $payment->purchaseOrder->purchase_order_reference;
                            } else {
                                $temp['reff'] = $payment->document->document_number;
                            }
                            $temp['date'] = $payment->payment_date;
                            $documentAndPayment->push($temp);
                        }
                    } // here
                    else {
                        $temp['P_or_D'] = 'p';
                        $temp['value'] = floatval($payment->value);
                        if ($payment->payment_method == 'bank_transfer') {
                            $temp['reference'] = "Bank transfer: " . $payment->bank_transfer->bank_name;
                        } else if ($payment->payment_method == 'cheque') {
                            $temp['reference'] = 'Cheque: ' . $payment->cheque->cheque_number;
                        } else if ($payment->payment_method == 'cashe') {
                            $temp['reference'] = 'Cashe';
                        }

                        if ($payment->table == 'PO') {
                            $temp['reff'] = $payment->purchaseOrder->purchase_order_reference;
                        } else {
                            $temp['reff'] = $payment->document->document_number;
                        }

                        $temp['date'] = $payment->payment_date;
                        $documentAndPayment->push($temp);
                    }
                }
            }
        }
        $openingBalance = $this->clientBalancesToData($request->fromDate, $request->clientType, $request->clientId);

        $sorted = $documentAndPayment->sortBy('date');

        return json_encode(collect([
            'documentsAndPayments' => $sorted->values()->all(),
            'opening_balance' => $openingBalance,
        ]));
    }
    // End of Daily Client Balances Report
    // The financial position of the supply order
    public function financialPositionOfSupplyOrder()
    {
        $from_date = DB::table('documents')->first()->date;
        $to_date = DB::table('documents')->latest()->first()->date;
        return view('pages.reports.financialPositionOfSupplyOrder', compact('from_date', 'to_date'));
    }
    public function financialPositionOfSupplyOrderData(Request $request)
    {
        //    dd($request);
        $temp = [
            'P_or_D' => '', // Payment or document => p or d
            'value' => 0,
            'reference' => '',
            'reff' => '',
            'date' => '',
            //po
            'purchase_order_reference' => '',
            'project_name' => '',
        ];
        $documentAndPayment = collect([]);
        if ($request->clientType && $request->clientId) { // filter payments by date
            if ($request->clientType == 'b') {
                $client = BusinessClient::find($request->clientId);
            } else if ($request->clientType == 'p') {
                $client = PersonClient::find($request->clientId);
            } else if ($request->clientType == 'f') {
                $client = ForeignerClient::find($request->clientId);
            }
            if ($client) {
                // $purchaseorders = $client->purchaseOrders()->with('documents')->whereBetween('created_at', [$request->fromDate, $request->toDate])->get();
                // whereIn('id', $integerIDs)
                $integerIDs = [];
                if (isset($request->purchaseOrderId)) {
                    $integerIDs = array_map('intval', $request->purchaseOrderId);
                }
                $purchaseorders = $client->purchaseOrders()->whereIn('id', $integerIDs)->get();
                // dd($purchaseorders,$integerIDs,$request->purchaseorder);
                $sum = 0;

                $payments = [];

                $payments = $client->payments()->get();
                foreach ($purchaseorders as $purchaseorder) {

                    $temp['purchase_order_reference'] = $purchaseorder->purchase_order_reference;
                    $temp['project_name'] = $purchaseorder->project_name;
                    // dd($temp['purchase_order_reference'], $temp['project_name'],$purchaseorder->purchase_order_reference,$purchaseorder->project_name);
                    $allDocuments = [];

                    $allDocuments = $purchaseorder->documents()->where('submit_status', 1)->get();

                    foreach ($allDocuments as $document) {
                        $temp['P_or_D'] = 'doc';
                        $temp['value'] = $this->documentTotalwithTaxes($document);
                        $temp['reference'] = $document->document_number;
                        $temp['reff'] = '';
                        $temp['date'] = $document->date;
                        $documentAndPayment->push($temp);
                    }

                    foreach ($payments as $payment) {
                        if ($payment->payment_method == 'deduction') {
                            $paymentDeductions = $payment->deductions()->get();
                            foreach ($paymentDeductions as $paymentDeduction) {
                                if ($paymentDeduction->deduction->code == 'D15') // Ignore الدفعات المقدمة
                                    continue;
                                $temp['P_or_D'] = 'd';
                                $temp['value'] = floatval($paymentDeduction->value);
                                // want edit +
                                $temp['reference'] = $paymentDeduction->deduction->name;

                                if ($payment->table == 'PO') {
                                    $temp['reff'] = $payment->purchaseOrder->purchase_order_reference;
                                } else {
                                    $temp['reff'] = $payment->document->document_number;
                                }
                                $temp['date'] = $payment->payment_date;
                                $documentAndPayment->push($temp);
                            }
                        } // here
                        else {
                            $temp['P_or_D'] = 'p';
                            $temp['value'] = floatval($payment->value);
                            if ($payment->payment_method == 'bank_transfer') {
                                $temp['reference'] = "Bank transfer: " . $payment->bank_transfer->bank_name;
                            } else if ($payment->payment_method == 'cheque') {
                                $temp['reference'] = 'Cheque: ' . $payment->cheque->cheque_number;
                            } else if ($payment->payment_method == 'cashe') {
                                $temp['reference'] = 'Cashe';
                            }

                            if ($payment->table == 'PO') {
                                $temp['reff'] = $payment->purchaseOrder->purchase_order_reference;
                            } else {
                                $temp['reff'] = $payment->document->document_number;
                            }

                            $temp['date'] = $payment->payment_date;
                            $documentAndPayment->push($temp);
                        }
                    }
                }
            }
        }
        $request->fromDate = "2022-10-01";
        $openingBalance = $this->clientBalancesToData($request->fromDate, $request->clientType, $request->clientId);

        $sorted = $documentAndPayment->sortBy('date');

        return json_encode(collect([
            'documentsAndPayments' => $sorted->values()->all(),
            'opening_balance' => $openingBalance,
        ]));
    }
    // get business client
    public function getClientsPo(Request $request)
    {
        $Po =  BusinessClient::find($request->clientType);
        $Po->purchaseOrders;

        return json_encode(json_decode($Po->purchaseOrders));
    }
    // End Of Get Clients From Client Type
    //end The financial position of the supply order

    // Client Document Balances Report
    public function clientDocumentBalancesReport()
    {
        return view('pages.reports.clientDocmentBalancesReport');
    }

    public function documentTotalPayments($document)
    {
        return floatval($document->payments()->sum('value'));
    }

    public function clientDocumentBalancesReportData(Request $request)
    {
        $temp = [
            'reference' => '',
            'supplier' => '',
            'date' => '',
            'totalwithoutTaxes' => 0,
            'totalwithTaxes' => 0,
            'taxes' => 0,
            'C_Ch_BT' => 0, // Cashe, Cheque or Bank transfer
            'deductionwithout_D1' => 0, // D1 is the code for 'ضرائب خصم أرباح تجارية و صناعية	'
            'deduction_D1' => 0, // D1 is the code for 'ضرائب خصم أرباح تجارية و صناعية	'
            'document_balance' => 0,
        ];
        $toDate = $request->to_date;
        $documents = collect([]);

        if ($request->clientType && $request->clientId  && $request->to_date) { // filter payments by date
            if ($request->clientType == 'b') {
                $client = BusinessClient::find($request->clientId);
            } else if ($request->clientType == 'p') {
                $client = PersonClient::find($request->clientId);
            } else if ($request->clientType == 'f') {
                $client = ForeignerClient::find($request->clientId);
            }

            if ($client) {
                $purchaseorders = $client->purchaseOrders()->with([
                    'documents' => function ($q) use ($toDate) {
                        $q->where('date', '<', $toDate);
                    }
                ])->get();
            }
        }
        elseif ($request->clientType ) {
            $purchaseorders = PurchaseOrder::with([
                'documents' => function ($q) use ($toDate) {
                    $q->where('date', '<', $toDate);
                }
            ])->where("client_type",$request->clientType)->get();
        }
        else {
            $purchaseorders = PurchaseOrder::with([
                'documents' => function ($q) use ($toDate) {
                    $q->where('date', '<', $toDate);
                }
            ])->get();
        }
                foreach ($purchaseorders as $purchaseorder) {
                    foreach ($purchaseorder->documents as $document) {
                        if ($document->submit_status == 0) // Ignore Not sent documents
                            continue;
                            if ($purchaseorder->client_type =='b') {
                                $temp['supplier'] = $purchaseorder->businessClient->name;
                            } elseif ($purchaseorder->client_type =='p') {
                                $temp['supplier'] = $purchaseorder->personClient->name;
                            } elseif ($purchaseorder->client_type =='f') {
                                $temp['supplier'] = $purchaseorder->foreignerClient->company_name;
                            }
                            else{

                            }
                        $documentTotalwithTaxes = 0;
                        if($this->documentTaxesDeduction($document) == 0) {
                            $documentTotalwithTaxes = $this->documentTotalwithTaxes($document);
                        } else {
                            $documentTotalwithTaxes =  $this->documentTaxesT4($document) + $this->documentTotalwithoutTaxes($document);
                        }
                        $temp['reference'] = $document->document_number;
                        $temp['date'] = $document->date;
                        $temp['totalwithoutTaxes'] = $this->documentTotalwithoutTaxes($document);
                        $temp['totalwithTaxes'] = $documentTotalwithTaxes;
                        $temp['taxes'] = $this->documentTaxesT4($document);
                        $temp['C_Ch_BT'] = 0;
                        $temp['deductionwithout_D1'] = $this->documentTaxesDeduction($document);
                        $temp['deduction_D1'] = 0;
                        $temp['document_balance'] = 0;
                        if($this->documentTaxesDeduction($document) == 0) {
                            foreach ($document->payments as $payment) {
                                $temp['document_balance'] = $this->documentTotalPayments($document);
                                if ($payment->payment_method == 'deduction') {
                                    $paymentDeductions = $payment->deductions()->get();
                                    foreach ($paymentDeductions as $paymentDeduction) {
                                        if ($paymentDeduction->deduction->code == 'D1') { // 'ضرائب خصم أرباح تجارية و صناعية	'
                                            $temp['deduction_D1'] += $paymentDeduction->value;
                                        } else {
                                            $temp['deductionwithout_D1'] += $paymentDeduction->value;
                                        }
                                    }
                                } else { // Bank transfer, Cheque, Cashe
                                    $temp['C_Ch_BT'] += $payment->value;
                                }
                            }
                            $temp['document_balance'] = $temp['totalwithTaxes'] - $temp['document_balance'];
                        } else {
                            $temp['document_balance']  = number_format($temp['totalwithTaxes'] - $this->documentTaxesDeduction($document),2);
                        }
                        $documents->push($temp);
                    }
                }
       
        $sorted = $documents->sortBy('date');
        return json_encode($sorted->values()->all());
    }

    function documentTaxesDeduction ($document) {
        $sign = 1;
        if ($document->type == 'C')
            $sign = -1;

        $rate = floatval($document->items()->get()[0]->rate);

        $totalTaxes = 0;
        $data = 0;
        $total_with_taxes = $this->documentTotalwithoutTaxes($document);

        foreach ($document->items as $documentItem) {
            foreach($documentItem->basicItemData->purchaseOrderTaxes as $taxTest)
                if($taxTest->tax_type  == 4)
                    $data += 1;


            $totalTaxes += $this->itemTaxes($documentItem);
            $client['_documentTaxes'] = 0;
        }
        if($data > 0)
             return   ($total_with_taxes * 0.01);
        else
            return 0;
    }

    function documentTaxesT4 ($document) {
        $sign = 1;
        if ($document->type == 'C')
            $sign = -1;

        $rate = floatval($document->items()->get()[0]->rate);

        $totalTaxes = 0;
        $data = 0;
        $total_with_taxes = $this->documentTotalwithoutTaxes($document);

        foreach ($document->items as $documentItem) {
            foreach($documentItem->basicItemData->purchaseOrderTaxes as $taxTest)
                if($taxTest->tax_type  == 4)
                    $data += 1;


            $totalTaxes += $this->itemTaxes($documentItem);
            $client['_documentTaxes'] = 0;
        }
        if($data > 0)
            $totalTaxes  = $totalTaxes - ($total_with_taxes * 0.01);
        return floatval($totalTaxes / $rate) * $sign;
    }


    // End of Client Document Balances Report

    public function reportAccountNumber()
    {
        $banks = Bank::get();
        $payments = Payment::get();

        $bank_id = [];
        foreach ($payments as $key => $payment) {

            if (isset($payment->purchaseOrder->bank->id)) {
                $bank_id[$key] = $payment->purchaseOrder->bank->id;
            }
        }

        // return $bank_id;

        return view("pages.reports.report_account_number", compact("banks"));
    }


    public function reportAccountNumberAjax(Request $request)
    {

        $payments = Payment::get();
        $startDate = $request->from_date;

        $bank_id = null;
        if (isset($request->bank_id)) {
            $bank_id = $request->bank_id;
        }

        $payments = [];
        if (isset($startDate)) {

            $payments = payment::whereDate("payment_date", ">=", $startDate)
                ->whereHas('bank_transfer', function ($query) use ($bank_id) {

                    return $query->where('bank_id', $bank_id);
                })->get();
        } elseif (isset($endDate)) {
            $payments = payment::whereDate("payment_date", "<=", $endDate)->whereHas('bank_transfer', function ($query) use ($bank_id) {

                return $query->where('bank_id', $bank_id);
            })->get();
        } elseif (isset($endDate) && isset($startDate)) {
            $payments = payment::whereDate("payment_date", ">=", $startDate)
                ->whereDate("payment_date", "<=", $endDate)->whereHas('bank_transfer', function ($query) use ($bank_id) {

                    return $query->where('bank_id', $bank_id);
                })->get();
        } else {
            $payments = payment::whereHas('bank_transfer', function ($query) use ($bank_id) {
                return $query->where('bank_id', $bank_id);
            })->get();
        }


        return view("pages.reports.table_account_number", compact("payments", "bank_id"))->render();
    }


    // Report of Payment Date Bank
    public function paymentDateReport(Request $request)
    {
        //        $issue_date = $request->issue_date;

        return view("pages.reports.paymentDateReport");
    }

    public function paymentDateReportData(Request $request)
    {
        $array = [];
        $issue_date = $request->issue_date;
        //        return $payments = payment::with('document')->with('purchaseOrder')->get();
        //        return $payments = payment::with("purchaseOrder")->get();

        $payments = payment::where("payment_method", "cheque")
            ->whereHas('cheque', function ($query) use ($issue_date) {
            return $query->where('issue_date', '>=', $issue_date);
        })->get();

         $cheques = Cheque::with('payments')->where('issue_date', '>=', $issue_date)->get();

        return view("pages.reports.tablePaymentDateReport", compact('payments',"cheques", "issue_date"))->render();
    }
    // End of Report of Payment Date Bank


} // End Of Controller
