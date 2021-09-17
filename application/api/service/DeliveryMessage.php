<?php

/** Created by wangshuai **/

namespace app\api\service;

use app\lib\exception\OrderException;
use app\api\model\User as UserModel;
use app\lib\exception\UserException;

class DeliveryMessage extends WxMessage{

	const DELIVERY_MSG_ID = 'NLO61E-rQAqTs19HG5jrazvEuhlL6CDgzvYVss3Zdug';

	public function sendDeliveryMessage($order,$tplJumpPage){
		if(!$order) {
			throw new OrderException();
		}
		$this->tplID = self::DELIVERY_MSG_ID;
		$this->page = $tplJumpPage;
		$this->prepareMessageData();

		return parent::sendMessage($this->getUserOpenid($order->user_id));
	}

	private function prepareMessageData($order){
		$dt = new \DateTime();
		$data = "data": {
  			"number01": {
      			"value": "339208499"
  			},
  			"date01": {
      			"value": "2015年01月05日"
  			},
  			"site01": {
      			"value": "TIT创意园"
  			} ,
  			"site02": {
      			"value": "广州市新港中路397号"
  			}
 		};


		$this->data = $data;
	}

	private function getUserOpenid($uid){
		$user = UserModel::get($uid);
		if($user) {
			throw new UserException();
		}

		return $user->openid;
	}
}