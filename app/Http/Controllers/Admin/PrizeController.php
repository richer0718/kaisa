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

        }
        return view('admin/prize/index') -> with([
            'res' => $res
        ]);
    }
}
