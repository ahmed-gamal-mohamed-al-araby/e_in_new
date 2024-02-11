<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Notification;
use App\Models\Product;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->where('approved', 1)->get();
        $data = null;
        return view('pages.Product.index', compact('data', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());
        $url = route('show_product_approve', $product->id);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'a',
            'table_name' => 'products',
            'record_id' => $product->id,
        ]);

        // set content
        $notification->update([
            'content' => auth()->user()->username . ' انشأ منتج جديد' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$product->product_name</a>",
        ]);

        Toastr::success(trans("site.product_success_add"), trans("site.success"));
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Product::findOrFail($id);
        $products = Product::all();
        return view('pages.Product.index', compact('products', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $url = route('show_product_approve', $product->id);

        $product->update($request->all());
        $product->update([
            'approved' => 0,
        ]);

        Notification::where('table_name', 'products')->where('record_id', $product->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'a',
            'table_name' => 'products',
            'record_id' => $product->id,
        ]);

        // set content
        $notification->update([
            'content' => auth()->user()->username . ' قام بتعديل منتج ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$product->product_name</a>",

        ]);

        Toastr::success(trans("site.product_success_edit"), trans("site.success"));
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $data = Product::findOrFail($request->product_id);
            $product = clone $data;
            $data->delete();

            // Notification part
            Notification::where('table_name', 'products')->where('record_id', $data->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'products',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف منتج ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Product name: $product->product_name, Internal code: $product->internal_code, Product code: $product->product_code,  Standard code type: $product->standard_code_type</div>",
            ]);

            Toastr::success(trans('site.product_success_deleted'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans('site.product_delete_error'), trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
        }
        return redirect()->route('product.index');
    }

    public function showForApprove(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $notification = Notification::findOrFail($request->query('n_id'));

        return view('pages.Product.approve-show', compact('product', 'notification'));
    }

    public function approve_edit($id)
    {
        $product = Product::findOrFail($id);
        // redirect to index if product approved
        if ($product->approved)
            return redirect(route('product.index'));

        return view('pages.product.approve-edit', compact('product'));
    }

    public function approved(Request $request, $id)
    {
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

        $product = Product::findOrFail($id);
        $product->update([
            'approved' => 1,
        ]);

        $url = route('show_product_approve', $product->id);

        $notification = Notification::create([
            'content' => '',
            'table_name' => 'products',
            'record_id' => $product->id,
            'user_id' => auth()->user()->id,
        ]);

        // set content
        $notification->update([
            'view_status' => 1,
            'content' => auth()->user()->username . ' وافق على إنشاء/تعديل منتج ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$product->product_name</a>",
        ]);

        Toastr::success(trans('site.approved_success'), trans('site.success'));
        return redirect()->route('product.index');
    }
}
