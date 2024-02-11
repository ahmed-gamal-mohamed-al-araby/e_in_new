<?php

namespace App\Http\Controllers;

use App\Service_Supplier;
use App\Supplier\Supplier;
use App\Supplier\Product;
use App\Supplier\Service;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $suppliers = Supplier::all();
        $count = $suppliers->count();

        $sevices = Service::all();
        $ser_count = $sevices->count();

        $products = Product::all();
        $pros_count = $products->count();

        $users = User::all();
        $users_count=  $users->count();

      return view('index',compact('count','ser_count','pros_count','users_count'));
    }
}
