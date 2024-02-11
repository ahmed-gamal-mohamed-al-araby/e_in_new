<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\BankTransfer;
use App\Models\Cheque;
use App\Models\Deduction;
use App\Models\Document;
use App\Models\PaymentDeduction;
use App\Models\Notification;
use App\Models\PurchaseOrder;
use App\Models\Payment;
use App\Models\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Promise\all;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payment::orderBy('payment_date', 'DESC')->paginate(env('PAGINATION_LENGTH', 5));
        return view('pages.payment.index', compact('payments'));
    }

    public function related()
    {
        $payments = [];
        $request = [];
        return view('pages.payment.related', compact('payments', 'request'));
    }

    public function get_related(Request $request)
    {
        if ($request->table_name == 'PO') {
            $purchaseOrderId = PurchaseOrder::select('id')->where('purchase_order_reference', $request->reference)->first();
            if ($purchaseOrderId) {
                $purchaseOrderId = $purchaseOrderId->id;
                $payments = Payment::where('table', $request->table_name)->where('table_id', $purchaseOrderId)->orderBy('id', 'DESC')->get();
            } else {
                $payments = [];
            }
        } else if ($request->table_name == 'D') {
            $documentId = Document::select('id')->where('document_number', $request->reference)->first();

            if ($documentId) {
                $documentId = $documentId->id;
                $payments = Payment::where('table', $request->table_name)->where('table_id', $documentId)->orderBy('id', 'DESC')->get();
            } else {
                $payments = [];
            }
        } else {
            return redirect()->route('payment.related.index');
        }

        $request = $request->except('_token');
        return view('pages.payment.related', compact('payments', 'request'));
    }

    function fetch_data(Request $request)
    {
        $length = request()->length ?? env('PAGINATION_LENGTH', 5);
        $searchContent = request()->search_content ?? '';
        $pageType = request()->page_type;
        // $payments = collect();
        $payments = [];
        if ($request->ajax()) {
            if ($pageType == 'index') {
                if ($length == -1) {
                    $length = Payment::count();
                }
                if (strlen($searchContent)) {
                    $payments = Payment::where('payment_method', 'like', '%' . $searchContent . '%')
                        ->orWhereHas('businessClient', function (Builder $query) use ($searchContent) {
                            return $query->where('name', 'like', '%' . $searchContent . '%');
                        })
                        ->orWhereHas('foreignerClient', function (Builder $query) use ($searchContent) {
                            return $query->where('company_name', 'like', '%' . $searchContent . '%');
                        })
                        ->orWhereHas('personClient', function (Builder $query) use ($searchContent) {
                            return $query->where('name', 'like', '%' . $searchContent . '%');
                        })
                        ->orWhereHas('purchaseOrder', function (Builder $query) use ($searchContent) {
                            return $query->where('purchase_order_reference', 'like', '%' . $searchContent . '%');
                        })
                        ->orWhereHas('document', function (Builder $query) use ($searchContent) {
                            return $query->where('document_number', 'like', '%' . $searchContent . '%');
                        })
                        ->orWhereHas('cheque', function (Builder $query) use ($searchContent) {
                            return $query->where('bank_name', 'like', '%' . $searchContent . '%');
                        })
                        ->orWhereHas('bank_transfer', function (Builder $query) use ($searchContent) {
                            return $query->where('bank_name', 'like', '%' . $searchContent . '%');
                        })
                        ->orderBy('id', 'DESC')->paginate($length);
                } else {
                    $payments = Payment::orderBy('id', 'DESC')->paginate($length);
                }
            }
            return view('pages.payment.pagination_data', compact('payments', 'pageType'))->render();
        }
    } // end of fetch data function

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        $notification_id = request()->query('n_id');
        if ($notification_id) {
            $notification = Notification::find($notification_id);
            if ($notification->view_status == 0 && $notification->user_id != auth()->user()->id && $notification->type == 'n') {
                app('App\Http\Controllers\Notification\NotificationController')->changeViewStatus($notification_id);
            }
        }

        // --------------------- Client --------------------------- //

        // Business client
        if ($payment->client_type == 'b') {
            $client = $payment->businessClient;
        } // Foreigner client
        else if ($payment->client_type == 'f') {
            $client = $payment->foreignerClient;
        } // Person client
        else if ($payment->client_type == 'p') {
            $client = $payment->personClient;
        }
        // --------------------- table --------------------------- //

        $purchaseOrderReference = null;
        // Purchase Order
        if ($payment->table == 'PO')
            $purchaseOrderReference = $payment->purchaseOrder->purchase_order_reference;
        // Document
        $documentNumber = null;
        if ($payment->table == 'D') {
            $document = $payment->document;
            $documentNumber = $document->document_number;
            $purchaseOrderReference = $document->purchaseOrder->purchase_order_reference;
            unset($document);
        }
        // --------------------- Payment Method --------------------------- //

        // Cashe
        if ($payment->payment_method == 'cashe') {
            $paymentMethod = null;
        } // Bank transfer
        else if ($payment->payment_method == 'bank_transfer') {
            $paymentMethod = $payment->bank_transfer;
        } // Cheque
        else if ($payment->payment_method == 'cheque') {
            $paymentMethod = $payment->cheque;
        } // Deduction
        else if ($payment->payment_method == 'deduction') {
            $paymentMethod = $payment->deductions;
        }

        return view('pages.payment.show', compact('payment', 'client', 'purchaseOrderReference', 'documentNumber', 'paymentMethod'));
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $totalAmount = $payment->table == 'PO' ? $this->purchaseOrderTotal($payment->purchaseOrder) : $this->documentTotal($payment->document);
        $totalPayments = $payment->table == 'PO' ? $this->purchaseOrderTotalPayments($payment->purchaseOrder) : $this->documentTotalPayments($payment->document);

        // --------------------- Client --------------------------- //

        // Business client
        if ($payment->client_type == 'b') {
            $client = $payment->businessClient;
        } // Foreigner client
        else if ($payment->client_type == 'f') {
            $client = $payment->foreignerClient;
        } // Person client
        else if ($payment->client_type == 'p') {
            $client = $payment->personClient;
        }
        // --------------------- table --------------------------- //

        $purchaseOrderReference = null;
        $purchaseOrderID = null;
        // Purchase Order
        if ($payment->table == 'PO') {
            $purchaseOrderReference = $payment->purchaseOrder->purchase_order_reference;
            $purchaseOrderID = $payment->purchaseOrder->id;
        }
        // Document
        $documentNumber = null;
        $documentId = null;
        if ($payment->table == 'D') {
            $document = $payment->document;
            $documentNumber = $document->document_number;
            $documentId = $document->id;
            $purchaseOrderReference = $document->purchaseOrder->purchase_order_reference;
            $purchaseOrderID = $document->purchaseOrder->id;
            unset($document);
        }
        // --------------------- Payment Method --------------------------- //

        $deductionNames = [];
        // Cashe
        if ($payment->payment_method == 'cashe') {
            $paymentMethod = null;
        } // Bank transfer
        else if ($payment->payment_method == 'bank_transfer') {
            $paymentMethod = $payment->bank_transfer;
        } // Cheque
        else if ($payment->payment_method == 'cheque') {
            $paymentMethod = $payment->cheque;
        } // Deduction
        else if ($payment->payment_method == 'deduction') {
            $paymentMethod = $payment->deductions;
            foreach ($paymentMethod as $deduction) {
                array_push($deductionNames, $deduction->deduction->name);
            }
        }

        $basicDeductions = Deduction::all();
        return view('pages.payment.edit', compact('payment', 'client', 'purchaseOrderReference', 'purchaseOrderID', 'documentNumber', 'documentId', 'paymentMethod', 'deductionNames', 'basicDeductions', 'totalAmount', 'totalPayments'));
    }

    public function purchaseOrder_create_payment_cashe_cheque_or_bank()
    {
        $cheques = Cheque::all();
        $bankTransfers = BankTransfer::all();
        return view('pages.payment.purchaseOrder.cheque_or_bank', compact('cheques', 'bankTransfers'));
    }

    public function purchaseOrder_create_payment_deduction()
    {
        $deductions = Deduction::all();
        return view('pages.payment.purchaseOrder.deduction', compact('deductions'));
    }

    public function document_create_payment_cashe_cheque_or_bank()
    {
        $cheques = Cheque::all();
        $bankTransfers = BankTransfer::all();
        return view('pages.payment.document.cheque_or_bank', compact('cheques', 'bankTransfers'));
    }

    public function document_create_payment_deduction()
    {
        $deductions = Deduction::all();
        return view('pages.payment.document.deduction', compact('deductions'));
    }

    public function document_store_payment_cashe_cheque_or_bank(Request $request)
    {
        // dump($request->all());

        $basicPaymentData = collect();
        $this->getBasicPaymentData($basicPaymentData, $request->basicData);

        $document_number = Document::where('id', $basicPaymentData['document_id'])->select('document_number')->get()[0]->document_number;

        DB::beginTransaction();

        try {
            if ($basicPaymentData['payment_method'] == 'cashe') {
                $payment_method_id = null;
            } else if ($basicPaymentData['payment_method'] == 'cheque') {
                if ($basicPaymentData->get('cheque_id')) {
                    $payment_method_id = $basicPaymentData['cheque_id'];
                } else {
                    $cheque = Cheque::create([
                        'bank_name' => $basicPaymentData['cheque_bank_name'],
                        'cheque_number' => $basicPaymentData['cheque_number'],
                        'received_date' => $basicPaymentData['cheque_received_date'],
                        'issue_date' => $basicPaymentData['cheque_issue_date'],
                        'deposit_date' => $basicPaymentData['cheque_deposit_date'],
                        'collect_date' => $basicPaymentData['cheque_collect_date'],
                        'bank_id' => $basicPaymentData['bank_id'],
                    ]);
                    $payment_method_id = $cheque->id;
                }
            } else if ($basicPaymentData['payment_method'] == 'bank_transfer') {
                if ($basicPaymentData->get('bankTransfer_id')) {
                    $payment_method_id = $basicPaymentData['bankTransfer_id'];
                } else {
                    $bankTransfer = BankTransfer::create([
                        'bank_name' => $basicPaymentData['bank_transfer_bank_name'],
                        'received_date' => $basicPaymentData['bank_transfer_received_date'],
                        'issue_date' => $basicPaymentData['bank_transfer_issue_date'],
                        'deposit_date' => $basicPaymentData['bank_transfer_deposit_date'],
                        'collect_date' => $basicPaymentData['bank_transfer_collect_date'],
                        'bank_id' => $basicPaymentData['bank_id'],
                    ]);
                    $payment_method_id = $bankTransfer->id;
                }
            }

            // Add payment record
            $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => $basicPaymentData['document_id'],
                'client_type' => $basicPaymentData['client_type'],
                'client_id' => $basicPaymentData['client_id'],
                'payment_method' => $basicPaymentData['payment_method'], // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => $basicPaymentData['payment_date'],
                'value' => $basicPaymentData['total_money'],
            ]);

            // Notification part
            $url = route('payment.show', $payment->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'payments',
                'record_id' => $payment->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة $document_number </a>",
            ]);

            DB::commit();

            $response = array(
                'id' => $payment->id,
            );

            Toastr::success(trans('site.payment_added'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Toastr::error(trans("site.sorry"));
            $response = array(
                'id' => 0,
            );
        }
        return response()->json($response);
    }

    public function purchaseOrder_store_payment_cashe_cheque_or_bank(Request $request)
    {

        $basicPaymentData = collect();
        $this->getBasicPaymentData($basicPaymentData, $request->basicData);
        $PO_reference = PurchaseOrder::where('id', $basicPaymentData['purchaseorder_id'])->select('purchase_order_reference')->get()[0]->purchase_order_reference;
        DB::beginTransaction();

        try {
            if ($basicPaymentData['payment_method'] == 'cashe') {
                $payment_method_id = null;
            } else if ($basicPaymentData['payment_method'] == 'cheque') {
                if ($basicPaymentData->get('cheque_id')) {
                    $payment_method_id = $basicPaymentData['cheque_id'];
                } else {
                    $cheque = Cheque::create([
                        'bank_name' => $basicPaymentData['cheque_bank_name'],
                        'cheque_number' => $basicPaymentData['cheque_number'],
                        'received_date' => $basicPaymentData['cheque_received_date'],
                        'issue_date' => $basicPaymentData['cheque_issue_date'],
                        'deposit_date' => $basicPaymentData['cheque_deposit_date'],
                        'collect_date' => $basicPaymentData['cheque_collect_date'],
                        'bank_id' => $basicPaymentData['bank_id'],
                    ]);
                    $payment_method_id = $cheque->id;
                }
            } else if ($basicPaymentData['payment_method'] == 'bank_transfer') {
                if ($basicPaymentData->get('bankTransfer_id')) {
                    $payment_method_id = $basicPaymentData['bankTransfer_id'];
                } else {
                    $bankTransfer = BankTransfer::create([
                        'bank_name' => $basicPaymentData['bank_transfer_bank_name'],
                        'received_date' => $basicPaymentData['bank_transfer_received_date'],
                        'issue_date' => $basicPaymentData['bank_transfer_issue_date'],
                        'deposit_date' => $basicPaymentData['bank_transfer_deposit_date'],
                        'collect_date' => $basicPaymentData['bank_transfer_collect_date'],
                        'bank_id' => $basicPaymentData['bank_id'],
                    ]);
                    $payment_method_id = $bankTransfer->id;
                }
            }

            // Add payment record
            $payment = Payment::create([
                'table' => 'PO', // ['D', 'PO']
                'table_id' => $basicPaymentData['purchaseorder_id'],
                'client_type' => $basicPaymentData['client_type'],
                'client_id' => $basicPaymentData['client_id'],
                'payment_method' => $basicPaymentData['payment_method'], // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => $basicPaymentData['payment_date'],
                'value' => $basicPaymentData['total_money'],
            ]);

            // Notification part
            $url = route('payment.show', $payment->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'payments',
                'record_id' => $payment->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على أمر شراء $PO_reference </a>",
            ]);

            DB::commit();

            $response = array(
                'id' => $payment->id,
            );

            Toastr::success(trans('site.payment_added'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Toastr::error(trans("site.sorry"));
            $response = array(
                'id' => 0,
            );
        }
        return response()->json($response);
    }

    public function purchaseOrder_store_payment_deduction(Request $request)
    {
        $basicPaymentData = collect();
        $deductions = $request->deductions;
        $this->getBasicPaymentData($basicPaymentData, $request->basicData);
        $PO_reference = PurchaseOrder::where('id', $basicPaymentData['purchaseorder_id'])->select('purchase_order_reference')->get()[0]->purchase_order_reference;

        DB::beginTransaction();

        try {
            // Add payment record
            $payment = Payment::create([
                'table' => 'PO', // ['D', 'PO']
                'table_id' => $basicPaymentData['purchaseorder_id'],
                'client_type' => $basicPaymentData['client_type'],
                'client_id' => $basicPaymentData['client_id'],
                'payment_method' => 'Deduction', // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => null,
                'payment_date' => $basicPaymentData['payment_date'],
                'value' => $basicPaymentData['total_money'],
            ]);

            // Add deductions
            for ($i = 0; $i < $basicPaymentData['deduction_counter']; $i++) {
                PaymentDeduction::create([
                    'deduction_id' => $deductions[$i]['deduction_id'],
                    'value' => $deductions[$i]['deduction_value'],
                    'payment_id' => $payment->id,
                ]);
            }

            // Notification part
            $url = route('payment.show', $payment->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'payments',
                'record_id' => $payment->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على أمر شراء $PO_reference </a>",
            ]);

            DB::commit();

            $response = array(
                'id' => $payment->id,
            );

            Toastr::success(trans('site.payment_added'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Toastr::error(trans("site.sorry"));
            $response = array(
                'id' => 0,
            );
        }
        return response()->json($response);
    }


    public function document_store_payment(Request $request)
    {
        // dd ($request->po);
        //  dd ($request->all());

        $basicPaymentData = collect();
        $documentWithDeductions = [];
        $this->getBasicPaymentData($basicPaymentData, $request->basicData);
        $documentWithDeductions = $this->getDocumentAndItsDeductions($request->deductions);

        $paymentIds = [];

        DB::beginTransaction();

        try {
            if ($basicPaymentData['payment_method'] == 'cashe') {
                $payment_method_id = null;
            }
            else if ($basicPaymentData['payment_method'] == 'cheque') {
                $cheque = Cheque::create([
                    'bank_name' => $basicPaymentData['cheque_bank_name'],
                    'cheque_number' => $basicPaymentData['cheque_number'],
                    'received_date' => $basicPaymentData['cheque_received_date'],
                    'issue_date' => $basicPaymentData['cheque_issue_date'],
                    'deposit_date' => $basicPaymentData['cheque_deposit_date'],
                    'collect_date' => $basicPaymentData['cheque_collect_date'],
                    'bank_id' => $basicPaymentData['bank_id'],
                ]);
                $payment_method_id = $cheque->id;
            } else if ($basicPaymentData['payment_method'] == 'bank_transfer') {
                $bankTransfer = BankTransfer::create([
                    'bank_name' => $basicPaymentData['bank_transfer_bank_name'],
                    'received_date' => $basicPaymentData['bank_transfer_received_date'],
                    'issue_date' => $basicPaymentData['bank_transfer_issue_date'],
                    'deposit_date' => $basicPaymentData['bank_transfer_deposit_date'],
                    'collect_date' => $basicPaymentData['bank_transfer_collect_date'],
                    'bank_id' => $basicPaymentData['bank_id'],
                ]);
                $payment_method_id = $bankTransfer->id;
            }


            // Add documents values
            foreach ($request->documents as $document) {
                if ($document['currentPayment'] != 0) { // ignore payment equal Zero (0)
                    $document_number = Document::where('id', $document['record_id'])->select('document_number')->get()[0]->document_number;
                    // Add payment record
                    $payment = Payment::create([
                        'table' => 'D', // ['D', 'PO']
                        'table_id' => $document['record_id'],
                        'client_type' => $basicPaymentData['client_type'],
                        'client_id' => $basicPaymentData['client_name'],
                        'payment_method' => $basicPaymentData['payment_method'], // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                        'payment_method_id' => $payment_method_id,
                        'payment_date' => $basicPaymentData['payment_date'],
                        'value' => $document['currentPayment'],
                    ]);

                    // Notification part
                    $url = route('payment.show', $payment->id);

                    $notification = Notification::create([
                        'content' => '',
                        'user_id' => auth()->user()->id,
                        'type' => 'n',
                        'table_name' => 'payments',
                        'record_id' => $payment->id,
                    ]);

                    array_push($paymentIds, $payment->id);

                    // set content
                    $notification->update([
                        'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة $document_number </a>",
                    ]);
                }
            }

            $index = 0;
            // Add deductions values
            foreach ($documentWithDeductions as $documentId => $documentWithDeduction) {
                $document_number = Document::where('id', $documentId)->select('document_number')->get()[0]->document_number;

                // Add payment record
                $payment = Payment::create([
                    'table' => 'D', // ['D', 'PO']
                    'table_id' => $documentId,
                    'client_type' => $basicPaymentData['client_type'],
                    'client_id' => $basicPaymentData['client_name'],
                    'payment_method' => 'Deduction', // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                    'payment_method_id' => null,
                    'payment_date' => $basicPaymentData['payment_date'],
                    'value' => $request->documents[$index]['sumOfDeductionValues'],
                ]);

                foreach ($documentWithDeduction as $deduction) {
                    PaymentDeduction::create([
                        'deduction_id' => $deduction['id'],
                        'value' => $deduction['value'],
                        'payment_id' => $payment->id,
                    ]);
                }

                // Notification part
                $url = route('payment.show', $payment->id);

                $notification = Notification::create([
                    'content' => '',
                    'user_id' => auth()->user()->id,
                    'type' => 'n',
                    'table_name' => 'payments',
                    'record_id' => $payment->id,
                ]);

                array_push($paymentIds, $payment->id);

                // set content
                $notification->update([
                    'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة $document_number </a>",
                ]);

                $index++;
            }

            DB::commit();

            $response = array(
                'ids' => $paymentIds,
            );

            Toastr::success(trans('site.payment_added'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Toastr::error(trans("site.sorry"));
            $response = array(
                'ids' => [],
            );
        }
        return response()->json($response);
    }

    public function document_store_payment_deduction(Request $request)
    {
        $basicPaymentData = collect();
        $deductions = $request->deductions;
        $this->getBasicPaymentData($basicPaymentData, $request->basicData);
        $document_number = Document::where('id', $basicPaymentData['document_id'])->select('document_number')->get()[0]->document_number;

        DB::beginTransaction();

        try {
            // Add payment record
            $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => $basicPaymentData['document_id'],
                'client_type' => $basicPaymentData['client_type'],
                'client_id' => $basicPaymentData['client_id'],
                'payment_method' => 'Deduction', // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => null,
                'payment_date' => $basicPaymentData['payment_date'],
                'value' => $basicPaymentData['total_money'],
            ]);

            // Add deductions
            for ($i = 0; $i < $basicPaymentData['deduction_counter']; $i++) {
                PaymentDeduction::create([
                    'deduction_id' => $deductions[$i]['deduction_id'],
                    'value' => $deductions[$i]['deduction_value'],
                    'payment_id' => $payment->id,
                ]);
            }

            // Notification part
            $url = route('payment.show', $payment->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'payments',
                'record_id' => $payment->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة $document_number </a>",
            ]);

            DB::commit();

            $response = array(
                'id' => $payment->id,
            );

            Toastr::success(trans('site.payment_added'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Toastr::error(trans("site.sorry"));
            $response = array(
                'id' => 0,
            );
        }
        return response()->json($response);
    }

    function getBasicPaymentData($basicPaymentData, $requestData)
    {
        // Prepare collection for Payment Data
        $tem = array_map(function ($v) {
            return [$v['name'] => $v['value']];
        }, $requestData);

        foreach ($tem as $mainKey => $object) {
            foreach ($object as $key => $value) {
                $basicPaymentData->put($key, $value);
            }
        }
    }

    function getBasicPaymentDataSpecial($basicPaymentData, $requestData)
    {
        $purchaseorder_id = [];
        $document_id = [];
        $total_money = [];
        // Prepare collection for Payment Data
        $tem = array_map(function ($v) {
            return [$v['name'] => $v['value']];
        }, $requestData);

        foreach ($tem as $mainKey => $object) {
            foreach ($object as $key => $value) {
                $basicPaymentData->put($key, $value);
            }
        }
    }

    function getDocumentAndItsDeductions($deductions)
    {
        $documentWithDeductions = [];
        $deductionTemp = [
            'id' => 0,
            'value' => 0,
        ];

        foreach ($deductions as $deduction) {
            $deductionTemp['id'] = $deduction['deduction_id'];
            $deductionTemp['value'] = $deduction['deduction_value'];

            if (!isset($documentWithDeductions[$deduction['document_id']]))
                $documentWithDeductions[$deduction['document_id']] = [];
            array_push($documentWithDeductions[$deduction['document_id']], $deductionTemp);
        }
        return $documentWithDeductions;
    }

    public function update(Request $request, $id)
    {
        $basicPaymentData = collect();
        $deductions = $request->deductions;
        $this->getBasicPaymentData($basicPaymentData, $request->basicData);
        $payment = Payment::find($id);
        if (!$payment)
            return;

        $tableId = $payment->table == 'D' ? $basicPaymentData['document_id'] : $basicPaymentData['purchaseorder_id'];
        $notificationReference = $payment->table == 'D' ? Document::where('id', $basicPaymentData['document_id'])->select('document_number')->get()[0]->document_number : PurchaseOrder::where('id', $basicPaymentData['purchaseorder_id'])->select('purchase_order_reference')->get()[0]->purchase_order_reference;

        DB::beginTransaction();

        try {

            $paymentMethodId = null;
            // If payment method is changed in (cashe, cheque, bank_transfer)
            if ($payment->payment_method != 'deduction') {
                if ($payment->payment_method != $basicPaymentData['payment_method']) {
                    // delete old payment method
                    if ($payment->payment_method == 'bank_transfer' && isset($payment->bank_transfer)) {
                        $payment->bank_transfer->delete();
                    } elseif ($payment->payment_method == 'cheque') {
                        $payment->cheque->delete();
                    }

                    // Add new payment method
                    if ($basicPaymentData['payment_method'] == 'bank_transfer') {
                        $paymentMethodId = BankTransfer::create([
                            'bank_name' => $basicPaymentData['bank_transfer_bank_name'],
                            'received_date' => $basicPaymentData['bank_transfer_received_date'],
                            'issue_date' => $basicPaymentData['bank_transfer_issue_date'],
                            'deposit_date' => $basicPaymentData['bank_transfer_deposit_date'],
                            'collect_date' => $basicPaymentData['bank_transfer_collect_date'],
                            'bank_id' => $basicPaymentData['bank_id'],
                        ]);
                        $paymentMethodId = $paymentMethodId->id;
                    } elseif ($basicPaymentData['payment_method'] == 'cheque') {
                        $paymentMethodId = Cheque::create([
                            'bank_name' => $basicPaymentData['cheque_bank_name'],
                            'cheque_number' => $basicPaymentData['cheque_number'],
                            'received_date' => $basicPaymentData['cheque_received_date'],
                            'issue_date' => $basicPaymentData['cheque_issue_date'],
                            'deposit_date' => $basicPaymentData['cheque_deposit_date'],
                            'collect_date' => $basicPaymentData['cheque_collect_date'],
                            'bank_id' => $basicPaymentData['bank_id'],
                        ]);
                        $paymentMethodId = $paymentMethodId->id;
                    }
                } else { // if payment method not changed but may be updated
                    if ($payment->payment_method == 'bank_transfer') {
                        $payment->bank_transfer->update([
                            'bank_name' => $basicPaymentData['bank_transfer_bank_name'],
                            'received_date' => $basicPaymentData['bank_transfer_received_date'],
                            'issue_date' => $basicPaymentData['bank_transfer_issue_date'],
                            'deposit_date' => $basicPaymentData['bank_transfer_deposit_date'],
                            'collect_date' => $basicPaymentData['bank_transfer_collect_date'],
                            'bank_id' => $basicPaymentData['bank_id'],
                        ]);
                        $paymentMethodId = $payment->bank_transfer->id;
                    } elseif ($payment->payment_method == 'cheque') {
                        $paymentMethodId = $payment->cheque->update([
                            'bank_name' => $basicPaymentData['cheque_bank_name'],
                            'cheque_number' => $basicPaymentData['cheque_number'],
                            'received_date' => $basicPaymentData['cheque_received_date'],
                            'issue_date' => $basicPaymentData['cheque_issue_date'],
                            'deposit_date' => $basicPaymentData['cheque_deposit_date'],
                            'collect_date' => $basicPaymentData['cheque_collect_date'],
                            'bank_id' => $basicPaymentData['bank_id'],
                        ]);
                        $paymentMethodId = $payment->cheque->id;
                    }
                }
            } else { //  If payment method is deduction
                $allOldDeductions = $payment->deductions()->pluck('id')->toArray();
                $deductionIds = array_filter(array_map(function ($deduction) {
                    return $deduction['record_id'];
                }, $deductions));

                $deletedDeductionIds = array_diff($allOldDeductions, $deductionIds);

                // Delete deleted deductions
                PaymentDeduction::whereIn('id', $deletedDeductionIds)->delete();

                // Update or Add new deduction
                for ($i = 0; $i < $basicPaymentData['deduction_counter']; $i++) {
                    if ($deductions[$i]['record_id'] == null) {
                        PaymentDeduction::create([
                            'deduction_id' => $deductions[$i]['deduction_id'],
                            'value' => $deductions[$i]['deduction_value'],
                            'payment_id' => $payment->id,
                        ]);
                    } else {
                        PaymentDeduction::where('id', $deductions[$i]['record_id'])->update([
                            'deduction_id' => $deductions[$i]['deduction_id'],
                            'value' => $deductions[$i]['deduction_value'],
                            'payment_id' => $payment->id,
                        ]);
                    }
                }
            }

            $paymentMethod = $payment->payment_method != 'deduction' ? $basicPaymentData['payment_method'] : 'deduction';

            // Add payment record
            $payment->update([
                'table_id' => $tableId,
                'client_type' => $basicPaymentData['client_type'],
                'client_id' => $basicPaymentData['client_id'],
                'payment_method' => $paymentMethod, // ['cashe', 'bank_transfer', 'cheque', 'deduction']
                'payment_method_id' => $paymentMethodId,
                'payment_date' => $basicPaymentData['payment_date'],
                'value' => $basicPaymentData['total_money'],
            ]);

            // Notification part
            $url = route('payment.show', $payment->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'payments',
                'record_id' => $payment->id,
            ]);

            $documentOrPO = $payment->table == 'D' ? 'وثيقة' : 'امر شراء';

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بتعديل دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على $documentOrPO $notificationReference </a>",
            ]);

            DB::commit();

            $response = array(
                'id' => $payment->id,
            );
            Toastr::success(trans('site.payment_success_edit'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Toastr::error(trans("site.sorry"));
            $response = array(
                'id' => 0,
            );
        }
        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        // dd($request);
        try {
            $data = Payment::findOrFail($request->payment_id);
            $deletedPayment = clone $data;

            if ($data->payment_method == 'cheque') {
                $data->cheque()->delete();
            } else if ($data->payment_method == 'bank_transfer') {
                $data->bank_transfer()->delete();
            }
            $data->delete();
            // Notification part
            Notification::where('table_name', 'payments')->where('record_id', $data->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'payments',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف دفع ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Payment reference: $deletedPayment->table, Payment method: $deletedPayment->payment_method, Payment date: $deletedPayment->payment_date,  Payment value: " . number_format($deletedPayment->value, 2) . "</div>",
            ]);

            Toastr::success(trans('site.payment_success_deleted'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans('site.payment_delete_error'), trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
        }
        return redirect()->route('payment.index');
    }

    public function permanentDelete(Request $request)
    {
        $errorMessage = '';
        $status = null;
        DB::beginTransaction();
        try {
            $data = Payment::findOrFail($request->payment_id);
            $deletedPayment = clone $data;

            PaymentDeduction::where('payment_id', $request->payment_id)->delete(); // delete all related to payment_id in deduction
            if ($data->payment_method == 'cheque') {
                $data->cheque()->delete();
                Payment::where('payment_method', 'cheque')->where('payment_method_id', $data->payment_method_id)->delete(); // delete all payments with the same payment method and payment method ID
                $status = true;
            } else if ($data->payment_method == 'bank_transfer') {
                $data->bank_transfer()->delete();
                Payment::where('payment_method', 'bank_transfer')->where('payment_method_id', $data->payment_method_id)->delete(); // delete all payments with the same payment method and payment method ID
                $status = true;
            }
            $data->delete();
            $status = true;
            // Notification part
            Notification::where('table_name', 'payments')->where('record_id', $data->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'payments',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف دفع ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Payment reference: $deletedPayment->table, Payment method: $deletedPayment->payment_method, Payment date: $deletedPayment->payment_date,  Payment value: " . number_format($deletedPayment->value, 2) . "</div>",
            ]);
            DB::commit();
            Toastr::success(trans('site.payment_success_deleted'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[0] == 23000)
                $errorMessage = $e->getMessage();
            else
                $errorMessage = 'DB error';
        }
        return json_encode([
            'status' => $status,
            'errorMessage' => $errorMessage,
        ]);
    }

    public function documentTotal($document)
    {
        $rate = floatval($document->items()->get()[0]->rate);
        return floatval($document->items()->sum('total_amount') / $rate); // - $document->extra_invoice_discount);
        // return floatval($document->items()->sum('total_amount') - $document->extra_invoice_discount);
    }

    public function documentTotalPayments($document)
    {
        return floatval($document->payments()->sum('value'));
    }

    public function purchaseOrderTotal($purchaseOrder)
    {
        return floatval($purchaseOrder->items()->sum('total_amount')); // - $document->extra_invoice_discount);
        // return floatval($purchaseOrder->items()->sum('total_amount'));
    }

    public function purchaseOrderTotalPayments($purchaseOrder)
    {
        return floatval($purchaseOrder->payments()->sum('value'));
    }

    public function purchaseOrderPaymentDetails(Request $request)
    {
        $purchaseOrder = PurchaseOrder::find($request->id);
        $result = collect();
        $result->put('totalAmount', $this->purchaseOrderTotal($purchaseOrder));
        $result->put('totalPayments', $this->purchaseOrderTotalPayments($purchaseOrder));
        return json_encode($result);
    }

    public function documentPaymentDetails(Request $request)
    {
        $document = Document::find($request->id);
        $purchase_order = PurchaseOrder::find($request->id);
        $result = collect();
        $result->put('totalAmount', $this->documentTotal($document));
        $result->put('totalPayments', $this->documentTotalPayments($document));
        $result->put('purchaseOrder', $purchase_order);
        return json_encode($result);
    }

    public function multi_document_payment()
    {
        $deductions = Deduction::all();
        return view('pages.payment.document.multi_document', compact('deductions'));
    }

    public function storeFile(Request $request)
    {
        //        return ($request->all());

        $newFileName = '';

        // Store file
        if ($request->hasFile('file')) { // $request->hasFile('file')

            $file = $request->file('file');
            // the name of file purchase_order_document_Auth::id_PO::id.extension
            $newFileName = time() . '.' . $request->file('file')->getClientOriginalExtension();
            $file->move(public_path('payment/files'), $newFileName);
        }

        if ($request->payment_id) {
            $payment = Payment::find($request->payment_id);

            if ($request->hasFile('file')) { // $request->hasFile('file')
                if ($payment->file) {
                    // $oldFilePath = public_path("payment/files/$payment->file");
                    $oldFilePath = url("payment/files/$payment->file");
                    if (file_exists($oldFilePath))
                        unlink($oldFilePath);
                }

                $payment->update(['file' => $newFileName]);
            }
        } else { // used in multi payment

            foreach (json_decode($request->payment_ids) as $key => $payment_id) {
                $payment = Payment::find($payment_id);

                if ($newFileName) { // $request->hasFile('file')
                    if ($payment->file) {
                        // $oldFilePath = public_path("payment/files/$payment->file");
                        $oldFilePath = url("payment/files/$payment->file");
                        if (file_exists($oldFilePath))
                            unlink($oldFilePath);
                    }

                    $payment->update(['file' => $newFileName]);
                }
            }
        }

        return true;
    }
}
