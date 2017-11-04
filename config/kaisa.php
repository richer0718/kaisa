<?php
/**
 * Created by PhpStorm.
 * User: richer
 * Date: 2017/11/4
 * Time: 07:09
 */
return[
    //凯撒选项配置
    'options'=>[
        //大小场

        //大
        '1' => [
            //赔率
            'peilv' => 1.9,
            //包括的数字
            'number'=>[5,6,7,8,9]
        ],
        //小
        '2' => [
            'peilv' => 1.9,
            'number'=>[0,1,2,3,4]
        ],
        //合
        '3' => [
            'peilv' => 7,
            'number' => [],
        ],
        //数组场
        // 0 1 2
        '4' => [
            'peilv' => 2.5,
            'number' => [0,1,2]
        ],
        // 3,4,5
        '5' => [
            'peilv' => 2.5,
            'number' => [3,4,5]
        ],
        // 6,7,8
        '6' => [
            'peilv' => 2.5,
            'number' => [6,7,8]
        ],

        // 0,1
        '7' => [
            'peilv' => 3.5,
            'number' => [0,1]
        ],
        // 2,3
        '8' => [
            'peilv' => 3.5,
            'number' => [2,3]
        ],
        // 4,5
        '9' => [
            'peilv' => 3.5,
            'number' => [4,5]
        ],
        // 6,7
        '10' => [
            'peilv' => 3.5,
            'number' => [6,7]
        ],
        // 8,9
        '11' => [
            'peilv' => 3.5,
            'number' => [8,9]
        ],

        //精确场
        '12' => [
            'peilv' => 7,
            'number' => [1]
        ],
        '13' => [
            'peilv' => 7,
            'number' => [2]
        ],
        '14' => [
            'peilv' => 7,
            'number' => [3]
        ],
        '15' => [
            'peilv' => 7,
            'number' => [4]
        ],
        '16' => [
            'peilv' => 7,
            'number' => [5]
        ],
        '17' => [
            'peilv' => 7,
            'number' => [6]
        ],
        '18' => [
            'peilv' => 7,
            'number' => [7]
        ],
        '19' => [
            'peilv' => 7,
            'number' => [8]
        ],
        '20' => [
            'peilv' => 7,
            'number' => [9]
        ],
        '21' => [
            'peilv' => 7,
            'number' => [10]
        ],

    ],
    'price_list' => [
    ]
];