<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\validate\OrderPlace as OrderPlaceValidate;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\PagingParameter;
use app\api\validate\IdMustBePositiveInt;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{

	protected $beforeActionList = [
		'checkExclusiveScope'=>['only'=>'placeOrder'],
		'checkPrimaryScope'=>['only'=>'getSummaryByUser,getDetail']
	];

	//下单
	public function placeOrder(){
		(new OrderPlaceValidate())->goCheck();

		$dataArray = input('post.products/a');

		$uid = TokenService::getCurrentUid();
		$order = new OrderService();
		$status = $order->place($uid,$dataArray);
		return $status;
	}

	//历史订单列表
	public function getSummaryByUser($page=1,$size=15){
		(new PagingParameter())->goCheck();

		$uid = TokenService::getCurrentUid();
		$summaryOrder = OrderModel::getSummaryByUser($uid,$page,$size);
		if($summaryOrder->isEmpty()){
			return [
				'data'=>[]
			];
		}
		$data = $summaryOrder->hidden(['snap_address','snap_items','prepay_id'])->toArray();
		return [
			'data'=>$data
		];
		
	}

	//订单详情
	public function getDetail($id){
		(new IdMustBePositiveInt())->goCheck();

		//$orderModel = new OrderModel;
		//$orderModel->setHidden(['delete_time','prepay_id','update_time']);
		$orderDetail =orderModel::get($id);
		if(!$orderDetail) {
			throw new OrderException();	
		}
		return $orderDetail->hidden(['prepay_id']);	
		
	}

	/**
	 * 获取所有订单的简要信息
	 */
	public function getSummary($page=1,$size=20)
	{
		(new PagingParameter())->goCheck();

		$summaryOrder = OrderModel::getSummaryByPage($page,$size);
		if($summaryOrder->isEmpty()){
			return [
				'page'=>$summaryOrder->currentPage(),
				'data'=>[]
			];
		}
		$data = $summaryOrder->hidden(['snap_address','snap_items'])->toArray();
		return [
			'page'=>$summaryOrder->currentPage(),
			'data'=>$data
		];
	}

	//发送模板消息
	public function delivery($id){
		(new IdMustBePositiveInt())->goCheck();

		$orderService = new OrderService();
		$success = $orderService->delivery($id);
		if($success) {
			throw new SuccessMessage();
		}
	}
}
