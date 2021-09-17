<?php

/** Created by wangshuai....  **/

namespace app\api\service;

use app\api\model\ThirdApp;
use app\lib\exception\TokenException;

class AppToken extends Token{
    public function get($ac,$se){
        $app = ThirdApp::check($ac,$se);
        if(!$app) {
            throw new TokenException([
                'msg'=>'授权失败',
                'errorCode'=>'10004'
            ]);
        }else{
            $scope = $app->scope;
            $uid = $app->id;
            $value = [
                'scope'=>$scope,
                'uid'=>$uid
            ];

            $token = $this->saveToCache($value);
            return $token;
        }
    }

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
	
}
