<?php


namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class companyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::orderBy('id', 'DESC')->get();
        $data = null;
        $countries = Country::all();

        return view('pages.Company.index', compact('data', 'companies', 'countries'));
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
    public function store(StoreCompanyRequest $request)
    {
        // Start transaction
        DB::beginTransaction();

        try {
            $address = Address::create([
                'country_id' => $request->country_id,
                'city_id'  => $request->city_id,
                'region_city' => $request->region_city,
                'street' => $request->street,
                'building_no' => $request->building_no,
            ]);
            Company::create([
                'company_name' => $request->company_name,
                'tax_id_number' => $request->tax_id_number,
                'commercial_registeration_number' => $request->commercial_registeration_number,
                'tax_file_number' => $request->tax_file_number,
                'address_id' => $address->id,
            ]);
            DB::commit();
            Toastr::success(trans('site.company_added'), trans("site.success"));
            return redirect()->route('company.index');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Toastr::error(trans("site.sorry"));
            redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Company::findOrFail($id);
        $companies = Company::orderBy('id', 'DESC')->get();
        $countries = Country::all();

        return view('pages.Company.index', compact('companies', 'data', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCompanyRequest $request, $id)
    {
        $company = Company::findOrFail($id);
        // Start transaction
        DB::beginTransaction();

        try {
            $address = $company->address;
            $address->update([
                'country_id' => $request->country_id,
                'city_id'  => $request->city_id,
                'region_city' => $request->region_city,
                'street' => $request->street,
                'building_no' => $request->building_no,
            ]);
            $company->update([
                'company_name' => $request->company_name,
                'tax_id_number' => $request->tax_id_number,
                'commercial_registeration_number' => $request->commercial_registeration_number,
                'tax_file_number' => $request->tax_file_number,
            ]);

            DB::commit();
            Toastr::success(trans('site.company_success_edit'), trans("site.success"));
        return redirect()->route('company.index');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Toastr::error(trans("site.sorry"));
            redirect()->back();
        }
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
            $data = Company::findOrFail($request->company_id);
            $addressId = $data->address->id;
            Address::find($addressId)->delete();
            Toastr::success(trans('site.company_success_deleted'), trans("site.success"));
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans('site.company_delete_error'), trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
        }
        return redirect()->route('company.index');
    }
    
}
