<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', 'Admin\TestController@index');
Route::get('/getData', 'Admin\TestController@getData');

//Api
Route::group(['prefix' => 'api'], function () {
    //回调
    Route::any('/wxLogin/{code}','ApiController@checkLogin');

    //注册用户
    Route::any('/regUser', 'ApiController@regUser');
    //用户登录
    Route::any('/login', 'ApiController@login');
    //获取用户详细信息
    Route::any('/getUserDetail', 'ApiController@getUserDetail');
    //获取最新期数信息
    Route::any('/getNewPrize/{openid?}', 'ApiController@getNewPrize');
    //自动化生成下期期数
    Route::any('/makeNextPrize', 'ApiController@makeNextPrize');
    //下注
    Route::any('/buyNumber', 'ApiController@buyNumber');
    //充值
    Route::any('/aliPay', 'ApiController@aliPay');
    //阿里回调
    Route::any('/alipay', 'ApiController@notify');
    //计算
    Route::any('/jisuan/{number}', 'ApiController@jisuan');
    //开奖
    Route::any('/openPrize/{number}', 'ApiController@openPrize');
    //开奖历史记录
    Route::any('/getHistoryData', 'ApiController@getHistoryData');
    //下注历史记录
    Route::any('/buyLog', 'ApiController@getHistoryData');
    //充值(暂时)
    Route::any('/recharge', 'ApiController@recharge');
    //充值记录
    Route::any('/rechargeLog', 'ApiController@rechargeLog');
    //佣金记录
    Route::any('/yongjinLog', 'ApiController@yongjinLog');
    //阿里支付请求
    //Route::any('/notify','ApiController@notify');

    Route::any('/return_req','ApiController@return_req');
    //结账
    Route::any('/countOrder','ApiController@countOrder');
    //兑换
    Route::any('/duihuanLog','ApiController@duihuanLog');
    //下注记录
    Route::any('/xiazhuLog','ApiController@xiazhuLog');
    //转账
    Route::any('/givePoint','ApiController@givePoint');

    Route::any('/getCode','ApiController@getCode');
    Route::any('/getUserList','ApiController@getUserList');



    Route::any('/clearCache', 'ApiController@clearCache');


});

//后台

Route::get('/admin/login', 'Admin\IndexController@login');
Route::any('/admin/loginRes', 'Admin\IndexController@loginRes');
Route::any('/admin/loginout', 'Admin\IndexController@loginout');

Route::group(['as' => 'index','middleware' => ['checkadminlogin']], function () {
    Route::get('/admin/index', 'Admin\IndexController@index');
});

Route::group(['as' => 'user','middleware' => ['checkadminlogin']], function () {
    Route::any('/admin/user', 'Admin\UserController@index');
    Route::any('/admin/duihuan', 'Admin\UserController@duihuan');
    Route::any('/admin/userlog/{id}', 'Admin\UserController@userlog');
});
Route::group(['as' => 'prize','middleware' => ['checkadminlogin']], function () {
    Route::any('/admin/prize', 'Admin\PrizeController@index');
});