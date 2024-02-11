<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessClientRequest;
use App\Models\Address;
use App\Models\BusinessClient;
use App\Models\BusinessClientUser;
use App\Models\Country;
use App\Models\Notification;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class businessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $businessClients = BusinessClient::where('archive', 0)->paginate(env('PAGINATION_LENGTH', 5));
        // $businessClients = BusinessClient::where('archive', 1)->where('approved', 1)->orderBy('id', 'DESC')->get();
        return view('pages.client.business.index', compact('businessClients'));
    }

    public function archive_index()
    {
        $businessClients = BusinessClient::where('archive', 1)->paginate(env('PAGINATION_LENGTH', 5));

        return view('pages.client.business.archive', compact('businessClients'));
    }

    function fetch_data(Request $request)
    {
        $length = request()->length ?? env('PAGINATION_LENGTH', 5);
        $searchContent = request()->search_content ?? '';
        $pageType = request()->page_type;
        $businessClients = [];
        if ($request->ajax()) {
            if ($pageType == 'index') {
                if ($length == -1) {
                    $length = BusinessClient::where('archive', 0)->count();
                }
                if (strlen($searchContent)) {
                    $businessClients = BusinessClient::where('archive', 0)->where('name', 'like', '%' . $searchContent . '%')
                                                        ->orWhere('name', 'like', '%' . $searchContent . '%')
                                                        ->paginate($length);
                } else {
                    $businessClients = BusinessClient::where('archive', 0)->paginate($length);
                }
            } else if ($pageType == 'archived') {
                if ($length == -1) {
                    $length = BusinessClient::where('archive', 1)->count();
                }
                if (strlen($searchContent)) {
                    $businessClients = BusinessClient::where('archive', 1)
                        ->where(function ($query) use ($searchContent) {
                            return $query->where('name', 'like', '%' . $searchContent . '%')
                                            ->orWhere('name', 'like', '%' . $searchContent . '%');
                        })->paginate($length);
                } else {
                    $businessClients = BusinessClient::where('archive', 1)->paginate($length);
                }
            }

            return view('pages.client.business.pagination_data', compact('businessClients', 'pageType'))->render();
        }
    } // end of fetch data function


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();

        return view('pages.client.business.add_client', compact('countries'));
    }


    public function storeFile($getClientOriginalExtension, $name = null)
    {
        if ($name)
            return $name . '.' . $getClientOriginalExtension;
        else
            $name = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());

        return $name . '.' . $getClientOriginalExtension;
    }

    public function nameFiles($request, &$input, $id)
    {
        // Logo is [not required]
        $input['logo'] = null;
        if ($request->hasFile('logo')) {
            $input['logo'] = $this->storeFile($request->logo->getClientOriginalExtension(), $id);
        }

        // tax id number file is [required]
        $input['tax_id_number_file'] = null;
        if ($request->hasFile('tax_id_number_file')) {
            $input['tax_id_number_file'] = $this->storeFile($request->tax_id_number_file->getClientOriginalExtension(), $id);
        }

        // commercial registeration number file is [required]
        $input['commercial_registeration_number_file'] = null;
        if ($request->hasFile('commercial_registeration_number_file')) {
            $input['commercial_registeration_number_file'] = $this->storeFile($request->commercial_registeration_number_file->getClientOriginalExtension(), $id);
        }

        // tax_file_number_file is [not required]
        $input['tax_file_number_file'] = null;
        if ($request->hasFile('tax_file_number_file')) {
            $input['tax_file_number_file'] = $this->storeFile($request->tax_file_number_file->getClientOriginalExtension(), $id);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBusinessClientRequest $request)
    {
        $input = [];
        // Start transaction
        DB::beginTransaction();

        // businessClient
        $address = Address::create([
            'country_id' => $request->country_id,
            'city_id'  => $request->city_id,
            'region_city' => $request->region_city,
            'street' => $request->street,
            'building_no' => $request->building_no,
        ]);

        $businessClient = BusinessClient::create([
            'name' => $request->name,
            'fax' => $request->fax,
            'address_id' => $request->address_id,
            'gmap_url' => $request->gmap_url,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'website_url' => $request->website_url,
            'person_note' => $request->person_note,
            'accredite_note' => $request->accredite_note,
            'tax_id_number' => $request->tax_id_number,
            'commercial_registeration_number' => $request->commercial_registeration_number,
            'tax_file_number' => $request->tax_file_number,
            'address_id' => $address->id,
        ]);

        // Name the files
        $this->nameFiles($request, $input, $businessClient->id);

        // supplier add files if uploaded that it's name depend on supplierId
        $businessClient->update([
            'logo' => $input['logo'],
        ]);

        if (!$businessClient) // if supplier don't recorded in database
            DB::rollBack();
        else {
            try {
                if ($request->exists('persons')) {
                    // persons
                    foreach ($request->persons as $key => $person) {
                        BusinessClientUser::create([
                            'name' => $person['name'],
                            'job' => $person['job'],
                            'mobile' => $person['mobile'],
                            'whatsapp' => $person['whatsapp'],
                            'national_id' => $person['national_id'],
                            'email' => $person['email'],
                            'business_client_id' =>   $businessClient->id,
                        ]);
                    }
                }
                // Store files
                if ($request->hasFile('logo'))
                    $request->logo->move(public_path('client/business/logo'), $input['logo']);
                if ($request->hasFile('tax_id_number_file'))
                    $request->tax_id_number_file->move(public_path('client/business/tax_id_number/'), $input['tax_id_number_file']);
                if ($request->hasFile('commercial_registeration_number_file'))
                    $request->commercial_registeration_number_file->move(public_path('client/business/commercial_registeration_number/'), $input['commercial_registeration_number_file']);
                if ($request->hasFile('tax_file_number_file')) {
                    $request->tax_file_number_file->move(public_path('client/business/tax_file_number'), $input['tax_file_number_file']);
                }

                // Notification part
                $url = route('show_business_client_approve', $businessClient->id);

                $notification = Notification::create([
                    'content' => '',
                    'user_id' => auth()->user()->id,
                    'type' => 'a',
                    'table_name' => 'business_clients',
                    'record_id' => $businessClient->id,
                ]);

                // set content
                $notification->update([
                    'content' => auth()->user()->username . ' انشأ عميل شركة ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$businessClient->name</a>",
                ]);

                DB::commit();

                Toastr::success(trans('site.businessClient_success_added'), trans('site.success'));
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                Toastr::error($e->getMessage(), trans("site.sorry"));
            }
            return redirect()->route('businessClients.index');
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
        $businessClient = BusinessClient::findOrFail($id);

        $responsibePersons = $businessClient->businessClientUser;
        $businessClient_country = $businessClient->address->country;
        $countries = Country::all();
        return view('pages.client.business.edit_client', compact(
            'businessClient',
            'responsibePersons',
            'businessClient_country',
            'countries'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(StoreBusinessClientRequest $request, $id)
    {
        $businessClient = BusinessClient::findOrFail($id);

        $input = [];

        // Start transaction
        DB::beginTransaction();

        try {
            $address = $businessClient->address;
            $address->update([
                'country_id' => $request->country_id,
                'city_id'  => $request->city_id,
                'region_city' => $request->region_city,
                'street' => $request->street,
                'building_no' => $request->building_no,
            ]);

            $businessClient->update([
                'name' => $request->name,
                'fax' => $request->fax,
                'gmap_url' => $request->gmap_url,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'website_url' => $request->website_url,
                'person_note' => $request->person_note,
                'accredite_note' => $request->accredite_note,
                'tax_id_number' => $request->tax_id_number,
                'commercial_registeration_number' => $request->commercial_registeration_number,
                'tax_file_number' => $request->tax_file_number,
            ]);

            $businessClientUser = BusinessClientUser::where('business_client_id', $businessClient->id);
            $businessClientUser->delete();

            // persons
            if ($request->exists('persons')) {
                foreach ($request->persons as $key => $person) {
                    BusinessClientUser::create([
                        'name' => $person['name'],
                        'job' => $person['job'],
                        'mobile' => $person['mobile'],
                        'whatsapp' => $person['whatsapp'],
                        'national_id' => $person['national_id'],
                        'email' => $person['email'],
                        'business_client_id' =>   $businessClient->id,
                    ]);
                }
            }

            // Name the files
            $this->nameFiles($request, $input, $businessClient->id);
            // Store new file and remove old files
            if ($request->hasFile('logo')) {

                // File::delete('client/business/logo/' . $businessClient->logo);
                // $oldFilePath = public_path('client/business/logo/' . $businessClient->logo);
                $oldFilePath = url('client/business/logo/' . $businessClient->logo);
                if (file_exists($oldFilePath))
                    unlink($oldFilePath);
                $request->logo->move(public_path('client/business/logo'), $input['logo']);
                $businessClient->update([
                    'logo' => $input['logo'],
                ]);
            }
            if ($request->hasFile('tax_id_number_file')) {
                // File::delete('client/business/tax_id_number/' . $input['tax_id_number_file']);
                // $oldFilePath = public_path('client/business/commercial_registeration_number/' . $input['commercial_registeration_number_file']);
                $oldFilePath = url('client/business/tax_id_number/' . $input['tax_id_number_file']);
                if (file_exists($oldFilePath))
                    unlink($oldFilePath);
                $request->tax_id_number_file->move(public_path('client/business/tax_id_number/'), $input['tax_id_number_file']);
            }
            if ($request->hasFile('commercial_registeration_number_file')) {
                // File::delete('client/business/commercial_registeration_number/' . $input['commercial_registeration_number_file']);
                // $oldFilePath = public_path('client/business/commercial_registeration_number/' . $input['commercial_registeration_number_file']);
                $oldFilePath = url('client/business/commercial_registeration_number/' . $input['commercial_registeration_number_file']);
                if (file_exists($oldFilePath))
                    unlink($oldFilePath);
                $request->commercial_registeration_number_file->move(public_path('client/business/commercial_registeration_number/'), $input['commercial_registeration_number_file']);
            }
            if ($request->hasFile('tax_file_number_file')) {
                // File::delete('client/business/tax_file_number/' . $input['tax_file_number_file']);
                // $oldFilePath = public_path('client/business/tax_file_number/' . $input['tax_file_number_file']);
                $oldFilePath = url('client/business/tax_file_number/' . $input['tax_file_number_file']);
                if (file_exists($oldFilePath))
                    unlink($oldFilePath);
                $request->tax_file_number_file->move(public_path('client/business/tax_file_number'), $input['tax_file_number_file']);
            }

            // Notification part
            $url = route('show_business_client_approve', $businessClient->id);

            $businessClient->update([
                'approved' => 0,
            ]);

            Notification::where('table_name', 'business_clients')->where('record_id', $businessClient->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'a',
                'table_name' => 'business_clients',
                'record_id' => $businessClient->id,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بتعديل عميل شركة ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$businessClient->name</a>",
            ]);

            DB::commit();

            Toastr::success(trans('site.businessClient_success_edit'), trans('site.success'));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Toastr::error(trans("site.sorry"));
        }
        return redirect()->route('businessClients.index');
    }

    public function company_search()
    {
        $search_content = $_GET['search_content'];

        $result = explode(" ", $search_content);
        $realResult = collect(array_values(array_diff($result, [""])));

        $size = count($realResult);
        $businessClients = collect();

        for ($i = 0; $i < $size; $i++) {
            $data = BusinessClient::where('name', 'like', '%' . $realResult[$i] . '%')->where('approved', 1)->get();
            $temp = collect([
                'id' => 0,
                'name' => 0,
                'country' => '',
                'city' => '',
                'address' => 0,
                'created_at' => 0,
                'archive' => 0,
            ]);

            for ($j = 0; $j < count($data); $j++) {
                $temp->put('id', $data[$j]->id);
                $temp->put('name', $data[$j]->name);
                $temp->put('country', $data[$j]->address->country->name);
                $temp->put('city', $data[$j]->address->city->name);
                $temp->put('address', $data[$j]->address->country->name . ' ,' . $data[$j]->address->city->name . ', ' . $data[$j]->address->region_city . ', ' . $data[$j]->address->street . ', ' . $data[$j]->address->building_no);
                $temp->put('created_at', $data[$j]->created_at);
                $temp->put('archive', $data[$j]->archive);

                $businessClients->push(clone $temp);
            }
        }
        $uniqueClients = $businessClients->unique();
        $data = $uniqueClients->toArray();

        return $data = array(
            'businessClients' => $uniqueClients,
        );
    }


    public function get_profile($id)
    {
        $businessClient = BusinessClient::where('id', $id)->where('approved', 1)->firstOrFail();
        $archive = BusinessClient::select('archive')->where('id', $id)->get();

        $persons = BusinessClient::find($id)->businessClientUser;
        $preson_count = BusinessClient::find($id)->businessClientUser->count();

        return view('pages.client.business.profile', compact('businessClient', 'persons', 'preson_count', 'archive'));
    }

    public function businessClients_archive(Request  $request)
    {
        // dd($request->businessClient_id);
        $errorMessage = '';
        $status = null;
        $client = BusinessClient::findOrFail($request->businessClient_id);
        try{
            $client->update([
                'archive' => true,
            ]);
            $status = true;
        }catch (\Illuminate\Database\QueryException $e) { // Handle integrity constraint violation
            // $availableToDelete = false;
            if ($e->errorInfo[0] == 23000) {
                // $errorMessage = '';
                $errorMessage = $e->getMessage();
            } else {
                $errorMessage = 'DB error';
            }
        }
        // Toastr::success(trans('site.client_archive_success'), trans('site.success'));
        // return back();
        return json_encode([
            'status' => $status,
            'errorMessage' => $errorMessage,
        ]);
    }

    public function businessClients_restore(Request  $request)
    {
        // dd($request->businessClient_id);
        $errorMessage = '';
        $status = null;
        $client = BusinessClient::findOrFail($request->businessClient_id);
        try{
            $client->update([
                'archive' => false,
            ]);
            $status = true;
        }catch (\Illuminate\Database\QueryException $e) { // Handle integrity constraint violation
            // $availableToDelete = false;
            if ($e->errorInfo[0] == 23000) {
                // $errorMessage = '';
                $errorMessage = $e->getMessage();
            } else {
                $errorMessage = 'DB error';
            }
        }
        // Toastr::success(trans('site.client_archive_success'), trans('site.success'));
        // return back();
        return json_encode([
            'status' => $status,
            'errorMessage' => $errorMessage,
        ]);
    }

    public function permanent_delete(Request $request)
    {
        // dd($request->businessClient_id);
        $errorMessage = '';
        $status = null;
        try {
            $data = BusinessClient::findOrFail($request->businessClient_id);
            $deletedBusinessClient = clone $data;

            $data->delete();
            $status = true;
            // Notification part
            Notification::where('table_name', 'business_clients')->where('record_id', $deletedBusinessClient->id)->delete();

            $notification = Notification::create([
                'content' => '',
                'user_id' => auth()->user()->id,
                'type' => 'n',
                'table_name' => 'business_clients',
                'record_id' => -1,
            ]);

            // set content
            $notification->update([
                'content' => auth()->user()->username . ' قام بحذف عميل شركة ' . "<div class='alert alert-danger mb-0 mt-2 text-left'> Purchase Order reference: $deletedBusinessClient->tax_id_number</div>",
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[0] == 23000)
                $errorMessage = $e->getMessage();
            else
                $errorMessage = 'DB error';
        }

        return json_encode([                                            //  Error Response
            'status' => $status,
            'errorMessage' => $errorMessage,
        ]);

    } // end of permanent delete

    public function showForApprove(Request $request, $id)
    {
        $businessClient = BusinessClient::findOrFail($id);

        $persons = BusinessClient::find($id)->businessClientUser;
        $preson_count = BusinessClient::find($id)->businessClientUser->count();
        $notification = Notification::findOrFail($request->query('n_id'));

        return view('pages.client.business.approve-show', compact('businessClient', 'persons', 'preson_count', 'notification'));
    }

    public function approved(Request $request, $id)
    {
        // dd($request->all());
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

        $businessClient = BusinessClient::findOrFail($id);
        $businessClient->update([
            'approved' => 1,
        ]);

        $url = route('businessClients.profile', $businessClient->id);

        Notification::where('table_name', 'business_clients')->where('record_id', $businessClient->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'table_name' => 'business_clients',
            'record_id' => $businessClient->id,
        ]);

        // set content
        $notification->update([
            'view_status' => 1,
            'content' => auth()->user()->username . ' وافق على إنشاء/تعديل عميل شركة' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url\">$businessClient->name</a>",
        ]);

        Toastr::success(trans('site.approved_success'), trans('site.success'));
        return redirect()->route('businessClients.index');
    }

}
