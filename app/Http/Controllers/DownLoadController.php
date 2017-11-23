<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownLoadController extends Controller
{
    //
    public function index($code){
        return view('download/index');
    }
}
