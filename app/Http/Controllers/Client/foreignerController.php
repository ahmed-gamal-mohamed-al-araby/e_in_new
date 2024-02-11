<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreForeignerClientRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\ForeignerClient;
use App\Models\Notification;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class foreignerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foreigners = ForeignerClient::orderBy('id', 'DESC')->where('approved', 1)->get();
        $data = null;
        $countries = Country::all();

        return view('pages.client.foreigner.index', compact('data', 'foreigners', 'countries'));
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
    public function store(StoreForeignerClientRequest $request)
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

            $foreignerClient = ForeignerClient::create([
                'company_name' => $request->company_name,
                'person_name' => $request->person_name,
                'person_email' => $request->person_email,
                'person_mobile' => $request->person_mobile,
                'vat_id' => $request->vat_id,
                'address_id' => $address->id,
            ]);

            // Notification part
            $url = route('show_foreigner_client_approve', $foreignerClient->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'foreigner_clients',
                'record_id' => $foreignerClient->id,
            ]);
            
            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ عميل أجنبى ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$foreignerClient->company_name</a>",
            ]);

            DB::commit();

            Toastr::success(trans('site.client_success_add'), trans("site.success"));
            return redirect()->route('foreignerClient.index');
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
        $data = ForeignerClient::findOrFail($id);
        $foreigners = ForeignerClient::orderBy('id', 'DESC')->get();
        $countries = Country::all();

        return view('pages.client.foreigner.index', compact('foreigners', 'data', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreForeignerClientRequest $request, $id)
    {
        $foreignerClient = ForeignerClient::findOrFail($id);

        // Start transaction
        DB::beginTransaction();

        try {
            $address = $foreignerClient->address;
            $address->update([
                'country_id' => $request->country_id,
                'city_id'  => $request->city_id,
                'region_city' => $request->region_city,
                'street' => $request->street,
                'building_no' => $request->building_no,
            ]);
            $foreignerClient->update([
                'company_name' => $request->company_name,
                'person_name' => $request->person_name,
                'person_email' => $request->person_email,
                'person_mobile' => $request->person_mobile,
                'vat_id' => $request->vat_id,
            ]);

            // Notification part
            $url = route('show_foreigner_client_approve', $foreignerClient->id);

            $foreignerClient->update([
                'approved' => 0,
            ]);

            Notification::where('table_name', 'foreigner_clients')->where('record_id', $foreignerClient->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'foreigner_clients',
                'record_id' => $foreignerClient->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بتعديل عميل أجنبى ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$foreignerClient->company_name</a>",
            ]);

            DB::commit();

            Toastr::success(trans('site.client_success_edit'), trans("site.success"));
            return redirect()->route('foreignerClient.index');
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
            $foreignerClient = ForeignerClient::findOrFail($request->foreigner_id);
            if ($foreignerClient->purchaseOrders->count() > 0)
                throw new \Exception('');

            $addressId = $foreignerClient->address->id;
            $deletedForeignerClient = clone $foreignerClient;
            Address::find($addressId)->delete();

            // Notification part
            Notification::where('table_name', 'foreigner_clients')->where('record_id', $deletedForeignerClient->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'foreigner_clients',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف عميل أجنبى ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Company name: $deletedForeignerClient->company_name, Person name: $deletedForeignerClient->person_name, Person mobile: $deletedForeignerClient->person_mobile, Person email: $deletedForeignerClient->person_email, Vat id: $deletedForeignerClient->vat_id</div>",
            ]);

            Toastr::success(trans('site.client_success_delete'), trans("site.success"));
        } catch (\Exception $e) {
            Toastr::error(trans('site.client_delete_error'), trans("site.sorry"));
        }
        return redirect()->route('foreignerClient.index');
    }

    public function showForApprove(Request $request, $id)
    {
        $foreignerClient = ForeignerClient::findOrFail($id);
        $notification = Notification::findOrFail($request->query('n_id'));

        return view('pages.client.foreigner.approve-show', compact('foreignerClient', 'notification'));
    }

    public function approve_edit($id)
    {
        $foreignerClient = ForeignerClient::findOrFail($id);
        $countries = Country::all();
        // redirect to index if product approved
        if ($foreignerClient->approved)
            return redirect(route('product.index'));

        return view('pages.client.foreigner.approve-edit', compact('foreignerClient', 'countries'));
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

        $foreignerClient = ForeignerClient::findOrFail($id);
        $foreignerClient->update([
            'approved' => 1,
        ]);

        $url = route('show_foreigner_client_approve', $foreignerClient->id);

        $notification = Notification::create([
            'content' => '',
            'table_name' => 'foreigner_clients',
            'record_id' => $foreignerClient->id,
            'user_id' => auth()->user()->id,
        ]);

        // set content
        $notification->update([
            'view_status' => 1,
            'content' => auth()->user()->username . ' وافق على إنشاء/تعديل عميل أجنبى ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$foreignerClient->company_name</a>",
        ]);

        Toastr::success(trans('site.approved_success'), trans('site.success'));
        return redirect()->route('foreignerClient.index');
    }
    
}
