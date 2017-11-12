<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 用户管理
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    //
    public function index(){
        $res = DB::table('user') -> get();
        return view('admin/user/index') -> with([
            'res' => $res
        ]);
    }

    public function reg(){

    }

}
