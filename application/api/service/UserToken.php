<?php

/** Created by wangshuai....  **/

namespace app\api\service;
use app\lib\exception\WechatException;
use think\Exception;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;

class UserToken extends Token
{

	protected $code;
   	protected $wxAppId;
   	protected $wxAppSecret;
   	protected $wxLoginUrl;

	function __construct($code){
  		$this->code = $code;
   		$this->wxAppId = config('wx.app_id');
   		$this->wxAppSecret = config('wx.app_secret');
   		$this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppId,$this->wxAppSecret,$code);
   	}

    //获取Token
   	public function get(){
   	 	$result = curl_get($this->wxLoginUrl);
   	 	$wxResult = json_decode($result,true);
   	 	if(empty($wxResult)){
   	 		throw new Exception("获取session_key及openID时异常，微信内部错误");	
   	 	}else{
   	 		$loginFail = array_key_exists('errcode', $wxResult);
   	 		if($loginFail){
   	 			$this->processLoginError($wxResult);
   	 		}else{
   	 			$token = $this->gratToken($wxResult);
                return $token;
   	 		}
   	 	}
   	}

    //抛出异常
   	private function processLoginError($wxResult){
   	 	throw new WechatException([
   	 		'msg'=>$wxResult['errmsg'],
   	 		'errorCode'=>$wxResult['errcode']
   	 	]);
   	}

    //创建用户
    private function newUser($openid){
        $user = UserModel::create([
            'openid'=>$openid
        ]);
        return $user->id;
    }

    //准备缓存数据
    private function prepareCachedValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::user;

        return $cachedValue;
    }

    //加入缓存
    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expireIN = config('seatting.token_expire_in');
        $result = Cache($key,$value,$expireIN);
        if(!$result) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
            
        }
        return $key;
    }

   	// 颁发令牌
   	private function gratToken($wxResult)
   	{
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenId($openid);
        if($user) {
            $id = $user->id;
        }else{
            $id = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$id);
        $token = $this->saveToCache($cachedValue);
   		return $token;
   	}
}
