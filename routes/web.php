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

Route::middleware('auth')->group(function (){

    Route::get("/clients", "ClientController@get")->name("clients");

    Route::get("/client", "ClientController@get_form")->name("client_form");

    Route::get('/client/{id}', 'ClientController@get_by_id')->where('id', '[0-9]+')->name('client');

    Route::put('/client/{id}', 'ClientController@update')->where('id', '[0-9]+')->name('update_client');

    Route::post("/client", 'ClientController@create')->name("create_client");

    Route::get('/client/delete/{id}', 'ClientController@delete')->where('id', '[0-9]+')->name('delete_client');

    Route::get("/logout", "ProfileController@logout")->name("logout");

    Route::get("/profile", "ProfileController@get")->name("get_profile");

    Route::post("/profile", "ProfileController@update")->name("update_profile");

    Route::get("/", "ClusterController@get")->name("clusters");

    Route::get("/cluster", "ClusterController@get_form")->name("cluster_form");

    Route::get('/cluster/{id}', 'ClusterController@get_by_id')->where('id', '[0-9]+')->name('cluster');

    Route::put('/cluster/{id}', 'ClusterController@update')->where('id', '[0-9]+')->name('update_cluster');

    Route::post("/cluster", 'ClusterController@create')->name("create_cluster");

    Route::get("/cluster/delete/{id}", 'ClusterController@delete')->where('id', '[0-9]+')->name('delete_cluster');

    Route::get("/client-images", "ImageController@get")->name("images");

    Route::get("/client-image", "ImageController@get_form")->name("image_form");

    Route::get('/client-image/{id}', 'ImageController@get_by_id')->where('id', '[0-9]+')->name('image');

    Route::put('/client-image/{id}', 'ImageController@update')->where('id', '[0-9]+')->name('update_image');

    Route::post("/client-image", 'ImageController@create')->name("create_image");

    Route::get("/client-image/delete/{id}", 'ImageController@delete')->where('id', '[0-9]+')->name('delete_image');

    Route::get("/pending-approvals", 'PendingApprovalController@get')->name('pending_approvals');

    Route::get("/pending-approval/{id}", 'PendingApprovalController@get_by_id')->where('id', '[0-9]+')->name('pending_approval');

    Route::get("/pending-approval/delete/{id}", 'PendingApprovalController@delete')->where('id', '[0-9]+')->name('delete_pending_approval');

    Route::post("/pending-approval/approve", 'ClientController@approve')->name('approve_pending_approval');

});