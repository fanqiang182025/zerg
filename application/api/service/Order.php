<?php

/** Created by wangshuai....  **/

namespace app\api\service;
use app\api\model\Product as ProductModel;
use app\api\model\UserAddress as UserAddressModel;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct as OrderProductModel;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\api\service\DeliverMessage;
use think\Db;

class Order
{
	protected $oProducts;
	protected $products;
	protected $uid;

	public function place($uid,$oProducts){
		$this->uid = $uid;
		$this->oProducts = $oProducts;
		$this->products = $this->getProductByOrder();

		$status = $this->getOredrStatus();
		if(!$status['pass']) {
			$status['order_id'] = -1;
			return $status;
		}

		$orderSnap = $this->snapOrder($status);
		$order = $this->createOrder($orderSnap);
		$order['pass'] = true;
		return $order;
		
	}

	//获取订单中的商品的详细信息
	private function getProductByOrder(){
		$opIds = [];
		foreach($this->oProducts as $oProduct) {
			array_push($opIds,$oProduct['product_id']);
		}
		$products = ProductModel::all($opIds)
			->visible(['id','price','stock','name','main_img_url'])
			->toArray();
		return $products;
	}

	//获取订单的状态信息
	private function getOredrStatus(){
		$status = [
			'pass' => true,
			'orderPrice' => 0,
			'totalCount' => 0,
			'pStatusArray' => []
		];
		foreach($this->oProducts as $oProduct) {
			$pStatus = $this->getProductStatus(
				$oProduct['product_id'],$oProduct['count'],$this->products
			);
			if(!$pStatus['haveStock']) {
				$status['pass'] = false;
			}
			$status['orderPrice'] += $pStatus['totalPrice'];
			$status['totalCount'] += $pStatus['counts'];
			array_push($status['pStatusArray'],$pStatus);
		}
		return $status;
	}

	//获取订单中商品的状态信息
	private function getProductStatus($oPid,$oCount,$products){
		$pINdex = -1;
		$pStatus = [
			'id'=>0,
			'name'=>'',
			'counts'=>0,
			'price'=>0,
			'haveStock'=>false,
			'totalPrice'=>0,
			'main_img_url'=>''
		];
		for($i=0; $i<count($products); $i++) { 
			if($oPid == $products[$i]['id']) {
				$pINdex = $i;
			}	
		}
		if($pINdex == -1) {
			throw new Exception([
				'msg' => 'id为'.$oPid."的商品不存在，创建订单失败"
			]);
		}else{
			$product = $products[$pINdex];
			$pStatus['id'] = $product['id'];
			$pStatus['name'] = $product['name'];
			$pStatus['counts'] = $oCount;
			$pStatus['price'] = $product['price'];
			
			if($product['stock']-$oCount>=0) {
				$pStatus['haveStock'] = true;
			}
			$pStatus['totalPrice'] = $product['price']*$oCount;
			$pStatus['main_img_url'] = $product['main_img_url'];
			

		}

		return $pStatus;
	}

	//生成订单快照
	private function snapOrder($status){
		$snap = [
			'snapName' => '',
			'snapImg' =>'',
			'totalCount' => 0,
			'orderPrice' => 0,
			'snapAddress' => '',
			'pStatus' => []
		];

		$snap['snapName'] = $this->products[0]['name'];
		$snap['snapImg'] = $this->products[0]['main_img_url'];
		$snap['totalCount'] = $status['totalCount'];
		$snap['orderPrice'] = $status['orderPrice'];
		$snap['snapAddress'] = json_encode($this->getUserAddress());
		$snap['pStatus'] = $status['pStatusArray'];

		if(count($this->products)>1) {
			$snap['snapName'] .= '等';
		}
		return $snap;	
	}

	//获取下单地址
	private function getUserAddress(){
		$userAddress = UserAddressModel::where('user_id','=',$this->uid)
			->find();
		return $userAddress->toArray();
	}

	//创建订单
	private function createOrder($orderSnap){
		Db::startTrans();
		try{
			$orderNo = $this->makeOrderNo();

			$orderModel = new OrderModel();
			$orderModel->order_no = $orderNo;
			$orderModel->user_id = $this->uid;
			$orderModel->total_price = $orderSnap['orderPrice'];
			$orderModel->snap_img = $orderSnap['snapImg'];
			$orderModel->snap_name = $orderSnap['snapName'];
			$orderModel->total_count = $orderSnap['totalCount'];
			$orderModel->snap_items = json_encode($orderSnap['pStatus']);
			$orderModel->snap_address = $orderSnap['snapAddress'];
			$orderModel->save();
			$orderId = $orderModel->id;
			$createTime = $orderModel->create_time;
			foreach($this->oProducts as &$oProduct) {
				$oProduct['order_id'] = $orderId;
			}
			$OrderProductModel = new OrderProductModel();
			$OrderProductModel->saveAll($this->oProducts);
			Db::commit();
			return [
				'order_no'=>$orderNo,
				'order_id'=>$orderId,
				'create_time'=>$createTime
			];
		}catch (Exception $e) {
			Db::rollback();
			throw $e;
		}
	}

	//生成订单号
	public static function makeOrderNo(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    //根据客户端传过来的订单id去检测库存
    public function chekOrderStock($orderId){
    	$this->oProducts = OrderProductModel::where('order_id','=',$orderId)
    		->select();
    	$this->products = $this->getProductByOrder();
    	$status = $this->getOredrStatus();
		return $status;
    }

    public function delivery($orderId,$jumpPage=''){
    	$order = OrderModel::where('id','=',$id)
    		->find();
    	if($order['status'] != OrderStatusEnum::PAID) {
    		throw new OrderException([
    			'msg'=>'还没付款呢你想干嘛？或者订单已经更新了不要再刷了',
    			'errorCode'=>'80002',
    			'code'=>'403',
    		]);
    	}
    	$order->status = OrderStatusEnum::DELIVERED;
    	$order->save();

    	$message = new DeliverMessage();
    	return $message->sendDeliveryMessage($order,$jumpPage);
    }

	
}