<?php

namespace App\Http\Controllers\Deduction;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeductionRequest;
use App\Models\Deduction;
use App\Models\DeductionType;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deductions = Deduction::all();
        $deductionTypes = DeductionType::all();
        $data = [];
        return view('pages.deduction.index', compact('deductions', 'deductionTypes', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('deduction.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDeductionRequest $request)
    {
        Deduction::create([
            'name' => $request->name,
            'code' => $request->code,
            'deductionType_id' => $request->type
            ]);
        Toastr::success(trans('site.deduction_success_added'),trans("site.success"));
        return redirect()->route('deduction.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('deduction.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deductions = Deduction::all();
        $deductionTypes = DeductionType::all();
        $data = Deduction::findOrFail($id);
        return view('pages.deduction.index', compact('deductions', 'deductionTypes', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDeductionRequest $request, $id)
    {
        $input = $request->all();
        $deduction = Deduction::findOrFail($id);
        $deduction->update($input);
        Toastr::success(trans("site.deduction_success_edit"), trans("site.success"));
        return redirect()->route('deduction.index');
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
            $data = Deduction::findOrFail($request->deduction_id);
            $data->delete();
            Toastr::success(trans('site.deduction_success_deleted'), trans("site.success"));
            return redirect()->route('deduction.index');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans('site.deduction_delete_error'), trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
            return redirect()->route('deduction.index');
        }
    }
}
