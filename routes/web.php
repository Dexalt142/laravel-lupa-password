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

Route::get('/lupapassword', function() {
  return view('lupapassword'); // Page untuk memasukan email
});

Route::get('/lupapassword/{ftoken}', function($ftoken) {
  $forgetToken = DB::table('users')->where('forget_token', $ftoken);
  if($forgetToken->count() < 1) {
    return view('lupapassword'); // Jika forget token tidak ditemukan
  } else {
    return view('changepassword')->with('ftoken', $ftoken); // Jika forget token ditemukan
  }
});

Route::post('/lupapassword', 'ForgetPassController@lupaPassword');
Route::post('/lupapassword/{ftoken}', 'ForgetPassController@gantiPassword');

