<?php

/** Created by wangshuai....  **/

namespace app\api\service;

class WxMessage{
	private $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=%s';
	private $touser = '';
	//private $color = 'black';

	public $tplID;
	public $page;
	public $data;

	public __construct{
		$accesstoken = new AccessToken();
		$token = $accesstoken->get();
		$this->sendUrl = sprintf($this->sendUrl,$token);
	}

	public function sendMessage($openid){
		$data = [
			'touser'=> $openid;
			'template_id'=>$this->tplID;
			'page'=>$this->page;
			'data'=>$this->data;
		];

		$result = curl_post($this->sendUrl,$data);
		$result = json_decode($result);

		if($result['errcode'] == 0) {
			return true;
		}else{
			throw new Exception("发送订阅消息失败，".$result['errmsg']);	
		}

	}
}