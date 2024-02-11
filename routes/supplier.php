<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::group(['namespace'=>'Supplier'],function(){
//     Route::group(['prefix' => 'supplier'],function(){

//             // Supplier
//             Route::get('/', 'Supplier@index')->name('supplier');
//             Route::get('archive', 'Supplier@index')->name('archive_suppliers');
//             Route::get('profile/{id}', 'Supplier@get_profile')->name('profile');
//             Route::get('add', 'Supplier@create')->name('supplier.add');
//             Route::post('store', 'Supplier@store')->name('supplier.store');

//             Route::get('edit/{id}', 'Supplier@edit')->name('supplier.edit');
//             Route::post('update/{id}', 'Supplier@update')->name('supplier.update');

//             Route::delete('sup_archive/{id}', 'Supplier@sup_archive')->name('supplier.sup_archive');
//             Route::delete('sup_restore/{id}', 'Supplier@sup_restore')->name('supplier.sup_restore');

//             Route::get('company_search', 'Supplier@company_search')->name('supplier.search');

//             // ajax select box
//             Route::get('getProductsFromService/{id}', 'Supplier@returnCat');
//             Route::get('edit/getProductsFromService/{id}', 'Supplier@returnCat');

//     });
//     Route::resource('/service', 'ServiceController');
//     Route::resource('/product', 'ProductController');
// });







