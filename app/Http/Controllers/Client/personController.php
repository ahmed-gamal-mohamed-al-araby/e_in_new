<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonClientRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Notification;
use App\Models\PersonClient;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class personController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $persons = PersonClient::orderBy('id', 'DESC')->where('approved', 1)->get();
        $data = null;
        $countries = Country::all();

        return view('pages.client.person.index', compact('data', 'persons', 'countries'));
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
    public function store(StorePersonClientRequest $request)
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
            $personClient = PersonClient::create([
                'name' => $request->name,
                'national_id' => $request->national_id,
                'mobile' => $request->mobile,
                'address_id' => $address->id,
            ]);

            // Notification part
            $url = route('show_person_Client_approve', $personClient->id);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'person_clients',
                'record_id' => $personClient->id,
            ]);
            
            // set content
            $notification->update([
                'content' => auth()->user()->username . ' انشأ عميل شخص ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$personClient->name</a>",
            ]);

            DB::commit();

            Toastr::success(trans('site.client_success_add'), trans("site.success"));
            return redirect()->route('personClient.index');
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
        $data = PersonClient::findOrFail($id);
        $persons = PersonClient::orderBy('id', 'DESC')->get();
        $countries = Country::all();

        return view('pages.client.person.index', compact('persons', 'data', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePersonClientRequest $request, $id)
    {

        $personClient = PersonClient::findOrFail($id);
        // Start transaction
        DB::beginTransaction();

        try {
            $address = $personClient->address;
            $address->update([
                'country_id' => $request->country_id,
                'region_city' => $request->region_city,
                'street' => $request->street,
                'building_no' => $request->building_no,
            ]);

            $personClient->update([
                'name' => $request->name,
                'national_id' => $request->national_id,
                'mobile' => $request->mobile,
            ]);

            // Notification part
            $url = route('show_person_Client_approve', $personClient->id);

            $personClient->update([
                'approved' => 0,
            ]);

            Notification::where('table_name', 'person_clients')->where('record_id', $personClient->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'person_clients',
                'record_id' => $personClient->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بتعديل عميل شخص ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$personClient->name</a>",
            ]);

            DB::commit();

            Toastr::success(trans('site.client_success_edit'), trans("site.success"));
            return redirect()->route('personClient.index');
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
            $personClient = PersonClient::findOrFail($request->person_id);
            if ($personClient->purchaseOrders->count() > 0)
                throw new \Exception('');

            $addressId = $personClient->address->id;
            $deletedPersonClient = clone $personClient;
            Address::find($addressId)->delete();

            // Notification part
            Notification::where('table_name', 'person_clients')->where('record_id', $deletedPersonClient->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'person_clients',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف عميل شخص ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Company name: $deletedPersonClient->name, Name: $deletedPersonClient->name, National ID: $deletedPersonClient->national_id, Mobile: $deletedPersonClient->mobile</div>",
            ]);

            Toastr::success(trans('site.client_success_delete'), trans("site.success"));
        } catch (\Exception $e) {
            Toastr::error(trans('site.client_delete_error'), trans("site.sorry"));
        }
        return redirect()->route('personClient.index');
    }

    public function showForApprove(Request $request, $id)
    {
        $personClient = PersonClient::findOrFail($id);
        $notification = Notification::findOrFail($request->query('n_id'));

        return view('pages.client.person.approve-show', compact('personClient', 'notification'));
    }

    public function approve_edit($id)
    {
        $personClient = PersonClient::findOrFail($id);
        $countries = Country::all();
        // redirect to index if product approved
        if ($personClient->approved)
            return redirect(route('product.index'));

        return view('pages.client.person.approve-edit', compact('personClient', 'countries'));
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

        $personClient = PersonClient::findOrFail($id);
        $personClient->update([
            'approved' => 1,
        ]);

        $url = route('show_person_Client_approve', $personClient->id);

        $notification = Notification::create([
            'content' => '',
            'table_name' => 'person_clients',
            'record_id' => $personClient->id,
            'user_id' => auth()->user()->id,
        ]);

        // set content
        $notification->update([
            'view_status' => 1,
            'content' => auth()->user()->username . ' وافق على إنشاء/تعديل عميل شخص ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$personClient->name</a>",
        ]);

        Toastr::success(trans('site.approved_success'), trans('site.success'));
        return redirect()->route('personClient.index');
    }
    
}
