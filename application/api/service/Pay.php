<?php

/** Created by wangshuai....  **/

namespace app\api\service;
use think\Exception;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use app\lib\enum\OrderStatusEnum;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class Pay
{
	protected $OrderId;
	protected $orderNo;

	function __construct($OrderId='')
	{
		if(!$OrderId) {
			throw new Exception("订单号不允许为null");
		}
		$this->OrderId = $OrderId;
	}

	public function pay(){
		//检测订单的有效性
		$this->checkOrderValid();
		//检测库存
		$orderService = new OrderService();
		$status = $orderService->chekOrderStock($this->OrderId);
		if(!$status['pass']) {
			$status['order_id'] = -1;
			return $status;
		}
		//调用微信的预订单接口
		return $this->makeWxPreOrder($status['orderPrice']);
	}

	/**
	 * 检测订单的有效性
	 * 1.订单是否真实存在
	 * 2.订单和用户是否匹配
	 * 3. 订单是否已支付
	 * */
	private function checkOrderValid(){
		$orderModel = OrderModel::find($this->OrderId);
		if(!$orderModel) {
			throw new OrderException();
		}
		if(!token::isValidOperate($orderModel->user_id)){
			throw new TokenException([
				'msg'=>'订单与用户不匹配',
				'errorCode'=>10003
			]);
		}
		if($orderModel->status != OrderStatusEnum::UNPAID) {
			throw new OrderException([
				'msg'=>'订单已支付',
				'errorCode'=>80003,
				'code'=>400
			]);
		}

		$this->orderNo = $orderModel->order_no;
		return true;
		
	}

	//调用微信的预订单接口
	private function makeWxPreOrder($totalPrice){
		$openid = Token::getCurrentTokenVar('openid');
		if(!$openid) {
			throw new TokenException();
		}
		$wxOrderData = new \WxPayUnifiedOrder();
		$wxOrderData->SetOut_trade_no($this->orderNo);
		$wxOrderData->SetTrade_type('JSAPI');
		$wxOrderData->SetTotal_fee($totalPrice*100);
		$wxOrderData->SetBody('零食商贩');
		$wxOrderData->SetOpenid($openid);
		$wxOrderData->SetNotify_url(config('secure.pay_back_url'));
		$wxOrderData->SetSignType('MD5'); 
		return $this->getPaySignature($wxOrderData);
	}

	private function getPaySignature($wxOrderData){
		//请求预订单接口
		$wxConfig = new \WxPayConfig();
		$wxOrder = \WxPayapi::unifiedOrder($wxConfig,$wxOrderData);
		if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
			Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error'],
        	]);
        	Log::record($wxOrder,'error');
        	Log::record('获取预支付订单失败','error');
			
		}
		//prepay_id插入数据库order表
		$this->recordPreOrder($wxOrder);

		return $signNature = $this->sign($wxOrder);

	}

	private function recordPreOrder($wxOrder){
		OrderModel::where('id','=',$this->OrderId)
			->update(['prepay_id'=>$wxOrder['prepay_id']]);
	}

	//准备小程序发起微信支付需要的参数
	private function sign($wxOrder){
		$jsApiPayData = new \WxPayJsApiPay();
		$jsApiPayData->SetAppid(config('wx.app_id'));
		$jsApiPayData->SetTimeStamp((string)time());
		$rand = md5(time().mt_rand(0,99));
		$jsApiPayData->SetNonceStr($rand);
		$jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
		$jsApiPayData->SetSignType('md5');
		//生成签名
		$wxConfig = new \WxPayConfig();
		$sign = $jsApiPayData->MakeSign($wxConfig);
		$rawValues = $jsApiPayData->GetValues();
		$rawValues['paySign'] = $sign;
		unset($rawValues['appId']);

		return $rawValues;
	}

	
}