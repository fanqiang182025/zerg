<?php

/** Created by wangshuai....  **/

namespace app\api\validate;


class IdMustBePositiveInt extends BaseValidate
{

    protected $rule = [
        'id'=>'require|isPositiveInteger',
    ];

    protected $message = [
        'id'=>'id必须是正整数'
    ];

}
