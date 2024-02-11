<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Bank;
use App\Models\BusinessClient;
use App\Models\Country;
use App\Models\Deduction;
use App\Models\ForeignerClient;
use App\Models\Item;
use App\Models\Notification;
use App\Models\PersonClient;
use App\Models\ProductUnit;
use App\Models\Project;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{


    public $fileName = null;

    public function downloadExcel()
    {
        return Storage::download('public/excel.xlsx');
    }


    public function index()
    {

        $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 1)->orderBy('id', 'DESC')->paginate(env('PAGINATION_LENGTH', 5));
        $banks = Bank::all();
        $purchaseOrderTotal = [];
        $totalSumatiom = 0;
        foreach ($purchaseorders as $purchaseorder) {
            $totalSumatiom = 0;
            foreach ($purchaseorder->items as $item)
                $totalSumatiom += $item->total_amount;
            array_push($purchaseOrderTotal, $totalSumatiom);
        }

        return view('pages.purchaseorders.index', compact('purchaseorders', 'banks', 'purchaseOrderTotal'));
    } // end of index


    public function archiveIndex()
    {
        $purchaseOrderTotal = [];
        $totalSumatiom = 0;
        $purchaseorders = PurchaseOrder::where('archive', 1)->where('approved', 1)->orderBy('id', 'DESC')->paginate(env('PAGINATION_LENGTH', 5));
        foreach ($purchaseorders as $purchaseorder) {
            $totalSumatiom = 0;
            foreach ($purchaseorder->items as $item)
                $totalSumatiom += $item->total_amount;
            array_push($purchaseOrderTotal, $totalSumatiom);
        }
        return view('pages.purchaseorders.archive', compact('purchaseorders', 'purchaseOrderTotal'));
    }

    public function waitingApproveIndex()
    {
        $purchaseOrderTotal = [];
        $totalSumatiom = 0;
        $purchaseorders = PurchaseOrder::where('approved', 0)->where('archive', 0)->orderBy('id', 'DESC')->paginate(env('PAGINATION_LENGTH', 5));
        foreach ($purchaseorders as $purchaseorder) {
            $totalSumatiom = 0;
            foreach ($purchaseorder->items as $item)
                $totalSumatiom += $item->total_amount;
            array_push($purchaseOrderTotal, $totalSumatiom);
        }
        return view('pages.purchaseorders.waiting_approve', compact('purchaseorders', 'purchaseOrderTotal'));
    }

    function fetch_data(Request $request)
    {
        $purchaseOrderTotal = [];
        $totalSumatiom = 0;
        $length = request()->length ?? env('PAGINATION_LENGTH', 5);
        $searchContent = request()->search_content ?? '';
        $pageType = request()->page_type;
        $purchaseorders = [];
        if ($request->ajax()) {
            $businessClientIds = BusinessClient::where('name', 'like', '%' . $searchContent . '%')->pluck('id')->toArray(); // b
            $foreignerClientIds = ForeignerClient::where('company_name', 'like', '%' . $searchContent . '%')->pluck('id')->toArray(); // f
            $personClientIds = PersonClient::where('name', 'like', '%' . $searchContent . '%')->pluck('id')->toArray(); // p
            if ($pageType == 'index') {
                if ($length == -1) {
                    $length = PurchaseOrder::where('approved', 1)->count();
                }
                if (strlen($searchContent)) {
                    $purchaseorders = PurchaseOrder::where('approved', 1)->where('purchase_order_reference', 'like', '%' . $searchContent . '%')
                        ->when($businessClientIds, function ($q) use ($businessClientIds) {
                            return $q->orWhere('client_type', 'b')->whereIn('client_id', $businessClientIds);
                        })
                        ->when($foreignerClientIds, function ($q) use ($foreignerClientIds) {
                            return $q->orWhere('client_type', 'f')->whereIn('client_id', $foreignerClientIds);
                        })
                        ->when($personClientIds, function ($q) use ($personClientIds) {
                            return $q->orWhere('client_type', 'p')->whereIn('client_id', $personClientIds);
                        })
                        ->orWhere('purchase_order_reference', 'like', '%' . $searchContent . '%')
                        ->orderBy('id', 'DESC')->paginate($length);
                } else {
                    $purchaseorders = PurchaseOrder::where('approved', 1)->orderBy('id', 'DESC')->paginate($length);
                }
            } else if ($pageType == 'archived') {
                if ($length == -1) {
                    $length = PurchaseOrder::where('archive', 1)->count();
                }
                if (strlen($searchContent)) {
                    $purchaseorders = PurchaseOrder::where('archive', 1)
                        ->where(function ($query) use ($searchContent) {
                            return $query->where('purchase_order_reference', 'like', '%' . $searchContent . '%')
                                ->orWhere('purchase_order_reference', 'like', '%' . $searchContent . '%');
                        })
                        ->when($businessClientIds, function ($q) use ($businessClientIds) {
                            return $q->orWhere('client_type', 'b')->whereIn('client_id', $businessClientIds);
                        })
                        ->when($foreignerClientIds, function ($q) use ($foreignerClientIds) {
                            return $q->orWhere('client_type', 'f')->whereIn('client_id', $foreignerClientIds);
                        })
                        ->when($personClientIds, function ($q) use ($personClientIds) {
                            return $q->orWhere('client_type', 'p')->whereIn('client_id', $personClientIds);
                        })
                        ->orderBy('id', 'DESC')->paginate($length);
                } else {
                    $purchaseorders = PurchaseOrder::where('archive', 1)->orderBy('id', 'DESC')->paginate($length);
                }
            } else if ($pageType == 'waiting_approve') {
                if ($length == -1) {
                    $length = PurchaseOrder::where('archive', 0)->where('approved', 0)->count();
                }
                if (strlen($searchContent)) {
                    $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 0)
                        ->where(function ($query) use ($searchContent) {
                            return $query->where('purchase_order_reference', 'like', '%' . $searchContent . '%')
                                ->orWhere('purchase_order_reference', 'like', '%' . $searchContent . '%');
                        })
                        ->when($businessClientIds, function ($q) use ($businessClientIds) {
                            return $orWhere('client_type', 'b')->whereIn('client_id', $businessClientIds);
                        })
                        ->when($foreignerClientIds, function ($q) use ($foreignerClientIds) {
                            return $q->orWhere('client_type', 'f')->whereIn('client_id', $foreignerClientIds);
                        })
                        ->when($personClientIds, function ($q) use ($personClientIds) {
                            return $q->orWhere('client_type', 'p')->whereIn('client_id', $personClientIds);
                        })
                        ->orderBy('id', 'DESC')->paginate($length);
                } else {
                    $purchaseorders = PurchaseOrder::where('archive', 0)->where('approved', 0)->orderBy('id', 'DESC')->paginate($length);
                }
            }
            foreach ($purchaseorders as $purchaseorder) {
                $totalSumatiom = 0;
                foreach ($purchaseorder->items as $item)
                    $totalSumatiom += $item->total_amount;
                array_push($purchaseOrderTotal, $totalSumatiom);
            }

            return view('pages.purchaseorders.pagination_data', compact('purchaseorders', 'pageType', 'purchaseOrderTotal'))->render();
        }
    } // end of fetch data function


    public function create()
    {

        $projects=Project::all();
        $deduction = Deduction::where('deductionType_id', 3)->get();

        $productUnits = ProductUnit::where('approved', 1)->select('code', 'name_ar')->get();
        $countries = Country::pluck('name', 'id');
        return view('pages.purchaseorders.create', compact('countries', 'productUnits','deduction','projects'));

    } // end of create

    public function storeFile(Request $request)
    {

        $POId = $request->PO_id;
        if ($request->file('file')) {
            $purchaseorder_document = $request->file('file');
            // the name of file purchase_order_document_Auth::id_PO::id.extension
            $newFileName = time() . '.' . $request->file('file')->getClientOriginalExtension();
            $purchaseorder_document->move(public_path('uploads/purchase_order_document'), $newFileName);
            $purchaseOrder = PurchaseOrder::find($request->PO_id);
            $purchaseOrder->update(['purchaseorder_document' => $newFileName]);
        }
        return true;
    }

    public function store(Request $request)
    {

        $purchaseOrderItems = collect();
        $basicPurchaseOrderData = collect();

//         return ($request->all());
        // Prepare collection for Invoice Data
        $basicData = array_map(function ($arr) {
            return [$arr['name'] => $arr['value']];
        }, $request->basicData);

        foreach ($basicData as $mainKey => $object) {
            foreach ($object as $key => $value) {
                $basicPurchaseOrderData->put($key, $value);
            }
        }

        $basicPurchaseOrderData->pull('items_counter');

        $basicPurchaseOrderData = $basicPurchaseOrderData->toArray();
        // Start transaction
        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::create($basicPurchaseOrderData);
            $purchaseOrderItems->put('items', $request->items);
            PoItemController::set_purchase_order_id($purchaseOrder->id);
            PoItemController::store($purchaseOrderItems);


            // Notification part
            $url = route('show_purchaseorder_approve', $purchaseOrder->id);


            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'purchase_orders',
                'record_id' => $purchaseOrder->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ امر شراء ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$purchaseOrder->purchase_order_reference</a>",
            ]);

            DB::commit();
            // return purchaseOrder ID
            Toastr::success(trans('site.purchase_order_success_added'), trans('site.success'));
            return $purchaseOrder->id;
        } catch (\Illuminate\Database\QueryException $e) {
            dump($e->getMessage());
            DB::rollBack();
            Toastr::error(trans("site.sorry"));
            return 'null';
        }
    } // end of store

    public function returnBankData($id)
    {
        echo json_encode(Bank::where('bank_code', $id)->where('approved', 1)->get());
    }

    public function returnPOData($purchase_order_reference)
    {
        $po = PurchaseOrder::where('purchase_order_reference', $purchase_order_reference)->where('approved', 1)->first();
        $bank = $po->first()->bank()->first();
        $result = [$po, $bank];
        return json_encode($result);
    }

    public function returnPOProductData(Request $request)
    {
        $po = PurchaseOrder::where('purchase_order_reference', $request->purchase_order_reference)->where('approved', 1)->first();
        $items = collect();
        $POType = $po->type;
        $item = [];
        foreach ($po->items as $_item) {
            $item['id'] = $_item->id;
            $item['description'] = $_item->description;
            $item['internal_code'] = $_item->product->internal_code;
            // $item['type'] = $po->type;
            $items->push($item);
        }
        $data = [
            'items' => $items,
            'type' => $POType
        ];
        return json_encode($data);
    }
    public function confirmUpdateItemQuantity(Request $request)
    {
        $POItem = Item::find($request->item_id_modal);
        $newQnt=$POItem->quantity+$request->quantity_difference;
        $POItem->update([
            'quantity' => $newQnt
        ]);
        return 1;
    }
    public function returnPOProductFullData(Request $request)
    {
        $po = PurchaseOrder::where('purchase_order_reference', $request->purchase_order_reference)->where('approved', 1)->first();
        $items = collect();
        $POType = $po->type;
        $item = [];
        foreach ($po->items as $_item) {
            $item['id'] = $_item->id;
            $item['description'] = $_item->description;
            $item['internal_code'] = $_item->product->internal_code;
            $POItem = Item::find($_item->id);
            $item['PO_item']['data'] = $POItem;
            $item['PO_item']['taxes'] = $POItem->purchaseOrderTaxes;
            $item['PO_item']['product'] = $POItem->product;

            $documentItemQuantities = 0;
            $documentItemPrices = 0;

            /* Get all available quantity and price
            * I => Invoice is added to used quantity and item price.
            * D => Debit note is added to used quantity and item price.
            * C => Credit note is subtracted from used quantity and item price.
            */
            foreach ($POItem->documentItems()->get() as $documentItem) {
                if ($documentItem->document->type == 'I' || $documentItem->document->type == 'D') {
                    $documentItemQuantities += $documentItem->quantity;
                    $documentItemPrices += $documentItem->item_price;
                } else if ($documentItem->document->type == 'C') {
                    $documentItemQuantities -= $documentItem->quantity;
                    $documentItemPrices -= $documentItem->item_price;
                }
            }

            $item['PO_item']['documentItemQuantities'] = $documentItemQuantities;
            $item['PO_item']['documentItemPrices'] = $documentItemPrices;

            $items->push($item);
        }
        $data = [
            'items' => $items,
            'type' => $POType
        ];
        return json_encode($data);
    }

    public function returnSelectedItemData($item_id)
    {
        $item = Item::find($item_id);
        $taxes = $item->purchaseOrderTaxes;
        $result = collect();
        $result->put('item', $item);
        $result->put('taxes', $taxes);
        $result->put('product', $item->product);

        $documentItemQuantities = 0;
        $documentItemPrices = 0;

        /* Get all available quantity and price
        * I => Invoice is added to used quantity and item price.
        * D => Debit note is added to used quantity and item price.
        * C => Credit note is subtracted from used quantity and item price.
        */
        foreach ($item->documentItems()->get() as $documentItem) {
            if ($documentItem->document->type == 'I' || $documentItem->document->type == 'D') {
                $documentItemQuantities += $documentItem->quantity;
                $documentItemPrices += $documentItem->item_price;
            } else if ($documentItem->document->type == 'C') {
                $documentItemQuantities -= $documentItem->quantity;
                $documentItemPrices -= $documentItem->item_price;
            }
        }

        // $result->put('documentItemQuantities', $item->documentItems()->sum('quantity'));
        $result->put('documentItemQuantities', $documentItemQuantities);
        // $result->put('documentItemPrices', $item->documentItems()->sum('item_price'));
        $result->put('documentItemPrices', $documentItemPrices);
        return json_encode($result);
    }

    public function show(PurchaseOrder $purchaseorder)
    {
        $productUnits = ProductUnit::where('approved', 1)->select('code', 'name_ar')->get();
        $client = null;
        if ($purchaseorder->client_type == 'b')
            $client = BusinessClient::find($purchaseorder->client_id);
        else if ($purchaseorder->client_type == 'p')
            $client = PersonClient::find($purchaseorder->client_id);
        else if ($purchaseorder->client_type == 'f')
            $client = ForeignerClient::find($purchaseorder->client_id);

        return view('pages.purchaseorders.show', compact('purchaseorder', 'client', 'productUnits'));
    }

    public function edit(PurchaseOrder $purchaseorder)
    {
        $projects=Project::all();

        $deduction = Deduction::where('deductionType_id', 3)->get();
        $productUnits = ProductUnit::where('approved', 1)->select('code', 'name_ar')->get();
        $client = null;
        // Business client
        if ($purchaseorder->client_type == 'b') {
            $client = $purchaseorder->businessClient;
        } // Foreigner client
        else if ($purchaseorder->client_type == 'f') {
            $client = $purchaseorder->foreignerClient;
        } // Person client
        else if ($purchaseorder->client_type == 'p') {
            $client = $purchaseorder->personClient;
        }

        $countries = Country::pluck('name', 'id');
        $banks = Bank::all();
        return view('pages.purchaseorders.edit', compact('projects','deduction','purchaseorder', 'banks', 'countries', 'client','productUnits'));
    }

    public function update(Request $request, PurchaseOrder $purchaseorder)
    {
        $request_data = $request->all();

        if ($request->hasFile('purchaseorder_document')) {
            // File::delete('uploads/purchase_order_document/' . $purchaseorder->purchaseorder_document);
            // $oldFilePath = public_path('uploads/purchase_order_document/' . $purchaseorder->purchaseorder_document);
            $oldFilePath = url('uploads/purchase_order_document/' . $purchaseorder->purchaseorder_document);
            if (file_exists($oldFilePath))
                unlink($oldFilePath);
            $purchaseorder_document = $request->file('purchaseorder_document');
            $purchaseorder_document_new_name = time() . '.' . $purchaseorder_document->getClientOriginalExtension();
            $purchaseorder_document->move(public_path('uploads/purchase_order_document'), $purchaseorder_document_new_name);
            $request_data['purchaseorder_document'] = $purchaseorder_document_new_name;
        }

        $purchaseorder->update($request_data);
        // Notification part
        $url = route('show_purchaseorder_approve', $purchaseorder->id);

        $purchaseorder->update([
            'approved' => 0,
        ]);

        Notification::where('table_name', 'purchase_orders')->where('record_id', $purchaseorder->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'a',
            'table_name' => 'purchase_orders',
            'record_id' => $purchaseorder->id,
        ]);

        // set content
        $notification->update([
            'content' => auth()->user()->username . ' قام بتعديل امر الشراء ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$purchaseorder->purchase_order_reference</a>",
        ]);

        Toastr::success(trans('site.updated_successfully'), trans('site.success'));
        return redirect()->route('purchaseorders.index');
    } // end of update

    public function purchaseorder_archive(Request $request)
    {
        // dd($request->purchaseorder_id);
        $errorMessage = '';
        $status = null;
        $purchaseorder = PurchaseOrder::findOrFail($request->purchaseorder_id);
        try {
            $purchaseorder->update([
                'archive' => true,
            ]);
            $status = true;
        } catch (\Illuminate\Database\QueryException $e) { // Handle integrity constraint violation
            // $availableToDelete = false;
            if ($e->errorInfo[0] == 23000) {
                // $errorMessage = '';
                $errorMessage = $e->getMessage();
            } else {
                $errorMessage = 'DB error';
            }
        }
        // Toastr::success(trans('site.client_archive_success'), trans('site.success'));
        // return back();
        return json_encode([
            'status' => $status,
            'errorMessage' => $errorMessage,
        ]);
    }

    public function purchaseorder_restore(Request $request)
    {
        // dd($request->purchaseorder_id);
        $errorMessage = '';
        $status = null;
        $purchaseorder = PurchaseOrder::findOrFail($request->purchaseorder_id);

        try {
            $purchaseorder->update([
                'archive' => false,
            ]);
            $status = true;
        } catch (\Illuminate\Database\QueryException $e) {               // Handle integrity constraint violation
            if ($e->errorInfo[0] == 23000) {
                $errorMessage = $e->getMessage();
            } else {
                $errorMessage = 'DB error';
            }
        }

        return json_encode([                                            //  Error Response
            'status' => $status,
            'errorMessage' => $errorMessage,
        ]);
    }

    public function permanent_delete(Request $request)
    {
        // dd($request->purchaseorder_id);
        $errorMessage = '';
        $status = null;
        try {
            $data = PurchaseOrder::findOrFail($request->purchaseorder_id);
            $deletedPurchaseOrder = clone $data;

            $data->delete();
            $status = true;
            // Notification part
            Notification::where('table_name', 'purchase_orders')->where('record_id', $deletedPurchaseOrder->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'purchase_orders',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف أمر شراء ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Purchase Order reference: $deletedPurchaseOrder->purchase_order_reference</div>",
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                $errorMessage = $e->getMessage();
            else
                $errorMessage = 'DB error';
        }

        return json_encode([                                            //  Error Response
            'status' => $status,
            'errorMessage' => $errorMessage,
        ]);

    } // end of permanent delete

    public function check_purchase_order_reference(Request $request)
    {
        if ($request->id)
            return PurchaseOrder::where('purchase_order_reference', $request->purchase_order_reference)->where('id', '<>', $request->id)->count();
        else
            return PurchaseOrder::where('purchase_order_reference', $request->purchase_order_reference)->count();
    }

    public function returnProductData($id)
    {
        echo json_encode(DB::table('products')->where('internal_code', $id)->where('approved', 1)->get());
    }

    public function showForApprove(Request $request, $id)
    {
        $productUnits = ProductUnit::where('approved', 1)->select('code', 'name_ar')->get();
        $purchaseorder = PurchaseOrder::findOrFail($id);
        $client = null;
        if ($purchaseorder->client_type == 'b')
            $client = BusinessClient::find($purchaseorder->client_id);
        else if ($purchaseorder->client_type == 'p')
            $client = PersonClient::find($purchaseorder->client_id);
        else if ($purchaseorder->client_type == 'f')
            $client = ForeignerClient::find($purchaseorder->client_id);
        $notification = Notification::findOrFail($request->query('n_id'));

        return view('pages.purchaseorders.approve-show', compact('purchaseorder', 'notification', 'client', 'productUnits'));
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

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->update([
            'approved' => 1,
        ]);

        $url = route('purchaseorders.show', $purchaseOrder->id);

        Notification::where('table_name', 'purchase_orders')->where('record_id', $purchaseOrder->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'table_name' => 'purchase_orders',
            'record_id' => $purchaseOrder->id,
        ]);

        // set content
        $notification->update([
            'view_status' => 1,
            'content' => auth()->user()->username . ' وافق على إنشاء/تعديل امر الشراء' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url\">$purchaseOrder->purchase_order_reference</a>",
        ]);

        Toastr::success(trans('site.approved_success'), trans('site.success'));
        return redirect()->route('purchaseorders.index');
    }

    public function showAddItemsViaExcel($PO_id)
    {
        $purchaseorder = PurchaseOrder::findOrFail($PO_id);
        $productUnits = ProductUnit::where('approved', 1)->select('code', 'name_ar')->get();
        $client = null;
        if ($purchaseorder->client_type == 'b')
            $client = BusinessClient::find($purchaseorder->client_id);
        else if ($purchaseorder->client_type == 'p')
            $client = PersonClient::find($purchaseorder->client_id);
        else if ($purchaseorder->client_type == 'f')
            $client = ForeignerClient::find($purchaseorder->client_id);
        return view('pages.purchaseorders.show_addItemViaExcel', compact('purchaseorder', 'client', 'productUnits'));
    }


    public function addItemsViaExcel(Request $request)
    {
        $purchaseOrder = PurchaseOrder::find($request->PO_id);
        $purchaseOrderItems = collect();

        // if purchase order not founded
        if (!$purchaseOrder) {
            Toastr::error(trans("site.sorry"));
            return 'null';
        }

        // Start transaction
        DB::beginTransaction();
        try {
            $purchaseOrderItems->put('items', $request->items);
            PoItemController::set_purchase_order_id($purchaseOrder->id);
            PoItemController::store($purchaseOrderItems);

            // Notification part
            $url = route('show_purchaseorder_approve', $purchaseOrder->id);

            $purchaseOrder->update([
                'approved' => 0,
            ]);

            Notification::where('table_name', 'purchase_orders')->where('record_id', $purchaseOrder->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'purchase_orders',
                'record_id' => $purchaseOrder->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بتعديل امر الشراء ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$purchaseOrder->purchase_order_reference</a>",
            ]);

            DB::commit();

            Toastr::success(trans('site.updated_successfully'), trans('site.success'));

            // return purchaseOrder ID
            return $purchaseOrder->id;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Toastr::error(trans("site.sorry"));
            return 'null';
        }
    }

    public function getDocumentFromPurchaseOrder(Request $request)
    {
        $purchaseOrder = PurchaseOrder::where('purchase_order_reference', $request->purchase_order_reference)->where('approved', 1)->first();
        if ($purchaseOrder)
            return json_encode($purchaseOrder->documents()->select('id', 'document_number')->get());
        else
            return [];
    }

    public function getDocumentFromPurchaseOrderByPOID(Request $request)
    {
        $purchaseOrder = PurchaseOrder::where('id', $request->id)->where('approved', 1)->first();
        if ($purchaseOrder)
            return json_encode($purchaseOrder->documents()->where('approved', 1)->select('id', 'document_number')->get());
        else
            return [];
    }

    public function related_document_show()
    {
        return view('pages.purchaseorders.related_document');
    }

    public function related_document(Request $request)
    {
        $documents = collect([]);
        $document = [
            'id' => '',
            'document_number' => '',
            'date' => '',
            'type' => '',
            'version' => '',
            'PO_reference' => '',
            'PO_id' => '',
            'submit_status' => '',
            'archive' => '',
        ];
        $purchaseOrder = PurchaseOrder::where('purchase_order_reference', $request->PO_reference)->first();
        if ($purchaseOrder) {
            $_documents = $purchaseOrder->documents()->select('id', 'document_number', 'date', 'type', 'version', 'submit_status', 'archive');

            if ($request->sent) { // Only sent documents
                $_documents = $_documents->where('submit_status', 1);
            }

            $_documents = $_documents->get();
            foreach ($_documents as $_document) {
                $document['id'] = $_document->id;
                $document['document_number'] = $_document->document_number;
                $document['date'] = $_document->date;
                $document['type'] = $_document->type;
                $document['version'] = $_document->version;
                $document['PO_reference'] = $purchaseOrder->purchase_order_reference;
                $document['PO_id'] = $purchaseOrder->id;
                $document['submit_status'] = $_document->submit_status;
                $document['archive'] = $_document->archive;
                $documents->push($document);
            }
            return json_decode($documents);
        } else {
            return [];
        }
    }
}
