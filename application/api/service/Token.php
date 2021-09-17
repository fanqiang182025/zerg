<?php

/** Created by wangshuai....  **/

namespace app\api\service;
use think\Request;
use think\Cache;
use app\lib\exception\TokenException;
use app\lib\exception\ForbiddenException;
use app\lib\enum\ScopeEnum;

class Token
{
	public static function generateToken(){
		//32个字符组成一组随机字符串 getRandChars公共函数
		$randChars = getRandChars(32);
		//请求开始时的时间戳-微秒级别的精准度
		$timesTamp = $_SERVER['REQUEST_TIME_FLOAT'];
		//salt 盐
		$salt = config('secure.token_salt');
		//用三组字符串md5加密
		return md5($randChars.$timesTamp.$salt);
	}

	//获取Token键值缓存中的值
	public static function getCurrentTokenVar($key){
		$request = Request::instance();
		$token = $request->header('token');
		$vars = Cache::get($token);
		if(!$vars){
			throw new TokenException();
		}else{
			if(!is_array($vars)){
				$vars = json_decode($vars,true);
			}
			if(!array_key_exists($key,$vars)) {
				throw new Exception("尝试获取的token变量不存在");	
			}else{
				return $vars[$key];
			}
		}
		
	}

	//获取Token中的uid
	public static function getCurrentUid(){
		$uid = self::getCurrentTokenVar('uid');
		return $uid;
	}

	//获取Token中的scope
	public static function getCurrentScope(){
		$scope = self::getCurrentTokenVar('scope');
		return $scope;
	}

	//用户和CMS管理员都可以访问的接口权限
	public static function needPrimaryScope(){
		$scope = self::getCurrentScope(); 
        if($scope){
            if($scope<ScopeEnum::user) {
                throw new ForbiddenException();   
            }else{
                return true;
            }
        } else {
            throw new TokenException();   
        }
	}

	//只有用户才能访问的接口权限
	public static function needExclusiveScope(){
		$scope = self::getCurrentScope(); 
        if($scope){
            if($scope == ScopeEnum::user) {
            	return true; 
            }else{
                throw new ForbiddenException();  
            }
        } else {
            throw new TokenException();   
        }
	}

	//是否是有效的操作
	public static function isValidOperate($checkUid){
		if(!$checkUid) {
			throw new Exception("检测uid时必须传入被检测的uid");
		}

		$currentUid = self::getCurrentUid();
		if($currentUid == $checkUid) {
			return true;
		}

		return false;		
	}

	public static function verifyToken($token){
		$vars = Cache::get($token);
		if ($vars) {
			return true;
		}

		return false;
	}
}