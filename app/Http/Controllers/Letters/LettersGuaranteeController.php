<?php

namespace App\Http\Controllers\Letters;

use Brian2694\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use App\Imports\LettersGuaranteeChangingImport;
use App\Imports\LettersGuaranteeImport;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Bank;
use App\Models\LettersGuarantee;
use App\Models\LettersGuaranteeBankCommissions;
use App\Models\LettersGuaranteeChanging;
use App\Models\LettersGuaranteeRequest;
use Maatwebsite\Excel\Facades\Excel;

class LettersGuaranteeController extends Controller
{
    public function import_letters_guarantee(Request $request)
    {
        if (!$request->file) {
            return back()->with('error', 'Can not upload empty file.');
        }
        Excel::import(new LettersGuaranteeImport, request()->file('file'));

        return back();
    }
    public function import_letters_guarantee_changing(Request $request)
    {
        if (!$request->file) {
            return back()->with('error', 'Can not upload empty file.');
        }
        Excel::import(new LettersGuaranteeChangingImport, request()->file('file'));

        return back();
    }

    public function index()
    {
        $letters_guarantee = LettersGuarantee::orderBy('id', 'DESC')->get();
        return view('pages.letters_guarantee.index', compact('letters_guarantee'));
    }
    public function show($id)
    {
        // return $id;
        $letter_guarantee = LettersGuarantee::where("id", $id)->first();
        $letters_guarantee_changing = LettersGuaranteeChanging::where("letters_guarantee_id", $id)->get();
        $letters_guarantee_bank_commissions = LettersGuaranteeBankCommissions::where("letters_guarantee_id", $id)->get();
        return view('pages.letters_guarantee.show', compact('letter_guarantee', 'letters_guarantee_changing','letters_guarantee_bank_commissions'));
    }

    public function create()
    {

        $purchaseOrders = purchaseOrder::get();
        $banks = Bank::all();

        return view('pages.letters_guarantee.create', compact('purchaseOrders', 'banks'));
    }

    public function letter_guarantee_create_from_request($id)
    {

        $letter_guarantee = LettersGuaranteeRequest::where("id", $id)->first();
        $banks = Bank::all();
        $purchaseOrders = purchaseOrder::get();
        $client = null;
        // Business client
        if ($letter_guarantee->client_type == 'b') {
            $client = $letter_guarantee->businessClient;
        } // Foreigner client
        else if ($letter_guarantee->client_type == 'f') {
            $client = $letter_guarantee->foreignerClient;
        } // Person client
        else if ($letter_guarantee->client_type == 'p') {
            $client = $letter_guarantee->personClient;
        }

        return view('pages.letters_guarantee.create-from-request', compact('purchaseOrders', 'banks', 'letter_guarantee', 'client'));
    }

    public function extend_raise($id)
    {
        // return 123;
        $value = 0;
        $cash_margin = 0;
        $expiry_date = 0;
        $letter_guarantee = LettersGuarantee::where("id", $id)->first();

        $letter_guarantee_changing = LettersGuaranteeChanging::where("letters_guarantee_id", $letter_guarantee->id)->latest('id')->first();


        if (isset($letter_guarantee_changing)) {
            $value = $letter_guarantee_changing->value;
            $cash_margin = $letter_guarantee_changing->cash_margin;
            $expiry_date = $letter_guarantee_changing->expiry_date;
        } else {
            $value = $letter_guarantee->value;
            $cash_margin = $letter_guarantee->cash_margin;
            $expiry_date = $letter_guarantee->expiry_date;
        }

        return view('pages.letters_guarantee.extend_raise', compact('cash_margin','letter_guarantee', 'value', 'expiry_date'));
    }

    public function extend_raise_store($id, Request $request)
    {
        $validatedData = $request->validate([

            'user_id' => ['required'],
            'letters_guarantee_id' => ['required'],
            'value' => ['required', 'max:255'],
            'cash_margin' => ['required', 'max:255'],
            'expiry_date' => ['required', 'max:255'],

        ]);



        $x = LettersGuaranteeChanging::create($validatedData);

        if ($image = $request->file('image')) {
            $destinationPath = 'public/image_letter/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $profileImage;
            $x->update([
                "image" => $profileImage,
            ]);
        }

        Toastr::success(trans('site.letter_guarantee_success_edited'), trans("site.success"));
        return redirect()->route('letter_guarantee.index');
    }

    public function letter_guarantee_answered(Request $request)
    {
        // return $request;

        $letter_guarantee = LettersGuarantee::findOrFail($request->letterGuaranteeID);
        $letter_guarantee->update([
            'recipient_name' => $request->recipient_name,
            'reply_date' => $request->reply_date,
            'giver_name' => auth()->user()->id,
        ]);

        $purchaseOrder = purchaseOrder::where("id", $letter_guarantee->supply_order)->first();
        if ($letter_guarantee->type == "instant") {
            $purchaseOrder->update([
                'primary_delivery_status' => 1,

            ]);
        }
        if ($letter_guarantee->type == "final_insurance") {
            $purchaseOrder->update([
                'final_delivery_status' => 1,
                'received_final_performance_bond_status' => 1,

            ]);
        }

        Toastr::success(trans('site.letter_success_updated'), trans("site.success"));
        return redirect()->route('letter_guarantee.index');
    }
    public function bank_commissions(Request $request)
    {
        // return $request;

        $x = LettersGuaranteeBankCommissions::create([
            'letters_guarantee_id' => $request->letterGuaranteeID,
            'value_commission' => $request->value_commission,
            'statement' => $request->statement,
            'date' => $request->date,
            'user_id' => auth()->user()->id,
        ]);

        $profileImage = "";
        if ($image = $request->file('image')) {
            $destinationPath = 'public/image_letter/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $profileImage;
            $x->update([
                "image" => $profileImage,
            ]);
        }

        Toastr::success(trans('site.letter_success_updated'), trans("site.success"));
        return redirect()->route('letter_guarantee.index');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        if (isset($request->letter_guarantee_request)) {
            $letter_guarantee_request = LettersGuaranteeRequest::where("id", $request->letter_guarantee_request)->first();
            $letter_guarantee_request->update(["attached" => 1,]);
        }
        // return $request;

        $validatedData = $request->validate([
            // 'client_id' => 'required',
            'client_type' => 'required',
            'side' => 'required',
            'supply_order_tax' => ['max:255'],
            'purpose' => 'required',
            'project_number' => ['required', 'string', 'min:3'],
            // 'supply_order' => ['required'],
            'value' => ['required', 'max:255'],
            'cash_margin' => ['required', 'max:255'],
            'type' => ['required', 'max:255'],
            'bank_id' => ['required', 'max:255'],
            'letter_guarantee_num' => ['required', 'unique:letters_guarantee'],
            'image' => 'required',
            'release_date' => ['required', 'max:255'],
            'expiry_date' => ['required', 'max:255'],
        ]);
        $profileImage = "";
        if ($image = $request->file('image')) {
            $destinationPath = 'public/image_letter/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $profileImage;
        }


        $x = LettersGuarantee::create($validatedData);
        $x->update([
            "image" => $profileImage,
            "letters_guarantee_request_id" => $request->letter_guarantee_request,
        ]);

        if (isset($request->client_id)) {
            $x->update([
                "client_id" => $request->client_id,
            ]);
        } else {
            $x->update([
                "client_name" => $request->client_name,
            ]);
        }
        if (isset($request->supply_order)) {
            $x->update([
                "supply_order" => $request->supply_order,
            ]);
        } else {
            $x->update([
                "supply_order_name" => $request->supply_order_name,
            ]);
        }
        Toastr::success(trans('site.letter_success_added'), trans("site.success"));
        return redirect()->route('letter_guarantee.index');
    }

    public function dataAjax(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = purchaseOrder::select("id", "project_name")
                ->where('project_name', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($data);
    }



    public function edit(LettersGuarantee $letter_guarantee)
    {
        $banks = Bank::get();
        $purchaseOrders = purchaseOrder::get();
        $client = null;
        // Business client
        if ($letter_guarantee->client_type == 'b') {
            $client = $letter_guarantee->businessClient;
        } // Foreigner client
        else if ($letter_guarantee->client_type == 'f') {
            $client = $letter_guarantee->foreignerClient;
        } // Person client
        else if ($letter_guarantee->client_type == 'p') {
            $client = $letter_guarantee->personClient;
        }

        return view('pages.letters_guarantee.edit', compact('purchaseOrders', 'banks', 'letter_guarantee', 'client'));
    }




    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'client_id' => 'required',
            'client_type' => 'required',
            'side' => 'required',
            'purpose' => 'required',
            'project_number' => ['required', 'string', 'min:3'],
            'supply_order' => ['required'],
            'value' => ['required', 'max:255'],
            'cash_margin' => ['required', 'max:255'],
            'type' => ['required', 'max:255'],
            'bank_id' => ['required', 'max:255'],
            'letter_guarantee_num' => 'required|unique:letters_guarantee,letter_guarantee_num,' . $id,
            'release_date' => ['required', 'max:255'],
            'expiry_date' => ['required', 'max:255'],
        ]);

        if ($image = $request->file('image')) {
            $destinationPath = 'public/image_letter/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
        $letter_guarantee = LettersGuarantee::findOrFail($id);
        $letter_guarantee->update($validatedData);

        if ($image = $request->file('image')) {
            $letter_guarantee->update(["image" => $profileImage,]);
        }
        Toastr::success(trans('site.letter_success_edited'), trans("site.success"));
        return redirect()->route('letter_guarantee.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $letters_guarantee = LettersGuarantee::find($id);
        $letters_guarantee->delete();

        Toastr::success(trans('site.letter_success_deleted'), trans("site.success"));
        return redirect()->route('letter_guarantee.index');
    }
}
