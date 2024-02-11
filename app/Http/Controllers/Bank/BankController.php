<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankRequest;
use App\Models\Bank;
use App\Models\Notification;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::where('approved', 1)->get();
        $user = auth()->user();
        return view('pages.bank.index', compact('banks', 'user'));
    } // end of index

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.bank.create');
    } // end of create

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankRequest $request)
    {
        $bank = Bank::create($request->all());
        $url = route('show_bank_approve', $bank->id);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'a',
            'table_name' => 'banks',
            'record_id' => $bank->id,
        ]);

        // set content
        $notification->update([
            'content' => auth()->user()->username . ' انشأ بنك جديد' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$bank->bank_name</a>",
        ]);

        Toastr::success(trans('site.bank_added_successfully'), trans('site.success'));
        return redirect()->route('bank.index');
    } // end of store

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bank = Bank::findOrFail($id);
        return view('pages.bank.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        return view('pages.bank.edit', compact('bank'));
    } // end of edit

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBankRequest $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $url = route('show_bank_approve', $bank->id);

        $bank->update($request->all());
        $bank->update([
            'approved' => 0,
        ]);

        Notification::where('table_name', 'banks')->where('record_id', $bank->id)->update(['view_status' => 1, 'type' => 'n', 'updated_at' => DB::raw('updated_at')]);

        $notification = Notification::create([
            'content' => '',
            'user_id' => auth()->user()->id,
            'type' => 'a',
            'table_name' => 'banks',
            'record_id' => $bank->id,
        ]);

        // set content
        $notification->update([
            'content' => auth()->user()->username . ' قام بتعديل بنك ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">$bank->bank_name</a>",
        ]);

        Toastr::success(trans('site.bank_updated_successfully'), trans('site.success'));

        return redirect()->route('bank.index');
    } // end of update

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // 
    }

    public function showForApprove(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $notification = Notification::findOrFail($request->query('n_id'));

        return view('pages.bank.approve-show', compact('bank', 'notification'));
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

        $bank = Bank::findOrFail($id);
        $bank->update([
            'approved' => 1,
        ]);

        $url = route('bank.show', $bank->id);

        $notification = Notification::create([
            'content' => '',
            'table_name' => 'banks',
            'record_id' => $bank->id,
            'user_id' => auth()->user()->id,
        ]);

        // set content
        $notification->update([
            'view_status' => 1,
            'content' => auth()->user()->username . ' وافق على إنشاء/تعديل بنك ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url\">$bank->bank_name</a>",
        ]);

        Toastr::success(trans('site.approved_success'), trans('site.success'));
        return redirect()->route('bank.index');
    }
    
}
