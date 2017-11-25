<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/api/buyNumber',
        '/api/getNewPrize',
        '/api/getHistoryData',
        '/api/getUserDetail',
        '/api/recharge',
        '/api/regUser',
        '/api/notify',
        '/api/return_req',
        '/api/makeNextPrize',
        '/api/rechargeLog',
        '/api/yongjinLog',
        '/api/duihuanLog',
        '/api/xiazhuLog',
        '/api/givePoint',
        '/api/getCode',
        '/api/getUserList',
        '/api/payRequest',
        '/api/aliPay',
        '/api/alipay',
        '/api/jisuan',
        '/api/sendMessage',
        '/api/mkQrcode',
        '/messageBack',

    ];
}
