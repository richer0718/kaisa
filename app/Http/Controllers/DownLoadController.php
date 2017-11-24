<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownLoadController extends Controller
{
    //
    public function index($code){
        return view('download/index') -> with([
            'code' => $code
        ]);
    }

    public function android(){
        $file = public_path().'/apk/kaisa.apk';
        return response()->download($file,'kaisa.apk');
    }
    public function ios(){
        $file = public_path().'/apk/H5C80117E_1125002713.ipa';
        return response()->download($file,'H5C80117E_1125002713.ipa');
    }

}
