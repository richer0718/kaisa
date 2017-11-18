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
        '/api/alipay',
        '/api/alipay_notify',
        '/api/return_req',
        '/api/makeNextPrize',
        '/api/rechargeLog',
        '/api/yongjinLog',
        '/api/duihuanLog',
        '/api/xiazhuLog',

    ];
}
