<?php

use App\Models\User;
use App\Models\Sale;
use Illuminate\Support\Facades\Mail;
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

Route::get('/test', function () {
    $customers = User::with('sales')->get()->toArray();
    dd($customers);
    return "Hello World";
});

Route::get('/mailtest', function () {
  Mail::send([], [], function($message){
    $message->to('mimranqureshi54@gmail.com')
      ->subject('Mail Test')
      ->setBody('Hello World');
  });
  return "Hello World";
});
