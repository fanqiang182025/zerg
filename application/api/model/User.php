<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class User extends BaseModel
{
    public function address(){
        return $this->hasOne('UserAddress','user_id','id');
    }

    public static function getByOpenId($openid)
    {
    	$user = self::where('openid','=',$openid)->find();
    	//$user = self::find($openid);
    	if($user){
    		return $user;
    	}else{
    		return false;
    	}
    }
}
