<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class Order extends BaseModel
{
	protected $hidden = ['delete_time','user_id','update_time'];
	protected $autoWriteTimestamp = true;

	public function getSnapItemsAttr($value){
		if(empty($value)) {
			return null;
		}
		return json_decode($value);
	}

	public function getSnapAddressAttr($value){
		if(empty($value)) {
			return null;
		}
		return json_decode($value);
	}

	public static function getSummaryByUser($uid,$page,$size){
		$summaryOrder = self::where('user_id','=',$uid)
			->order('create_time desc')
			->paginate($size,true,['page'=>$page]);
		return $summaryOrder;
	}

	public static function getSummaryByPage($page,$size){
		$summaryOrder = self::order('create_time desc')
			->paginate($size,true,['page'=>$page]);
		return $summaryOrder;
	}
}
