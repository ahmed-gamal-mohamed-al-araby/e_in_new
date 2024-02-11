<?php

namespace App\Http\Controllers\City;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Models\City;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();
        $countries = Country::all();
        $data = [];
        return view('pages.city.index', compact('cities', 'countries', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('city.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $inputs = $request->all();
        if ($this->validateSubGroupRequest($inputs) != 0) {
            Toastr::error(trans('site.duplicated_city_with_same_country'), trans("site.sorry"));
            throw ValidationException::withMessages(['name' => trans('site.duplicated_city_with_same_country')]);
        }
        City::create($inputs);

        Toastr::success(trans("site.city_success_added"), trans("site.success"));
        return redirect()->route('city.index');
    }

    // Custom validation to check if there is group have the same subGroup
    public function validateSubGroupRequest($inputs, $id = NULL)
    {
        if ($id)
            return City::where([
                ['name', '=', $inputs['name']],
                ['country_id', '=', $inputs['country_id']],
                ['id', '<>', $id],
            ])->count();
        else
            return City::where([
                ['name', '=', $inputs['name']],
                ['country_id', '=', $inputs['country_id']],
            ])->count();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('city.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = City::findOrFail($id);
        $cities = City::all();
        $countries = Country::all();
        return view('pages.city.index', compact('cities', 'countries', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCityRequest $request, $id)
    {
        $inputs = $request->all();
        if ($this->validateSubGroupRequest($inputs, $id) != 0) {
            Toastr::error(trans('site.duplicated_city_with_same_country'), trans("site.sorry"));
            throw ValidationException::withMessages(['name' => trans('site.duplicated_city_with_same_country')]);
        }
        $city = City::findOrFail($id);
        $city->update($inputs);

        Toastr::success(trans("site.city_success_edit"), trans("site.success"));
        return redirect()->route('city.index');
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
            $data = City::findOrFail($request->city_id);
            $data->delete();
            Toastr::success(trans('site.city_success_deleted'), trans("site.success"));
            return redirect()->route('city.index');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                Toastr::error(trans('site.city_delete_error'), trans("site.sorry"));
            else
                Toastr::error(trans("site.sorry"));
            return redirect()->route('city.index');
        }
    }
    
}
