<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //
    public function login(){
        return view('admin/login');

    }
    public function loginout(Request $request){
        $request->session()->flush();
        return redirect('admin/login');
    }

    public function loginRes(Request $request){
        $username = $request -> input('username');
        $password = $request -> input('password');
        $res = DB::table('admin') -> where([
            'username'=>$username,
            'password'=>$password,
            //'type' => '1'
        ]) -> first();
        //dd($res);
        $res = (array)$res;
        if($res){

            session([
                'admin_username' => $res['username'],
                //'type' => 'manage',
            ]); //储存登陆标志

            return redirect('admin/index')->with('status', 'success');
        }else{
            return redirect('admin/login')->with('status', 'error');
        }
        //var_dump($res);
    }

    public function index(Request $request){
        //dd(session('username'));
        return view('admin/index');
    }
}
