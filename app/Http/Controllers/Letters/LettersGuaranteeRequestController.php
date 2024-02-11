<?php

namespace App\Http\Controllers\Letters;

use App\Models\LettersGuaranteeRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PurchaseOrder;
use App\Models\Bank;
use App\Models\BusinessClient;
use App\Models\ForeignerClient;
use App\Models\Item;
use App\Models\LettersGuaranteeRequestChanging;
use App\Models\PersonClient;
use Brian2694\Toastr\Facades\Toastr;

class LettersGuaranteeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $letters_guarantee_request = LettersGuaranteeRequest::Where("attached", 0)->orderBy('id', 'DESC')->get();
        $banks = Bank::get();
        $purchaseOrders = PurchaseOrder::get();
        return view('pages.letters_guarantee_request.index', compact('purchaseOrders', 'banks', 'letters_guarantee_request'));
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

        return view('pages.letters_guarantee_request.create', compact('purchaseOrders', 'banks'));
    }
    public function get_supply_order(Request $request)
    {
        return PurchaseOrder::where("client_type", $request->clientType)->where("client_id", $request->client_id)->get();
    }
    public function get_supply_order_data(Request $request)
    {
        $sum = 0;
        $po_data = PurchaseOrder::with("project")->where("id", $request->po_id)->first();
        $items = Item::where("purchase_order_id", $request->po_id)->get();

        if ($request->po_tax!= "excl") {
            foreach ($items as $key => $item) {
                $sum += $item->total_amount;
            }
        }
        else {
            foreach ($items as $key => $item) {
                $sum += $item->net_total;
            }
        }
        

        
        return array("sum" => $sum, "purchase_order_reference" => $po_data->purchase_order_reference, "main_project_name" => isset($po_data->main_project_name) ? $po_data->project->name_ar : "" , "project_name" => $po_data->project_name, "project_number" => $po_data->project_number);
    }
    public function print($id)
    {
        // return $id;
        $letter_guarantee_request = LettersGuaranteeRequest::where("id", $id)->first();

        $value = $letter_guarantee_request->value;

        $f = new \NumberFormatter("ar", \NumberFormatter::SPELLOUT);
        $fdecimal = new \NumberFormatter("en", \NumberFormatter::DECIMAL_ALWAYS_SHOWN);
        if (strpos($f->format($value), "فاصل") != "") {
            $var = substr($f->format($value), 0, strpos($f->format($value), "فاصل"));
            $var .=  "و " . str_replace(".", "", strstr($fdecimal->format($value), ".")) . " / 100 قرشا";
        } else {
            $var =  $f->format($value);
        }
        return view('pages.letters_guarantee_request.print', compact('var', 'letter_guarantee_request'));
    }

    public  function numToArabicLatters($value)
    {

        $f = new \NumberFormatter("ar", \NumberFormatter::SPELLOUT);
        $fdecimal = new \NumberFormatter("en", \NumberFormatter::DECIMAL_ALWAYS_SHOWN);
        if (strpos($f->format($value), "فاصل") != "") {
            $var = substr($f->format($value), 0, strpos($f->format($value), "فاصل"));
            $var .=  "و " . str_replace(".", "", strstr($fdecimal->format($value), ".")) . " / 100 قرشا";
        } else {
            $var =  $f->format($value);
        }
        return $var;
    }


    public function extend_raise($id)
    {
        // return $id;
        $sum = 0;
        $letter_guarantee_request = LettersGuaranteeRequest::where("id", $id)->first();

        $po_data = PurchaseOrder::where("id", $letter_guarantee_request->supply_order)->first();
        $items = Item::where("purchase_order_id", $po_data->id)->get();
        foreach ($items as $key => $item) {
            $sum += $item->total_amount;
        }
        return view('pages.letters_guarantee_request.extend_raise', compact('letter_guarantee_request', 'sum'));
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
            'user_id' => 'required',
            'supply_order_tax' => 'max:255',
            'client_type' => 'required',
            'duration_in_month' => ['required'],
            'value' => ['required', 'max:255'],
            'type' => ['required', 'max:255'],
            'release_date' => ['required', 'max:255'],
            'expiry_date' => ['required', 'max:255'],

        ]);

        $lettersGuaranteeRequest = LettersGuaranteeRequest::create($validatedData);

        if (isset($request->supply_order)) {
            $lettersGuaranteeRequest->update([
                "supply_order" => $request->supply_order,
                "purchase_order_percentage" => $request->purchase_order_percentage,

            ]);
        } else {
            $lettersGuaranteeRequest->update([
                "supply_order_name" => $request->supply_order_name,
                "main_project_name" => $request->main_project_name,
                "project_name" => $request->project_name,
                "project_number" => $request->project_number,
                "total_amount" => $request->total_amount,
                "purchase_order_percentage" => $request->purchase_order_percentage,

            ]);
        }
        if (isset($request->client_id)) {
            $lettersGuaranteeRequest->update([
                "client_id" => $request->client_id,

            ]);
        } else {
            $lettersGuaranteeRequest->update([
                "client_name" => $request->new_client_name,
                "client_address" => $request->new_client_address,
                

            ]);
        }
        Toastr::success(trans('site.letter_request_success_added'), trans("site.success"));
        return redirect()->route('letter_guarantee_request.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LettersGuaranteeRequest  $lettersGuaranteeRequest
     * @return \Illuminate\Http\Response
     */
    public function show(LettersGuaranteeRequest $lettersGuaranteeRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LettersGuaranteeRequest  $lettersGuaranteeRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(LettersGuaranteeRequest $letter_guarantee_request)
    {
        // return $letter_guarantee_request;
        $client = null;
        // Business client
        if ($letter_guarantee_request->client_type == 'b') {
            $allClients = BusinessClient::all();
            $client = $letter_guarantee_request->businessClient;
            $purchaseOrders = purchaseOrder::where("client_id", $letter_guarantee_request->client_id)->get();
            $purchaseOrder12 = purchaseOrder::where("id", $letter_guarantee_request->supply_order)->first();
        } // Foreigner client
        else if ($letter_guarantee_request->client_type == 'f') {
            $allClients = ForeignerClient::all();
            $client = $letter_guarantee_request->foreignerClient;
            $purchaseOrders = purchaseOrder::where("client_id", $letter_guarantee_request->client_id)->get();
            $purchaseOrder12 = purchaseOrder::where("id", $letter_guarantee_request->supply_order)->first();
        } // Person client
        else if ($letter_guarantee_request->client_type == 'p') {
            $allClients = PersonClient::all();
            $client = $letter_guarantee_request->personClient;
            $purchaseOrders = purchaseOrder::where("client_id", $letter_guarantee_request->client_id)->get();
            $purchaseOrder12 = purchaseOrder::where("id", $letter_guarantee_request->supply_order)->first();
        }
        $sum = 0;
        $items = Item::where("purchase_order_id", $letter_guarantee_request->supply_order)->get();
        foreach ($items as $key => $item) {
            $sum += $item->total_amount;
        }

        return view('pages.letters_guarantee_request.edit', compact('allClients', 'sum', 'purchaseOrder12', 'purchaseOrders', 'letter_guarantee_request', 'client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LettersGuaranteeRequest  $lettersGuaranteeRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $validatedData = $request->validate([
            'client_id' => 'required',
            'supply_order_tax' => 'max:255',
            'client_type' => 'required',
            'duration_in_month' => ['required'],
            'value' => ['required', 'max:255'],
            'type' => ['required', 'max:255'],
            'release_date' => ['required', 'max:255'],
            'expiry_date' => ['required', 'max:255'],

        ]);


        $letter_guarantee_request = LettersGuaranteeRequest::findOrFail($id);
        $letter_guarantee_request->update($validatedData);


        if (isset($request->supply_order)) {
            $letter_guarantee_request->update([
                "supply_order" => $request->supply_order,
                "purchase_order_percentage" => $request->purchase_order_percentage,

                "supply_order_name" => null,
                "main_project_name" => null,
                "project_name" => null,
                "project_number" => null,
                "total_amount" => null,
            ]);
        } else {
            $letter_guarantee_request->update([
                "supply_order_name" => $request->supply_order_name,
                "main_project_name" => $request->main_project_name,
                "project_name" => $request->project_name,
                "project_number" => $request->project_number,
                "total_amount" => $request->total_amount,
                "purchase_order_percentage" => $request->purchase_order_percentage,
                "supply_order" => null,

            ]);
        }

        if (isset($request->client_id2)) {
            $letter_guarantee_request->update([
                "client_id" => $request->client_id2,
                "client_name" => null,
                "client_address" => null,
            ]);
        } 
        elseif (!isset($request->new_client_name)) {
            $letter_guarantee_request->update([
                // "client_id" => $request->client_id,
             
            ]);
        }
        else {
            $letter_guarantee_request->update([
                "client_name" => $request->new_client_name,
                "client_address" => $request->new_client_address,
                "client_id" => null,

            ]);
        }

        Toastr::success(trans('site.letter_request_success_edited'), trans("site.success"));
        return redirect()->route('letter_guarantee_request.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LettersGuaranteeRequest  $lettersGuaranteeRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lettersGuaranteeRequest = LettersGuaranteeRequest::find($id);
        $lettersGuaranteeRequest->delete();

        Toastr::success(trans('site.letter_request_success_deleted'), trans("site.success"));
        return redirect()->route('letter_guarantee_request.index');
    }
}
