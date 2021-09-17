<?php

/** Created by wangshuai....  **/

namespace app\api\validate;

class Address extends BaseValidate
{

    protected $rule = [
        'name'=>'require|isNotEmpty',
        'mobile'=>'require|isMobile',
        'province'=>'require|isNotEmpty',
        'city'=>'require|isNotEmpty',
        'county'=>'require|isNotEmpty',
        'detail'=>'require|isNotEmpty',
    ];

    //protected $message = [
        //'name.isNotEmpty'=>'name是傻逼吗',
        // 'mobile'=>'mobile是傻逼啊',
        // 'province'=>'滚吧你',
    //];
}
