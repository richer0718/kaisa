<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{


//MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDhQ/VT3joAmUTtD0KpZl87M1YYa6oDIEBzPMScYuC958TkV7AZ7UbEzJNrlqQ4NbBmLPltrqsgceP5X0c7qyafoFby+PMKOP+6PRYNTqIrp3mbLCaLD6fF10XYrmJ6hhEndLQKz4JR9i6wkGUwvwJ8gSX52VDgYnimv9Cy71KoPQIDAQAB
    public function clearCache(){
        Cache::flush();
        echo 'success';
    }



    public function checkLogin($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=wx16137799d7567e8f&secret=0870cf68faef93d81e505bd0380a8da0&js_code='.$code.'&grant_type=authorization_code';
        $result = file_get_contents($url);
        return response() -> json($result);
    }

    //获取最新开奖
    //客户端请求最新开奖信息
    public function getNewPrize(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
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
            $number4 = intval($end_number_info['id'])  - 3;

            $return_arr = [];
            //返回最新一期的期数
            $return_arr['first_number'] = $first_data;
            //最新一期期数的id
            $return_arr['first_number_id'] = $end_number_info['id'];
            //第一期已经开奖的时间
            //已经开奖的时间
            $return_arr['open_time'] = time() - $create_time;
            //获取第二期第三期第四期数据
            $data2 = $this -> getOpenLog($number2);
            $data3 = $this -> getOpenLog($number3);
            $data4 = $this -> getOpenLog($number4);



            $return_arr['history'][] = $data2;
            $return_arr['history'][] = $data3;
            $return_arr['history'][] = $data4;

            //返回这个人的余额
            if($openid){
                $return_arr['userinfo'] = DB::table('user') -> where([
                        'openid' => $openid
                ]) -> first();

                if(isset($data2['open_id'])){
                    $return_arr['xiazhu'] = DB::table('touzhu') -> where([
                        //number
                        'number' => $data2['open_id']
                    ]) -> get();
                }

            }

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
        /*
        $is_pay = DB::table('touzhu') -> where([
            'number' => $open_number_id,
            'openid' => $openid
        ]) -> get();
        if($is_pay){
            //如果投过，返回他的余额

        }
        */



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
        Cache::put('date',$date,1440);


        
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

        //结账  算下这期赚了多少。

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

         //查下点数够不够
        $userinfo = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
         if(!$userinfo){
             return response() -> json(['status'=>'error']);
         }
         if($userinfo -> point < $point){
             return response() -> json(['status'=>'notenough']);
         }
        //存入投注表
        DB::table('touzhu') -> insert([
            'openid' => $openid,
            'buy_option' => $option,
            'number' => $number,
            'point' => $point,
            'created_at' => time()
        ]);

         //扣除
        DB::table('user') -> where([
            'openid' => $openid
        ]) -> update([
            'point' => $userinfo -> point - $point
        ]);
        //var_dump($userinfo);exit;
        //他扣除 给他上级加钱 此人信息$userinfo
        if($userinfo){
            $this -> addPoint($openid,$userinfo -> openid_other,$point);
        }




            return response() -> json(['status'=>'success','point'=>$userinfo -> point - $point]);


        }else{
            return response() -> json(['status'=>'error']);
        }


    }


    public function notify(Request $request){
        header('Access-Control-Allow-Origin:*');
        //$temp = json_decode('{"discount":"0.00","payment_type":"1","trade_no":"2017112021001104040253319949","subject":"\u51ef\u6492\u7535\u5b50","buyer_email":"187****4202","gmt_create":"2017-11-20 22:36:21","notify_type":"trade_status_sync","quantity":"1","out_trade_no":"2017112046364","seller_id":"2088621908302474","notify_time":"2017-11-20 22:36:23","body":"\u51ef\u6492\u7535\u5b50","trade_status":"TRADE_SUCCESS","is_total_fee_adjust":"N","total_fee":"0.01","gmt_payment":"2017-11-20 22:36:22","seller_email":"jinhaoqck@163.com","price":"0.01","buyer_id":"2088802485328042","notify_id":"68ce8a0e90643990704d502c2378e57gb6","use_coupon":"N","sign_type":"RSA","sign":"dg306Nn1\/XKiQ\/OkcS9sBCsN++f0dI8adyLDh17dVp6EKC+h4ut4BdnDhO9CJC0ooxsYqBmskwWPMx+0iflS6cXHEfsTtUhITn5bVl5FpmvLZJoFS5xhBHxz9tcfA3FF\/TsUkgvZbm\/yWBqjTVrDwD5QawSk\/XAfBnb1URfsdff="}',true);

        //var_dump(json_encode($temp));exit;
        //header('Access-Control-Allow-Origin:*');
        //var_dump(app_path().'/AliPay/alipay.config.php');exit;
        //include app_path().'/AliPay/notify_url.php';
        //dd($alipay_config);

        $url_verify = 'https://mapi.alipay.com/gateway.do?service=notify_verify&partner=2088621908302474&notify_id='.$_POST['notify_id'];
        $verify_res = file_get_contents($url_verify);
        //var_dump($verify_res);exit;


        if($verify_res == 'true'){
            //验签完毕
            $order_id = $_POST['out_trade_no'];
            $price = $_POST['price'];
            //查找buy_log
            $log = DB::table('buylog') -> where([
                'order_id' => $order_id
            ]) -> first();
            if($log){
                //更改is_pay
                DB::table('buylog') -> where([
                    'order_id' => $order_id
                ]) -> update([
                    'is_pay' => 1,
                    'updated_at' => time()
                ]);
                //user 加余额
                $user_info = DB::table('user') -> where([
                    'openid' => $log -> openid
                ]) -> first();
                DB::table('user') -> where([
                    'openid' => $log -> openid
                ]) -> update([
                    'point' => $user_info -> point + $price
                ]);
                echo 'success';

            }
        }





    }

    //第五级的openid

    /**
     * @param $openid_buy 买的那个openid
     * @param $openid 需要加点数的openid
     * @param $point
     */
    public function addPoint($openid_buy,$openid,$point){
        $user5 = $this -> addPoints($openid_buy,$openid,$point,0.05);
        if($user5){
            $user4 = $this -> addPoints($openid_buy,$user5 -> openid_other,$point,0.03);
            if($user4){
                $user3 = $this -> addPoints($openid_buy,$user4 -> openid_other,$point,0.02);
                if($user3){
                    $user2 = $this -> addPoints($openid_buy,$user3 -> openid_other,$point,0.01);
                    if($user2){
                        //$user1 = $this -> addPoints($openid_buy,$user2 -> openid_other,$point,0.01);
                    }
                }
            }
        }
    }

    //下注 上级加点数

    /**
     * @param $openid
     * @param $point 点数
     * @param $per 应该扣的百分比
     * @return bool
     */
    public function addPoints($openid_buy,$openid,$point,$per){
        //查这个openid 有没有
        $user = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
        if($user){
            //如果有，就给他加钱
            DB::table('user') -> where([
                'openid' => $openid
            ]) -> update([
                'point' => $user -> point + $point * $per
            ]);

            //记录代理入款记录
            DB::table('daili_log') -> insert([
                'openid' => $openid,
                'point' => $point * $per,
                'openid_buy' => $openid_buy,
                'created_at' => time()
            ]);


            return $user;

        }else{
            return false;
        }
    }


    //计算投注结果
    //返回三位数字
    public function jisuan($open_number_id = 16007){
        //echo 11;exit;
        //去touzhu表中查，这期的投注
        $result = DB::table('touzhu') -> where(function($query) use($open_number_id){
            $query -> where('number','=',$open_number_id);
            $query -> where('buy_option','!=',1);
            $query -> where('buy_option','!=',2);
            $query -> where('buy_option','!=',3);
        }) -> get();

        $options = config('kaisa.options');
        //dump($options);
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
            //dump($result);
            foreach($result as $vo){
                //dump($vo);
                //查下此option所包含的number
                $temp = $vo->buy_option;
                $option = $options[$temp];
                //dd($option['number']);
                //没有考虑 合 的情况
                //dump($option['number']);
                if($option['number']){
                    //每个number + 投的点数
                    //此数组里边的值，每个都加point
                    foreach($option['number'] as $vol){
                        $numbers[$vol] += intval($vo -> point) * $option['peilv'];
                    }
                }

            }
        }

        //echo 'end';exit;


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
            $res2 = intval($result2) * $options[2]['peilv'];
            $res3 = intval($result3) * $options[3]['peilv'];

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
        $number_pre = rand(1256600,1999999);
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
            'open_id' => $data -> id
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

        //开完奖，结账
        $this -> countOrder($number_res,$open_number_id);




        Cache::forever('is_stop',0);
        //var_dump($number_res);exit;
        return $number_res;
    }


    //开奖后 结账
    public function  countOrder($number = 23432554,$id = 495){
        //算下这个数字，哪个选项中了。
        $options = config('kaisa.options');
        //大小合
        //看下十位个位是否相同
        $number_ge = substr($number,strlen($number)-1,1);
        $number_shi = substr($number,strlen($number) -2,1);
        $option_open_numbers = [];
        $option_open_peilv = [];

        if($number_ge == $number_shi){
            //开合
            $option_open_peilv[] = $options['3']['peilv'];
            $option_open_numbers[] = 3;
            unset($options['1']);
            unset($options['2']);
            unset($options['3']);
        }
        //看下其他
        foreach($options as $k => $vo){
            $temp_number = $vo['number'];
            if(in_array($number_ge,$temp_number)){
                $option_open_peilv[] = $options[$k]['peilv'];
                $option_open_numbers[] = $k;
            }
        }

        //得到开奖的option
        $touzhus = DB::table('touzhu') -> where([
            'number' => $id
        ]) -> get();
        //给每个人开奖
        if($touzhus){
            //所有的投注点数
            $point_all = 0;
            $buy_plus = [];
            $buy_ext = [];
            foreach($touzhus as $key => $vo){
                //投的选项
                $temp_option = $vo -> buy_option;
                //投的点数
                $temp_point = $vo -> point;
                //投的openid
                $temp_openid = $vo -> openid;
                if($temp_option && $temp_point && $temp_openid){
                    //var_dump($option_open_numbers);exit;
                    //看下他挣了赔了
                    if(in_array($temp_option,$option_open_numbers)){
                        //得到索引
                        $index = array_keys($option_open_numbers,$temp_option);
                        //dd($option_open_peilv[$index[0]]);
                        //var_dump($option_open_peilv);exit;
                        //挣了,把ta的钱给加上
                        $buy_plus[$key]['openid'] = $temp_openid;
                        $buy_plus[$key]['point'] = $temp_point * $option_open_peilv[$index[0]];
                    }else{

                        //赔了 就全部减去
                        $buy_ext[$key]['openid'] = $temp_openid;
                        $buy_ext[$key]['point'] = $temp_point;
                    }

                    //加一下 所有的投注点数
                    $point_all += $temp_point;



                }else{
                    continue;
                }
            }

            $point_cut =0;
            //该加的加上
            foreach($buy_plus as $vo){
                //开始处理
                DB::table('user') -> where([
                    'openid' => $vo['openid']
                ]) -> increment('point',$vo['point']);
                //加了的 就是赔的
                $point_cut += $vo['point'];
            }

            //更新到交易表中
            DB::table('tradelog') -> insert([
                'qishu_id' => $id,
                'point_buy' => $point_all,
                'point_cut' => $point_cut,
                'created_at' => time()
            ]);


            //该扣的扣掉
            //扣的 就是挣的
            /*
            foreach($buy_ext as $vo){
                DB::table('user') -> where([
                    'openid' => $vo['openid']
                ]) -> decrement('point',$vo['point']);
                //输赢记录
            }
            */





        }
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

    //阿里支付
    public function aliPay(Request $request){
        header('Access-Control-Allow-Origin:*');
        //生成订单

        $openid = $request -> input('openid');
        $price = $request -> input('price');
        if(!$openid || !$price){
            return response() -> json(['openid_price'=>'error']);
        }

        if($openid){
            $userinfo = DB::table('user') -> where([
                'openid' => $openid
            ]) -> first();
            if(!$userinfo){
                return response() -> json(['status'=>'error']);
            }
        }else{
            return response() -> json(['status'=>'error']);
        }


        //生成订单编号
        $out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        //var_dump('http://m.jhqck.com/al/order/sign?total_fee='.$price.'&out_trade_no='.$out_trade_no);exit;
        $sign = file_get_contents('http://m.jhqck.com/al/order/sign?total_fee='.$price.'&out_trade_no='.$out_trade_no);


        if(!$sign){
            return response() -> json(['sign'=>'error']);
        }




        //看下多少钱可以买多少点
        $point = $price;


        //记录订单号

        DB::table('buylog') -> insert([
            'openid' => $openid,
            'order_id' => $out_trade_no,
            'point' => $point,
            'created_at' => time()
        ]);
        echo $sign;
        //return response() -> json($sign);
        //在user表中加入记录
        /*
        DB::table('user') -> where([
            'openid' => $openid
        ]) -> update([
            'point' => $userinfo -> point + $point
        ]);


        //返回最终点数
        $userinfo = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
        */

        //return response() -> json(['point'=>$userinfo -> point]);
    }

    //充值
    public function recharge(Request $request){
        header('Access-Control-Allow-Origin:*');
        //生成订单
        $openid = $request -> input('openid');
        $price = $request -> input('price');
        //看下多少钱可以买多少点
        $point = $price;
        //在user表中加入记录
        $userinfo = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();
        DB::table('user') -> where([
            'openid' => $openid
        ]) -> update([
            'point' => $userinfo -> point + $point
        ]);

        DB::table('buylog') -> insert([
            'openid' => $openid,
            'is_pay' => 1,
            'point' => $point,
            'created_at' => time()
        ]);

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
                    'open_id' => $data -> id
                ]);

                $arr = [
                    'prize_number' => $data -> prize_number,
                    'open_number' => $data -> open_number,
                    'open_id' => $data -> id
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
            'openid' => $openid,
            'is_pay' => 1
        ]) -> orderBy('id','desc') -> get();
        return response() -> json($data);
    }


    //注册用户
    public function regUser(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = trim($request -> input('openid'));
        $nickname = trim($request -> input('nickname'));
        $yaoqingma = trim($request -> input('code'));
        $yanzhengma = trim($request -> input('yanzhengma'));

        //判断必填
        if(!$openid){
            return response() -> json([
                'status' => 'must_error'
            ]);
        }

        //查下验证码
        $isset = is_null(Cache::get($openid));
        if($isset){
            return response() -> json([
                'status' => 'timeout'
            ]);
        }


        $cache = Cache::get($openid);
        //var_dump($cache);exit;
        if($cache != $yanzhengma){
            return response() -> json([
                'status' => 'yanzhengma_error'
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

        if($is_code){
            $other_openid = $is_code -> openid;
        }else{
            $other_openid = '';
        }

        //查处现在有多少用户
        $user_count  = DB::table('user') -> count();
        $num = intval($user_count) + 129876;
        $uid = str_pad($num,6,"0",STR_PAD_LEFT);

        //此人的邀请码
        $new_yaoqing = substr(md5(microtime(true)), 0, 6);
        $res = DB::table('user') -> insert([
            'openid' => $openid,
            'nickname' => $nickname,
            'code' => $new_yaoqing,
            'code_other' => $yaoqingma,
            'uid' => $uid,
            'openid_other' => $other_openid,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        if($res){
            return response() -> json([
                'status' => 'success',
                'code' => $new_yaoqing,
                'uid' => $uid,
                'point' => 0,
            ]);
        }
    }

    //下订单
    public function makeOrder(){
        
    }

    //返回各级人数
    public function getUserList(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');

        if(!$openid){
            return response() -> json([
                'status' => 'error'
            ]);
        }
        $userinfo = DB::table('user') -> where([
            'openid' => $openid
        ]) -> first();

        if(!$userinfo){
            return response() -> json([
                'status' => 'error'
            ]);
        }

        $return['userinfo'] = $userinfo;
        $return['daili1'] = 0;
        $return['daili2'] = 0;
        $return['daili3'] = 0;
        $return['daili4'] = 0;
        $return['daili5'] = 0;
        //各级代理人数
        $tmp_1 = DB::table('user') -> where([
            'code_other' => $userinfo -> code
        ]) -> get();
        $return['daili1'] = count($tmp_1);

        if(count($tmp_1)){
            $tmp_1_codes = [];
            //拿到一级代理的所有openid
            foreach($tmp_1 as $vo){
                $tmp_1_codes[] = $vo -> code;
            }
            $tmp_2 = DB::table('user') -> whereIn('code_other',$tmp_1_codes) -> get();
            $return['daili2'] = count($tmp_2);
            if(count($tmp_2)){
                $tmp_2_codes = [];
                //拿到一级代理的所有openid
                foreach($tmp_2 as $vo){
                    $tmp_2_codes[] = $vo -> code;
                }
                $tmp_3 = DB::table('user') -> whereIn('code_other',$tmp_2_codes) -> get();
                $return['daili3'] = count($tmp_3);
                if(count($tmp_3)){
                    $tmp_3_codes = [];
                    //拿到一级代理的所有openid
                    foreach($tmp_3 as $vo){
                        $tmp_3_codes[] = $vo -> code;
                    }
                    $tmp_4 = DB::table('user') -> whereIn('code_other',$tmp_3_codes) -> get();
                    $return['daili4'] = count($tmp_4);
                    if(count($tmp_4)){
                        $tmp_4_codes = [];
                        //拿到一级代理的所有openid
                        foreach($tmp_4 as $vo){
                            $tmp_4_codes[] = $vo -> code;
                        }
                        $tmp_5 = DB::table('user') -> whereIn('code_other',$tmp_4_codes) -> get();
                        $return['daili5'] = count($tmp_5);



                    }


                }


            }

        }



        return response() -> json([
            'status' => 'success',
            'data' => $return
        ]);


    }


    //返回充值记录
    public function rechargeLog(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        if($openid){
            $logs = DB::table('buylog') -> where([
                'openid' => $openid,
                'is_pay' => 1
            ]) -> get();
            if($logs){
                return response() -> json($logs);
            }else{
                return response() -> json(['openid'=>'error']);
            }
        }else{
            return response() -> json(['status'=>'error']);
        }
    }

    //返回佣金记录
    public function yongjinLog(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        if($openid){
            $logs = DB::table('daili_log') -> where([
                'openid' => $openid
            ]) -> get();
            if($logs){
                return response() -> json($logs);
            }else{
                return response() -> json(['openid'=>'error']);
            }
        }else{
            return response() -> json(['status'=>'error']);
        }
    }

    //兑换记录
    public function duihuanLog(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        if($openid){
            $logs = DB::table('duihuan_log') -> where([
                'openid' => $openid
            ]) -> get();
            if($logs){
                return response() -> json($logs);
            }else{
                return response() -> json(['openid'=>'error']);
            }
        }else{
            return response() -> json(['status'=>'error']);
        }
    }


    //下注记录
    public function xiazhuLog(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        if($openid){
            $logs = DB::table('touzhu') -> where([
                'openid' => $openid
            ]) -> get();
            if($logs){
                //查找每期的期数
                foreach($logs as $k => $vo){
                    $temp = Cache::get('open_number_'.$vo -> number);
                    if($temp){
                        $logs[$k] -> qishu = $temp['prize_number'];
                    }else{
                        $temp = DB::table('openprize') -> where([
                            'id' => $vo -> number
                        ]) -> first();
                        if($temp){
                            $logs[$k] -> qishu = $temp -> prize_number;
                        }else{
                            $logs[$k] -> qishu = 'error';
                        }
                    }
                    $temp = '';

                }
                return response() -> json($logs);
            }else{
                return response() -> json(['openid'=>'error']);
            }
        }else{
            return response() -> json(['status'=>'error']);
        }
    }

    //转账
    public function givePoint(Request $request ){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        $uid = $request -> input('uid');
        $point = intval($request -> input('point'));

        if($openid && $uid && $point){
            //查下这人 有没有这么多
            $userinfo = DB::table('user') -> where([
                'openid' => $openid
            ]) -> first();
            if($userinfo){
                if($userinfo -> point < $point){
                    return response() -> json(['point'=>'error']);
                }
                //判断uid 是否存在
                $isset = DB::table('user') -> where([
                    'uid' => $uid
                ]) -> first();
                if($isset){
                    // 开始转
                    //先扣
                    DB::table('user') -> where([
                        'openid' => $openid
                    ]) -> update([
                        'point' => $userinfo -> point - $point
                    ]);

                    //加
                    DB::table('user') -> where([
                        'uid' => $uid
                    ]) -> update([
                        'point' => $isset -> point + $point
                    ]);
                    return response() -> json(['status'=>'success','point'=>$userinfo -> point - $point]);



                }else{
                    return response() -> json(['uid'=>'error']);
                }


            }else{
                return response() -> json(['openid'=>'error']);
            }
        }else{
            return response() -> json(['status'=>'error']);
        }
    }

    //获取验证码
    public function getCode(Request $request){
        header('Access-Control-Allow-Origin:*');
        $openid = $request -> input('openid');
        if(!$openid){
            return response() -> json(['status'=>'error']);
        }
        //生成验证码
        $code = rand(123600,999999);
        DB::table('user') -> where([
            'openid' => $openid
        ]) -> update([
            'yanzhengma' => $code
        ]);

        return response() -> json(['status'=>'success','code'=>$code]);

    }


    public function payRequest(Request $request){

    }

    public function sendMessage(Request $request){
        require (app_path() . '/Tools/ChuanglanSmsApi.php');
        //判断是否存在
        if(!$request -> input('mobile')){
            return response() -> json(['status'=>'error']);
        }

        $is_set = !is_null(Cache::get($request -> input('mobile')));
        if($is_set){
            //代表重复获取
            return response() -> json(['status'=>'waiting']);
        }

        $api = new \ChuanglanSmsApi();
        $mobile = $request -> input('mobile');
        $code = mt_rand(100000,999999);

        $msg = '【青青客】您的验证码是'.$code;
        $res = $api -> sendSMS( $mobile, $msg);
        if($res){
            //存入缓存
            Cache::put($request -> input('mobile'),$code,2);
            return response() -> json(['status'=>'success','code'=>$code]);
        }

    }















}
