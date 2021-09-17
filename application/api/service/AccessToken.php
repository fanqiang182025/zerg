<?php

/** Created by wangshuai **/

namespace app\api\service;

class AccessToken{

	private $tokenUrl;
	const TOKEN_CACHED_KEY = 'access';
	const TOKEN_EXPIRE_IN = 70000;

	function __construct(){
		$url = config(wx.access_token_url);
		$url = sprintf($url,config(wx.app_id),config(wx.app_secret));
		$this->tokenUrl = $url;
	}

	//建议用户规模很小的时候-----直接请求微信服务器
	//明天请求的次数最多只有2000
	public function get($url){
		$token = getFromCache();
		if(!$token) {
			$token = $this->getFromWxServer();
		}
		return $token;
	}

	private function getFromCache(){
		$token = cache(self::TOKEN_CACHED_KEY);
		if (!token) {
			return false;
		}
		return $token;
	}

	private function getFromWxServer(){
		$token = curl_get($this->tokenUrl);
		$token = json_decode($token,true);

		if(!$token) {
			throw new Exception("获取微信access_token异常");
		}

		if(!array_key_exists('errcode',$token)) {
			throw new Exception($token['errmsg']);
		}

		$this->saveToCache($token);

		return $token['access_token'];
	}

	private function saveToCache($token){
		cache(self::TOKEN_CACHED_KEY,$token,self::TOKEN_EXPIRE_IN);
	}

}
