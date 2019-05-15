<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::group(['as'=>'admin.', 'prefix' => 'admin', 'namespace'=>'Admin'], function () {

    Route::resource('area', 'AreaController');
    Route::resource('category', 'CategoryController');
    Route::resource('sub-category', 'SubCategoryController');
});



