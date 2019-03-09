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

Auth::routes(['register' => false]);

Route::get('test', function (){
    \App\User::create([
        'name' => "admin",
        'email' => 'test@test.com',
        'password'=> \Illuminate\Support\Facades\Hash::make("123456")
    ]);
});

Route::middleware('auth')->group(function (){

    Route::get("/", "ClientController@get")->name("clients");

    Route::get("/client", "ClientController@get_form")->name("client_form");

    Route::get('/client/{id}', 'ClientController@get_by_id')->where('id', '[0-9]+')->name('client');

    Route::put('/client/{id}', 'ClientController@update')->where('id', '[0-9]+')->name('update_client');

    Route::post("/client", 'ClientController@create')->name("create_client");

    Route::get("/logout", "ProfileController@logout")->name("logout");

    Route::get("/profile", "ProfileController@get")->name("get_profile");

    Route::post("/profile", "ProfileController@update")->name("update_profile");

});