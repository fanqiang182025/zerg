<?php
namespace app\api\controller\v1;

use app\api\service\Pay as PayService;
use app\lib\service\WxNotify;

class Pay extends BaseController
{
	protected $beforeActionList=[
		'checkExclusiveScope'=>['only'=>'getPreOrder']
	];

	//请求预定单接口 接收参数并处理成微信服务器需要的参数供小程序拉起支付
	public function getPreOrder($id){
		$pay = new PayService($id);
		return $pay->pay();
	}

	//支付之后接受微信通知
	public function reciveNotify(){
		$notify = new WxNotify();
		$notify->Handle();

	}
}
	