<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use Session;
use Redirect;
use PDF;
use URL;

class ForgetPassController extends Controller {

  function lupaPassword() {
    $email = Input::get('email');

    $usr = DB::table('users')->where('email', $email);
    if($usr->count() > 0) {
      $userId = $usr->first()->id;
      $fToken = $usr->first()->id." ".$usr->first()->email." ".rand(0, 1000); // Saya menggabungkan id, email, dan angka random untuk membuat forget token
      $fToken = hash("SHA256", $fToken);
      $usr->update(['forget_token' => $fToken]);

      $html_link = "<a href='".URL::to('/lupapassword/'.$fToken)."'>Klik disini untuk mengganti password anda</a>";

      $pdf = PDF::loadhtml($html_link);
      $pdf->setPaper('A4', 'landscape');
      return $pdf->download("link_ganti_password_".now().".pdf"); // Saya pakai DOMPDF
    } else {
      return view('lupapassword'); // Jika email tidak terdaftar. Silahkan kasih pesan error sendiri.
    }
  }

  function gantiPassword($ftoken) {
    $password = Input::get('password');
    $repeat = Input::get('passwordRepeat');
    if($repeat == $password) {
      $password = bcrypt($password);
      DB::table('users')->where('forget_token', $ftoken)->update(['password' => $password]);
      DB::table('users')->where('forget_token', $ftoken)->update(['forget_token' => null]);
      return view('lupapassword'); // Password berhasil diganti. Silahkan kasih pesan sendiri
    } else {
      return Redirect::to('/lupapassword/'.$ftoken); // Error jika password yang dimasukkan tidak sama. Silahkan kasih pesan error sendiri
    }
  }

}
