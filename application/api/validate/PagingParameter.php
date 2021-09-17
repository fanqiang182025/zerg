<?php

/** Created by wangshuai....  **/

namespace app\api\validate;

class PagingParameter extends BaseValidate
{

    protected $rule = [
        'page'=>'isPositiveInteger',
        'size'=>'isPositiveInteger'
    ];

    protected $message = [
        'page'=>'页数必修是正整数',
        'size'=>'size必须是正整数'
    ];

}
