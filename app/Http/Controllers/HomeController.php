<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BusinessClient;
use App\Models\City;
use App\Models\Country;
use App\Models\Document;
use App\User;
use App\Models\ForeignerClient;
use App\Models\PersonClient;
use App\Models\Product;
use App\Models\PurchaseOrder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clients_count = BusinessClient::count() + PersonClient::count() + ForeignerClient::count();
        $users_count=  User::count();
        $po_count = PurchaseOrder::where('approved',1)->count();
        $document_count = Document::where('approved',1)->count();
        $bank_count = Bank::where('approved',1)->count();
        $product_count = Product::where('approved',1)->count();
        $country_count = Country::count();
        $city_count = City::count();

        return view('index',compact('clients_count' ,'users_count','po_count','document_count','city_count','country_count',
        'product_count','bank_count'));

    }
}
