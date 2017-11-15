<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use AopClient;
use AlipayTradeAppPayRequest;

class ApiController extends Controller
{

    protected $config = [
        'alipay' => [
            'app_id' => '2017110909830350',
            'partner' => '2088621908302474',
            //'app_id' => '2016081500253568',
            //'notify_url' => 'http://jhqck.com/kaisa/public/api/alipay_notify',
            'return_url' => 'http://jhqck.com/kaisa/public/api/alipay_notify',
            //'ali_public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDhQ/VT3joAmUTtD0KpZl87M1YYa6oDIEBzPMScYuC958TkV7AZ7UbEzJNrlqQ4NbBmLPltrqsgceP5X0c7qyafoFby+PMKOP+6PRYNTqIrp3mbLCaLD6fF10XYrmJ6hhEndLQKz4JR9i6wkGUwvwJ8gSX52VDgYnimv9Cy71KoPQIDAQAB',
            //支付宝公钥
            'ali_public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB',
            'private_key' => 'MIICXQIBAAKBgQDhQ/VT3joAmUTtD0KpZl87M1YYa6oDIEBzPMScYuC958TkV7AZ7UbEzJNrlqQ4NbBmLPltrqsgceP5X0c7qyafoFby+PMKOP+6PRYNTqIrp3mbLCaLD6fF10XYrmJ6hhEndLQKz4JR9i6wkGUwvwJ8gSX52VDgYnimv9Cy71KoPQIDAQABAoGAV/SF9LI/aX5u2DTuLWCIbIAV7MEVB9Vu9M/UYM+Guv+k9Bd87hKkYDEUmpyeEEh+UNbcqUPbE3cEsZjPInAoSs3zOz62/L3XYzmA//EKBQtj1y5AJZHYqo9RL+M6FM5fJlq3xMkIuNfBokC+h2aw8dWPm9eShIBfXSVIi7HFna0CQQDxFZaP5ISFZ+cwWhWFXg88ryxDyfXn7oejma4HazcEl5QbC2mhGvlY1kF2llK92kXmw2v7frMo0kdmp6ERJZaDAkEA7zPSveQPOOtHlLXe72SQabORVOAAOzMZ7Sotv9jM3skSjmPgAp6r0Mrz/n+fJ8KdSDmpndmUQCTfIwAP+VCKPwJBANF7QrKRjB0nZZl8DUsvqem/BKV6rbP0bePYO4GyxcG1vDmrtwMIHzX0JjnW8NqK+UZE9GU5eI+199jZO3lcweUCQQCVjsFlGQKrg+/tewk4hJgGfs+PUb7TRNAhCQ4xtUviv7VqcefNu4eRtFN5/DF2mqfcULFMkI2wzVz2dUOHjmPhAkAfwwARrXfic2CzHULmMuIh3hoUuP14JlqU/VeCVU/AQrRob+l2eUa/I5RqYpMqFwnkNzCUhrU3T2l8pu7w054j',
        ],
    ];

//MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDhQ/VT3joAmUTtD0KpZl87M1YYa6oDIEBzPMScYuC958TkV7AZ7UbEzJNrlqQ4NbBmLPltrqsgceP5X0c7qyafoFby+PMKOP+6PRYNTqIrp3mbLCaLD6fF10XYrmJ6hhEndLQKz4JR9i6wkGUwvwJ8gSX52VDgYnimv9Cy71KoPQIDAQAB
    public function clearCache(){
        Cache::flush();
        echo 'success';
    }

    //获取最新开奖
    //客户端请求最新开奖信息
    public function getNewPrize($openid = 0){
        header('Access-Control-Allow-Origin:*');
        /*
        Cache::forever('end_number_info',[
            'prize_number' => 201711040293,
            'id' => 354,
            'create_time' => 1509770760
        ]);
        */
        //Cache::flush();
        //先取create_time(最新一期的创建时间) prize_number(最新一期的期数) id(最新一期的id)

        if( Cache::get('end_number_info') ){
            $end_number_info = Cache::get('end_number_info');


            $create_time = $end_number_info['create_time'];
            //拿到时间后，跟现在的时间差

            //第一期 期数
            $first_data = intval($end_number_info['prize_number']);
            //根据id拿历史记录
            $number2 = intval($end_number_info['id'])  - 1;
            $number3 = intval($end_number_info['id'])  - 2;

            $return_arr = [];
            //返回最新一期的期数
            $return_arr['first_number'] = $first_data;
            //最新一期期数的id
            $return_arr['first_number_id'] = $end_number_info['id'];
            //第一期已经开奖的时间
            //已经开奖的时间
            $return_arr['open_time'] = time() - $create_time;
            //获取第二期第三期数据
            $return_arr['history'][] = $this -> getOpenLog($number2);
            $return_arr['history'][] = $this -> getOpenLog($number3);

            return response() -> json($return_arr);
        }else{
            return response() -> json(['status','error']);
        }

    }

    //55秒后请求
    //用来返回最新一期的开奖信息 + 这个人剩余的点数
    public function getOpenPriceData(Request $request){
        $openid = $request -> input('openid');
        $open_number_id = $request -> input('open_number_id');
        //没开奖
        if(!Cache::get('open_number_'.$open_number_id)){
            sleep(1);
        }
        if(!Cache::get('open_number_'.$open_number_id)){
            sleep(1);
        }
        if(!Cache::get('open_number_'.$open_number_id)){
            sleep(1);
        }
        if(!Cache::get('open_number_'.$open_number_id)){
            echo 'timeout';
        }

        //先看下这期的开奖结果
        if(Cache::get('open_number_'.$open_number_id)){
            $temp = Cache::get('open_number_'.$open_number_id);
            $result = $temp['open_number'];
            /*
            $return_arr = [];
            //返回最新一期的期数
            $return_arr['first_number'] = $first_data;
            //最新一期期数的id
            $return_arr['first_number_id'] = $end_number_info['id'];
            //第一期已经开奖的时间
            //已经开奖的时间
            $return_arr['open_time'] = time() - $create_time;
            //获取第二期第三期数据
            $return_arr['history'][] = $this -> getOpenLog($number2);
            $return_arr['history'][] = $this -> getOpenLog($number3);
            return response() -> json($return_arr);
            */

        }



        //看下他投没投过这期
        $is_pay = DB::table('touzhu') -> where([
            'number' => $open_number_id,
            'openid' => $openid
        ]) -> get();
        if($is_pay){
            //如果投过，返回他的余额

        }



    }

    //自动程序生成新一期开奖
    //一分钟生成一次
    public function makeNextPrize(){
        //Cache::flush();exit;
        //生成新一期前，判断下前一期开奖了没有
        if(Cache::get('end_number_info')){
            //echo 111;exit;
            $temp = Cache::get('end_number_info');
            //dd($temp);
            //最新一期的id
            $id = $temp['id'];
            //查找最新一次的开奖情况
            if(!Cache::get('open_number_'.$id)){
                //如果没有开奖  自动开奖
                $this -> openPrize($id);
            }
        }

        //取缓存number 看生成到第几期了
        $date = date('Ymd');
        if(Cache::get('date')){
            if($date != Cache::get('date')){
                Cache::put('date',$date,1440);
            }
        }else{
            Cache::put('date',$date,1440);
        }
        
        //查看今天生成的序号
        if(Cache::get('number')){
            //dd(Cache::get('number'));
            //序号加1
            $number = Cache::increment('number');
            //生成10000个 就归零

            if($number == 10000){
                Cache::forever('number',1);
                $number = 1;
            }


        }else{
            Cache::forever('number',1);
            $number = 1;
        }
        //前边补0
        $number = sprintf('%04s', $number);

        //最终的期数
        $end_number = $date.$number;



        //把开奖期数存下来
        $id = DB::table('openprize') -> insertGetId([
            'prize_number' => $end_number,
            'is_open' => 0,
            'created_at' => time(),
        ]);

        //最新一期的信息
        Cache::forever('end_number_info',[
            'prize_number' => $end_number,
            'id' => $id,
            'create_time' => time()
        ]);
        //echo 'success';
    }

    //投注
    public function buyNumber(Request $request){
        header('Access-Control-Allow-Origin:*');
        //判断下 是否停止投注
        $is_stop = Cache::get('is_stop');
        if($is_stop == 1){
            return response() -> json(['status'=>'stop']);
        }
        //dd($request);
        //openid
        $openid = $request -> input('openid');
        //投注选项
        $option = $request -> input('btn');
        //投注点数
        $point = $request -> input('point');
        //投注期数id
        $number = $request -> input('qishu');
        if($openid && $option && $point && $number){

        //查下此 option在不在
         $options = config('kaisa.options');
         if(!in_array($option,$options)){
             //return response() -> json(['status'=>'super-error']);
         }
        //存入投注表
        DB::table('touzhu') -> insert([
            'openid' => $openid,
            'buy_option' => $option,
            'number' => $number,
            'point' => $point,
            'created_at' => time()
        ]);
            return response() -> json(['status'=>'success']);


        }else{
            return response() -> json(['status'=>'error']);
        }


    }


    //计算投注结果
    //返回三位数字
    public function jisuan($open_number_id){
        //去touzhu表中查，这期的投注
        $result = DB::table('touzhu') -> where(function($query) use($open_number_id){
            $query -> where('number','=',$open_number_id);
            $query -> where('buy_option','!=',1);
            $query -> where('buy_option','!=',2);
            $query -> where('buy_option','!=',3);
        }) -> get();
        //dd($result);
        $options = config('kaisa.options');
        //dd($options);
        $numbers = [
            0=>0,
            1=>0,
            2=>0,
            3=>0,
            4=>0,
            5=>0,
            6=>0,
            7=>0,
            8=>0,
            9=>0,
        ];
        if($result){
            foreach($result as $vo){
                //dd($vo);
                //查下此option所包含的number
                $temp = $vo->buy_option;
                $option = $options[$temp];
                //dd($option['number']);
                //没有考虑 合 的情况

                if($option['number']){
                    //每个number + 投的点数
                    //此数组里边的值，每个都加point
                    foreach($option['number'] as $vol){
                        $numbers[$vol] += intval($vo -> point) * $option['peilv'];
                    }
                }

            }
        }


        if($result){
            //先看有没有0的 有 就开0
            foreach($numbers as $key => $value){
                if($value == 0 && $key != 'he'){
                    //存放有0的数组
                    $number_zero[] = $key;
                }
            }

            if(count($number_zero)){
                //有 没有投的情况的 就随便开一个
                $rand = array_rand($number_zero,1);
                $end_number = $number_zero[$rand];
            }else{
                //比较哪个小 就开哪个。
                $numbers_copy = $numbers;
                $end_number = array_search(min($numbers_copy), $numbers_copy);
                //这边是需要出钱的，算下这边需要出多少钱
                //$money = $numbers[$end_number];
            }

            //var_dump($end_number);
        }else{
            //如果没有 就随便开一个
            $end_number = rand(0,9);
        }

        //从上边可以得出 数组场 精确场 开哪个数字发出的点数最少 $end_number
        //然后开始比较大小场 得到十位数字
        if($result){
            //比较大小合买的，哪个少
            //买大
            $result1 = DB::table('touzhu') -> where(function($query) use($open_number_id){
                $query -> where('number','=',$open_number_id);
                $query -> where('buy_option','=',1);
            }) -> sum('point');
            //买小
            $result2 = DB::table('touzhu') -> where(function($query) use($open_number_id){
                $query -> where('number','=',$open_number_id);
                $query -> where('buy_option','=',2);
            }) -> sum('point');
            //买合
            $result3 = DB::table('touzhu') -> where(function($query) use($open_number_id){
                $query -> where('number','=',$open_number_id);
                $query -> where('buy_option','=',3);
            }) -> sum('point');


            //拿下每个的赔率
            $res1 = intval($result1) * $options[1]['peilv'];
            $res2 = intval($result1) * $options[2]['peilv'];
            $res3 = intval($result1) * $options[3]['peilv'];

            if($end_number >= 5){
                //大数字
                if($res1 > $res3){
                    //大比合多 开合
                    $number_pre =  $end_number;
                }else{
                    //除了end_number 都行
                    unset($numbers[$end_number]);
                    $number_pre = array_rand($numbers,1);

                }
            }else{
                //小数字
                if($res2 > $res3){
                    //小比合多 开合
                    $number_pre =  $end_number;
                }else{
                    //除了end_number 都行
                    unset($numbers[$end_number]);
                    $number_pre = array_rand($numbers,1);

                }
            }


        }else{
            //没有人买 就随便开
            $number_pre = rand(0,9);
        }





        $return_num = $number_pre.$end_number;
        //var_dump($return_num);
        return $return_num;
    }


    //开奖
    //5秒之后请求开奖结果
    /**
     * @param $open_number_id openprize 表id
     * @return string
     */
    function openPrize($open_number_id){
        //dd(Cache::get($number));
        //触发开奖 根据期数算出开奖结果
        //如果开奖，停止下注
        Cache::forever('is_stop',1);
        //开始开奖
        //返回后三位数字
        $number_end = $this -> jisuan($open_number_id);
        //获取前6位数字
        $number_pre = rand(1256600,9999999);
        //返回期数开奖数字
        $number_res = $number_pre.$number_end;
        //通过openprize id 查找期数
        $data = DB::table('openprize') -> where([
            'id' => $open_number_id
        ]) -> first();
        //开奖结果 保存缓存
        Cache::forever('open_number_'.$open_number_id,[
            'prize_number' => $data -> prize_number,
            'open_number' => $number_res,
        ]);
        //Cache::forever();

        //获取开奖数字的时候就把三种结果都存下来
        //$result1 = $this -> getResByType($number_res,1);

        //存储开奖结果
        DB::table('openprize') -> where([
            'id' => $open_number_id
        ]) -> update([
            'is_open' => 1,
            //开奖数字
            'open_number' => $number_res
        ]);
        Cache::forever('is_stop',0);
        //var_dump($number_res);exit;
        return $number_res;
    }

    //返回历史记录
    public function getHistoryData(Request $request){
        header('Access-Control-Allow-Origin:*');
        //header('Access-Control-Allow-Credentials:true');
        //返回记录
        $data = DB::table('openprize') -> select('prize_number','open_number') -> where(function($query){
            $query -> where('open_number','!=',0);
        }) -> orderBy('id','desc') -> limit(50) -> get();

        return response() -> json($data);
    }

    public function getResByType($number,$type){
        //取个位数字
        $length = strlen($number);
        $number_ge = substr($number,$length-1,1);
        $number_shi = substr($number,$length-2,1);
        switch ($type){
            case 1:
                //大小场
                if($number_ge == $number_shi){
                    return '合';
                }
                if($number_ge <5){
                    return '小';
                }else{
                    return '大';
                }
                break;

            case 2:
                //数组场
                ;
        }
    }

    //返回userinfo
    public function getUserInfo($openid){

    }

    //充值
    public function recharge(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        $price = $request -> input('price');
        //看下多少钱可以买多少点
        $point = $price;

        DB::table('buylog') -> insert([
            'openid' => $openid,
            'price' => $price,
            'point' => $point,
            'created_at' => time()
        ]);

        //在user表中加入记录
        DB::table('user') -> where([
            'openid' => $openid
        ]) -> increment('point',$point);

        //返回最终点数
        $userinfo = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
        return response() -> json(['point'=>$userinfo -> point]);
    }


    //通过openprize id 获取开奖记录
    public function getOpenLog($open_id){
        if(Cache::get('open_number_'.$open_id)){
            $temp = Cache::get('open_number_'.$open_id);
            return $temp;
        }else{
            //查询
            $data = DB::table('openprize') -> where([
                'id' => $open_id
            ]) -> first();
            if($data && $data -> prize_number){

                //开奖结果 保存缓存
                Cache::forever('open_number_'.$open_id,[
                    'prize_number' => $data -> prize_number,
                    'open_number' => $data -> open_number,
                ]);

                $arr = [
                    'prize_number' => $data -> prize_number,
                    'open_number' => $data -> open_number,
                ];
                return $arr;
            }else{
                return 0;
            }
        }
    }


    public function getUserDetail(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        $data = DB::table('buylog') -> where([
            'openid' => $openid
        ]) -> orderBy('id','desc') -> get();
        return response() -> json($data);
    }


    //注册用户
    public function regUser(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = trim($request -> input('openid'));
        $nickname = trim($request -> input('nickname'));
        $yaoqingma = trim($request -> input('code'));
        //判断必填
        if(!$openid){
            return response() -> json([
                'status' => 'must_error'
            ]);
        }

        //查下openid不会重复把
        $is_openid = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
        if($is_openid){
            //如果存在返回用户id 余额
            return response() -> json([
                'uid' => $is_openid -> uid,
                'point' => $is_openid -> point,
                'code' => $is_openid -> code,
            ]);
        }


        if(!$yaoqingma){
            return response() -> json([
                'status' => 'code_error'
            ]);
        }
        //先查下是否有此邀请码
        $is_code = DB::table('user') -> where([
            'code' => $yaoqingma
        ]) -> first();
        if(!$is_code && $yaoqingma != '999999'){
            return response() -> json([
                'status' => 'code_error'
            ]);
        }


        $uid = substr(microtime(),0,6);
        //此人的邀请码
        $new_yaoqing = substr(md5(microtime(true)), 0, 6);
        $res = DB::table('user') -> insert([
            'openid' => $openid,
            'nickname' => $nickname,
            'code' => $new_yaoqing,
            'code_other' => $yaoqingma,
            'uid' => $uid,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        if($res){
            return response() -> json([
                'status' => 'success',
                'code' => $new_yaoqing,
                'uid' => $uid
            ]);
        }
    }


    public function payRequest_2(Request $request){
        header("Content-type:text/html;charset=utf-8");
        $config_biz = [
            'out_trade_no' => date('YmdHis') . mt_rand(1000,9999),
            'subject' => 'Alipay Test',
            'total_fee' => '0.01',
        ];


        $pay = new Pay($this->config);

        return $pay->driver('alipay')->gateway('app')->pay($config_biz);
    }

    public function notify(Request $request){
        $pay = new Pay($this->config);

        return $pay->driver('alipay')->gateway()->verify($request->all());
    }

    public function return_req(Request $request){
        $pay = new Pay($this->config);

        return $pay->driver('alipay')->gateway()->verify($request->all());
    }


    public function payRequest(Request $request){
        //include app_path().'/AopSdk/aop/AopClient.php';



        $c = new AopClient();
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = "2017110909830350";
        $c->rsaPrivateKey = 'MIICXQIBAAKBgQDhQ/VT3joAmUTtD0KpZl87M1YYa6oDIEBzPMScYuC958TkV7AZ7UbEzJNrlqQ4NbBmLPltrqsgceP5X0c7qyafoFby+PMKOP+6PRYNTqIrp3mbLCaLD6fF10XYrmJ6hhEndLQKz4JR9i6wkGUwvwJ8gSX52VDgYnimv9Cy71KoPQIDAQABAoGAV/SF9LI/aX5u2DTuLWCIbIAV7MEVB9Vu9M/UYM+Guv+k9Bd87hKkYDEUmpyeEEh+UNbcqUPbE3cEsZjPInAoSs3zOz62/L3XYzmA//EKBQtj1y5AJZHYqo9RL+M6FM5fJlq3xMkIuNfBokC+h2aw8dWPm9eShIBfXSVIi7HFna0CQQDxFZaP5ISFZ+cwWhWFXg88ryxDyfXn7oejma4HazcEl5QbC2mhGvlY1kF2llK92kXmw2v7frMo0kdmp6ERJZaDAkEA7zPSveQPOOtHlLXe72SQabORVOAAOzMZ7Sotv9jM3skSjmPgAp6r0Mrz/n+fJ8KdSDmpndmUQCTfIwAP+VCKPwJBANF7QrKRjB0nZZl8DUsvqem/BKV6rbP0bePYO4GyxcG1vDmrtwMIHzX0JjnW8NqK+UZE9GU5eI+199jZO3lcweUCQQCVjsFlGQKrg+/tewk4hJgGfs+PUb7TRNAhCQ4xtUviv7VqcefNu4eRtFN5/DF2mqfcULFMkI2wzVz2dUOHjmPhAkAfwwARrXfic2CzHULmMuIh3hoUuP14JlqU/VeCVU/AQrRob+l2eUa/I5RqYpMqFwnkNzCUhrU3T2l8pu7w054j';
        $c->format = "json";
        $c->charset= "utf-8";
        $c->signType= "RSA";
        $c->alipayrsaPublicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        $request = new AlipayTradeAppPayRequest();
        $request->setBizContent("{\"timeout_express\":\"30m\",\"product_code\":\"QUICK_MSECURITY_PAY\",\"total_amount\":\"0.01\",\"subject\":\"1\",\"body\":\"我是测试数据\",\"out_trade_no\":\"012114575097325\"}");
        echo $c->sdkExecute($request);

    }











}
