<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Role;
use App\Supplier\Prod_Supplier;
use App\Supplier\Product;
use App\Supplier\Service;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function create(){
        $users_count = User::count();
        $roles = Role::all();
        $companies = Company::all();
        return view('pages.users.create',compact('users_count','roles','companies'));
    }

    public function index(){

        $users = User::all();

        $users_count=  $users->count();

        return view('pages.users.users',compact('users','users_count'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_id' => ['required'],

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validator($request->all())->validate();
       $user = User::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'company_id'=>$request->company_id,
            'password'=>Hash::make($request->password),
        ]);

        $user->attachRole($request->role);
        return redirect()->route('users.index');
    }


    public  function  edit($id){
        $user = User::find($id);
        $roles = Role::all();
        $companies = Company::all();
        return view('pages.users.edit',compact('user', 'roles','companies'));
    }

    public function update(Request $request,$id){
        // dd($request->all());
        $user = User::findOrFail($id);
        $input = $request->except(['_method', '_token', 'role']);
        $user->update($input);
        // $user->attachRole($request->role);



        Toastr::success(trans('site.users_success_edit'),trans('site.success'));
        return redirect('/users');

    }

    public function showResetPassword ($id = null)
    {
        $user = null;
        if($id)
            $user = User::findOrFail($id);
        $user = auth()->user();
        return view('pages.users.resetPassword',compact('user'));
    }

    public function resetPassword(Request $request,$id){
        $user = User::findOrFail($id);
        $request->validate([
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6',
        ]);
        $user->update([
            'password'=>Hash::make($request->password),
        ]);

        Toastr::success(trans('site.users_success_edit'),trans('site.success'));
        return redirect('/users');
    }

    public function  getProfile(Request $request,$id){
        $user = User::find($id);
        $users_count=  User::all()->count();
        return view('pages.users.profile',compact('user','users_count'));
    }
}
