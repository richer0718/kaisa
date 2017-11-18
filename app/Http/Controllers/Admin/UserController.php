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
        //echo 1;exit;
        $res = DB::table('user') -> paginate(15);
        //dd($res);
        //总人数
        $count = DB::table('user') -> count();
        return view('admin/user/index') -> with([
            'res' => $res,
            'count' => $count
        ]);
    }

    //兑换
    public function duihuan(Request $request){
        //判断必填
        $point = $request -> input('point');
        $openid = $request -> input('openid');
        if($point && $openid){
            //判断 openid 点数
            $userinfo = DB::table('user') -> where([
                'openid' => $openid
            ]) -> first();
            if($point <= $userinfo -> point){
                //可以兑换
                //存入兑换表李，然后给他退掉。
                DB::table('duihuan_log') -> insert([
                    'openid' => $openid,
                    'point' => $point,
                    'created_at' => time()
                ]);
                DB::table('user') -> where([
                    'openid' => $openid
                ]) -> update([
                    'point' => $userinfo -> point - $point
                ]);

                return redirect('admin/user') -> with('duihuan','success');

            }else{
                return false;
            }

        }
    }

    public function userlog($id){

    }

}
