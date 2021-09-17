<?php

/** Created by wangshuai....  **/

namespace app\api\validate;


class IdCollection extends BaseValidate
{

    protected $rule = [
        'ids' => 'require|checkIds',
    ];

    protected $message = [
        'ids' => 'ids必须是以逗号隔开的正整数',
    ];


    protected function checkIds($value,$rule='',$data='',$field=''){
        $values = explode(',',$value);
        if(empty($values)){
            return false;
        }else{
            foreach ($values as $id) {
                if(!$this->isPositiveInteger($id)) {
                    return false;
                }  
            }
        }
        return true;
    }
}
