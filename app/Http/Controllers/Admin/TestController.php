<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    //
    public function index(){
        $config = config('kaisa.options');
        dd($config);
    }

    public function getData(){
        if (Cache::has('key')) {
            //
            echo '123';
        }

        $value = Cache::get('key');
        dd($value);
    }
}
