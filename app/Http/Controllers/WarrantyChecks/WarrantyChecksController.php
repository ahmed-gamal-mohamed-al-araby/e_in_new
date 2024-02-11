<?php

namespace App\Http\Controllers\WarrantyChecks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\WarrantyChecksImport;
use App\Models\Bank;
use App\Models\BusinessClient;
use App\Models\ForeignerClient;
use App\Models\PersonClient;
use App\Models\PurchaseOrder;
use App\Models\WarrantyChecks;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;

class WarrantyChecksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   

    public function import_warranty_checks(Request $request) 
    {
        if (!$request->file) {
            return back()->with('error', 'Can not upload empty file.');
        }
        Excel::import(new WarrantyChecksImport,request()->file('file'));
           
        return back();
    }

    public function index()
    {
        $warranty_checks = WarrantyChecks::orderBy('id', 'DESC')->get();
        $banks = Bank::get();
        $purchaseOrders = PurchaseOrder::get();
        return view('pages.warranty_checks.index', compact('purchaseOrders', 'banks','warranty_checks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchaseOrders = purchaseOrder::get();
        $banks = Bank::all();

        return view('pages.warranty_checks.create', compact('purchaseOrders', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        // return $request;

        $validatedData = $request->validate([
            'client_id' => 'required',
            'client_type' => 'required',
            'side' => 'required',
            'bank_name' => '',
            'bank_id' => '',
            'source_name' => '',
            'purpose' => 'required',
            'project_number' => ['required', 'string', 'min:3'],
            'supply_order' => ['required'],
            'value' => ['required', 'max:255'],
            'type' => ['required', 'max:255'],
            'document_nature' => ['required'],
            'image' => 'required|max:2048',
            'check_date' => ['required', 'max:255'],
            'cheque_number' => ['required', 'unique:warranty_checks'],


        ]);
        $profileImage="";
        if ($image = $request->file('image')) {
            $destinationPath = 'public/image_letter/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $profileImage;
        }


       $x= WarrantyChecks::create($validatedData);
       $x->update([
           "image"=> $profileImage,
       ]);

    //    if (isset($request->bank_id)) {
    //     $x->update([
    //         "bank_id"=> $request->bank_id,
    //     ]);
    //    } else {
    //     $x->update([
    //         "bank_name"=> $request->bank_name,
    //     ]);
    //    }
       
        Toastr::success(trans('site.warranty_checks_added'), trans("site.success"));
        return redirect()->route('warranty_checks.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WarrantyChecks  $warrantyChecks
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return $id;
        $warranty_check = WarrantyChecks::where("id",$id)->first();
        return view('pages.warranty_checks.show', compact('warranty_check'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WarrantyChecks  $warrantyChecks
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warrantyChecks=WarrantyChecks::where("id",$id)->first();
        $banks = Bank::get();
        $purchaseOrders = purchaseOrder::get();
        $client = null;
        // Business client
        if ($warrantyChecks->client_type == 'b') {
            $allClients = BusinessClient::all();
            $purchaseOrders = purchaseOrder::where("client_id", $warrantyChecks->client_id)->get();
            $purchaseOrder12 = purchaseOrder::where("id", $warrantyChecks->supply_order)->first();
            $client = $warrantyChecks->businessClient;
        } // Foreigner client
        else if ($warrantyChecks->client_type == 'f') {
            $client = $warrantyChecks->foreignerClient;
            $allClients = ForeignerClient::all();
            $purchaseOrders = purchaseOrder::where("client_id", $warrantyChecks->client_id)->get();
            $purchaseOrder12 = purchaseOrder::where("id", $warrantyChecks->supply_order)->first();
        } // Person client
        else if ($warrantyChecks->client_type == 'p') {
            $client = $warrantyChecks->personClient;
            $allClients = PersonClient::all();
            $purchaseOrders = purchaseOrder::where("client_id", $warrantyChecks->client_id)->get();
            $purchaseOrder12 = purchaseOrder::where("id", $warrantyChecks->supply_order)->first();
        }


        return view('pages.warranty_checks.edit', compact('allClients','purchaseOrder12','purchaseOrders', 'banks','warrantyChecks', 'client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WarrantyChecks  $warrantyChecks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $validatedData = $request->validate([
            'client_id' => 'required',
            'client_type' => 'required',
            'side' => 'required',
            'bank_name' => '',
            'bank_id' => '',
            'reply_date' => '',
            'recipient_name' => '',
            'giver_name' => '',
            'source_name' => '',
            'purpose' => 'required',
            'project_number' => ['required', 'string', 'min:3'],
            'supply_order' => ['required'],
            'value' => ['required', 'max:255'],
            'type' => ['required', 'max:255'],
            'document_nature' => ['required'],
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'check_date' => ['required', 'max:255'],
            'cheque_number' => 'required|unique:warranty_checks,cheque_number,' . $id,

        ]);

        if ($image = $request->file('image')) {
            $destinationPath = 'public/image_letter/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
        $warrantyChecks=WarrantyChecks::findOrFail($id);
        $warrantyChecks->update($validatedData);

        if ($image = $request->file('image')) {
        $warrantyChecks->update(["image"=> $profileImage,]);
        }
        Toastr::success(trans('site.warranty_checks_updated'), trans("site.success"));
        return redirect()->route('warranty_checks.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WarrantyChecks  $warrantyChecks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $warrantyChecks = WarrantyChecks::find($id);
        $warrantyChecks->delete();

        Toastr::success(trans('site.warranty_checks_deleted'), trans("site.success"));
        return redirect()->route('warranty_checks.index');
    }
}
