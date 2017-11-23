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
}
