<?php

namespace App\Http\Controllers\PO;

use App\Http\Controllers\Controller;
use App\Models\DocumentItem;
use App\Models\Item;
use App\Models\Notification;
use App\Models\PurchaseOrderTax;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoItemController extends Controller
{
    public static $purchase_order_id = null;
    public static function set_purchase_order_id($purchase_order_id)
    {
        self::$purchase_order_id = $purchase_order_id;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($PoItems)
    {
        // for items per invoiceItems -> use the next foreach
        foreach ($PoItems as $key => $item) {

            foreach ($item as $itemIndex => $basicItemData) {
                // $key is the key of item and the value of it

                $item = Item::create([
                    'purchase_order_id' => self::$purchase_order_id,
                    'product_id' => $basicItemData['product_id'],
                    'description' => $basicItemData['description'],
                    'quantity' => $basicItemData['quantity'],
                    'unit' => $basicItemData['unit'],
                    'currency' => $basicItemData['currency'],
                    'item_price' => $basicItemData['item_price'],
                    'discount_item_rate' => $basicItemData['discount_items_rate'] ?? 0,
                    'discount_item_amount' => $basicItemData['discount_items_number'] ?? 0,
                    'taxable_fees' => $basicItemData['taxable_fees'] ?? 0,
                    'value_difference' => $basicItemData['differ_value'] ?? 0,
                    'items_discount' => $basicItemData['items_discount'] ?? 0,
                    'net_total' => $basicItemData['net_total'],
                    'total_amount' => $basicItemData['total_amount'],
                ]);

                PoTaxController::set_po_item_id($item->id);
                PoTaxController::store($basicItemData);
            }
        }
    }

    public static function storeIndividualItem(Request $request)
    {
        $item = Item::create([
            'purchase_order_id' => $request->basicData['purchase_order_id'],
            'product_id' => $request->basicData['product_id'],
            'description' => $request->basicData['description'],
            'quantity' => $request->basicData['quantity'],
            'unit' => $request->basicData['unit'],
            'currency' => $request->basicData['currency'],
            'item_price' => $request->basicData['item_price'],
            'discount_item_rate' => $request->basicData['discount_item_rate'],
            'discount_item_amount' => $request->basicData['discount_item_amount'],
            'taxable_fees' => $request->basicData['taxable_fees'],
            'value_difference' => $request->basicData['value_difference'],
            'items_discount' => $request->basicData['items_discount'],
            'net_total' => $request->basicData['net_total'],
            'total_amount' => $request->basicData['total_amount'],
        ]);
        PoTaxController::set_po_item_id($item->id);
        PoTaxController::storeIndividualItemTaxes($request->taxes);

        // Notification part
        $url = route('show_purchaseorder_approve', $item->purchaseOrder->id);

        $item->purchaseOrder->update([
            'approved' => 0,
        ]);

        Notification::where('table_name', 'purchase_orders')->where('record_id', $item->purchaseOrder->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'a',
            'table_name' => 'purchase_orders',
            'record_id' => $item->purchaseOrder->id,
        ]);

        $purchase_order_reference = $item->purchaseOrder->purchase_order_reference;
        // set content
        $notification->update([
            'content' => auth()->user()->username . ' قام بتعديل امر الشراء ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$purchase_order_reference</a>",
        ]);
        

        Toastr::success(trans('site.updated_successfully'), trans('site.success'));
        // return json_encode($item->purchase_order_id);
        return json_encode(route('purchaseorders.index') . '/' . $item->purchase_order_id);
    }

    public function show($id)
    {
        $poItem = Item::find($id);
        return view('pages.purchaseorders.show_item', compact('poItem'));
    }

    public function edit($id)
    {
        $poItem = Item::find($id);
        return view('pages.purchaseorders.edit_item', compact('poItem'));
    }

    public function getPoItemById($id)
    {
        $poItem = Item::find($id);
        $item = collect();
        // $item->put('basciData' ,$poItem->toArray());
        $item->put('basicData', $poItem);
        $item->put('product_code', $poItem->product->product_code);
        $item->put('product_name', $poItem->product->product_name);
        $item->put('taxes', $poItem->purchaseOrderTaxes);
        $item->put('documentItemQuantities', $poItem->documentItems()->sum('quantity'));
        $item->put('documentItemPrices', $poItem->documentItems()->sum('item_price'));
        return json_encode(($item));
    }

    public function update(Request $request)
    {
        $item = Item::find($request->basicData['id']);
        
        // update item basic data
        $item->update($request->basicData);

        // delete the deleted taxes
        PurchaseOrderTax::where('item_id', $item->id)->whereNotIn('id', $request->taxesId)->delete();

        // edit old taxes
        foreach ($request->taxes as $key => $newTax) {
            $oldTax = PurchaseOrderTax::where('id', $newTax['id']);
            if ($oldTax->get()->toArray()) { // update tax of item
                $oldTax->update($newTax);
            } else { // add new tax of item
                $new = PurchaseOrderTax::create($newTax);
            }
        }

        // Notification part
        $url = route('show_purchaseorder_approve', $item->purchaseOrder->id);

        $item->purchaseOrder->update([
            'approved' => 0,
        ]);

        Notification::where('table_name', 'purchase_orders')->where('record_id', $item->purchaseOrder->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'a',
            'table_name' => 'purchase_orders',
            'record_id' => $item->purchaseOrder->id,
        ]);

        $purchase_order_reference = $item->purchaseOrder->purchase_order_reference;
        // set content
        $notification->update([
            'content' => auth()->user()->username . ' قام بتعديل امر الشراء ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$purchase_order_reference</a>",
        ]);

        Toastr::success(trans('site.updated_successfully'), trans('site.success'));

        return json_encode(route('purchaseorders.index') . '/' . $item->purchase_order_id );
    }


    public function getAvailableQuantity($POItemId)
    {
        $POItem = Item::findOrFail($POItemId);
        return json_encode($POItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $documentItem = DocumentItem::where('item_id', $request->item_id)->count();
        if ($documentItem) {
            Toastr::error(trans('site.item_delete_error'), trans("site.sorry"));
        } else {
            $purchaseOrderItem = Item::findOrFail($request->item_id);
            $purchaseOrder = $purchaseOrderItem->purchaseOrder;
            $purchaseOrderItem->delete();


            // Notification part
            $url = route('show_purchaseorder_approve', $purchaseOrder->id);

            Notification::where('table_name', 'purchase_orders')->where('record_id', $purchaseOrder->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

            $purchaseOrder->update([
                'approved' => 0,
            ]);

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
            Toastr::success(trans('site.item_success_deleted'), trans("site.success"));
        }
        return redirect()->route('purchaseorders.index');
    }
}
