<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 开奖管理
 * Class PrizeController
 * @package App\Http\Controllers\Admin
 */
class PrizeController extends Controller
{
    //
    public function index(){
        $res = DB::table('openprize') -> orderBy('id','desc') -> paginate(15);
        foreach($res as $k => $vo){
            //投注点数
            $res[$k] ->  point = DB::table('touzhu') -> where([
                'number' => $vo -> id
            ]) -> sum('point');
            //盈利情况
            $res[$k] ->  shouru = DB::table('tradelog') -> where([
                'qishu_id' => $vo -> id
            ]) -> first();


        }
        //dd($res);
        return view('admin/prize/index') -> with([
            'res' => $res
        ]);
    }

    public function dangwei(Request $request){
        if($request -> input('dangwei')){
            DB::table('dangwei') -> where([
                'id' => 1
            ]) -> update([
                'number' => $request -> input('dangwei')
            ]);
        }

        $dangwei = DB::table('dangwei') -> first();
        return view('admin/prize/dangwei') -> with([
            'dangwei' => $dangwei -> number
        ]);
    }
}
