<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/user', function (Request $request) {
//     return ["tes"];
// });

// Route::get('/user', 'App\Http\Controllers\API\UserController@index');
Route::resource('/user', 'App\Http\Controllers\API\UserController');
Route::resource('/website', 'App\Http\Controllers\API\WebsiteController');
Route::resource('/post', 'App\Http\Controllers\API\PostController');

Route::get('/subscription', 'App\Http\Controllers\API\SubscriptionController@index');
Route::post('/subscribe', 'App\Http\Controllers\API\SubscriptionController@subscribe');
Route::delete("/unsubscribe/{subscription_id}", "App\Http\Controllers\API\SubscriptionController@unsubscribe" );

Route::get('test/email', function(){
  
	$send_mail = 'Alfalah.Madukubah@hashmicro.com';
  
    dispatch(new App\Jobs\SendEmailJob($send_mail));
  
    dd('send mail successfully !!');
});