<?php

/** Created by wangshuai....  **/

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\model\Product as ProductModel;
use app\lib\enum\OrderStatusEnum;
use think\Loader;
use  think\Db;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxNotify extends \WxPayNotify
{
	public function NotifyProcess($objData, $config, &$msg)
	{
		//result_code
		//out_trade_no
		//updateOrderStatus
		//reduceStock
		if($objData['result_code'] == 'SUCCESS'){
			Db::startTrans();
			try{
				$order = OrderModel::where('order_no','=',$objData['out_trade_no'])
					->find();
				if($order->status == OrderStatusEnum::UNPAID) {
					$orderService = new OrderService();
					$stockStatus = $orderService->chekOrderStock($order->id);
					if($stockStatus['pass']) {
						$this->updateOrderStatus($order->id,true);
						$this->reduceStock($stockStatus);
					}else{
						$this->updateOrderStatus($order->id,false);
					}
				}
				Db::commit();
				teturn true;
				
			}catch(Exception $e){
				Db::rollback();
				log::record($e,'error');
				return false;
			}
		}else{
			return ture;
		}
		
	}

	//修改订单支付状态
	public function updateOrderStatus($orderId,$success){
		$status = $success ? OrderStatusEnum::PAID :  OrderStatusEnum::PAID_BUT_OUT_OF;
		OrderModel::where('id','=',$orderId)
			->update(['status'=>$status]);
	}

	//减库存
	public function reduceStock($stockStatus){
		foreach($stockStatus['pStatusArray'] as $singlePstatus) {
			ProductModel::where('id','=',$singlePstatus['id'])
				->setDec('stock',$singlePstatus['count']);
		}
	}
}