<?php

/** Created by wangshuai....  **/

namespace app\api\validate;

use think\Validate;
use think\Request;
use app\lib\exception\ParameterException;
use think\Exception;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取http的所有参数
        //对参数进行校验
        $request = Request::instance();
        $params = $request->param();
        
        $result = $this->batch()->check($params);
        if(!$result){
            $e = new ParameterException([
                'msg'=>$this->error
            ]);
            throw $e;  
        }else{
            return true;
        }

    }

    protected function isPositiveInteger($value,$rule='',$data='',$field=''){
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        }else{
            return false;
        }

    }

    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        if(empty($value)){
            return false;
        }else{
            return true;
        }

    }

    protected function isMobile($value,$rule='',$data='',$field=''){
        $rule = "/^1((34[0-8]\d{7})|((3[0-3|5-9])|(4[5-7|9])|(5[0-3|5-9])|(66)|(7[2-3|5-8])|(8[0-9])|(9[1|8|9]))\d{8})$/";
        //$rule = "^1(34578)[0-9]\d{8}$^";
        $result =preg_match($rule,$value);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function getDataByRule($array)
    {
        if (array_key_exists('uid',$array) || array_key_exists('user_id',$array)) {
            throw new Exception("参数中含有非法参数名uid或者user_id");  
        }else{
            $newArray = [];
            foreach($this->rule as $key => $value) {
                $newArray[$key] = $array[$key];
            }
            return $newArray;
        }
    }
   
}
