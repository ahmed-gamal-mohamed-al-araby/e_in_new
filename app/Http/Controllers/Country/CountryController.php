<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        $data = [];
        return view('pages.country.index', compact('countries', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('country.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        Country::create([
            'name' => $request->name,
            'code' => $request->code
            ]);
        Toastr::success(trans('site.country_success_added'),trans("site.success"));
        return redirect()->route('country.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('country.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countries = Country::all();
        $data = Country::findOrFail($id);
        return view('pages.country.index', compact('countries', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCountryRequest $request, $id)
    {
        $input = $request->all();
        $country = Country::findOrFail($id);
        $country->update($input);
        Toastr::success(trans("site.country_success_edit"), trans("site.success"));
        return redirect()->route('country.index');
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
            $data = Country::findOrFail($request->country_id);
            $data->delete();
            Toastr::success(trans('site.country_success_deleted'), trans("site.success"));
            return redirect()->route('country.index');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans('site.country_delete_error'), trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
            return redirect()->route('country.index');
        }
    }

    // Return family names for its subcategory
    public function  citiesOfcountry($id){
        return json_encode(Country::findOrFail($id)->cities);
    }

}
