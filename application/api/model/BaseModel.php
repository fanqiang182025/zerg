<?php

/** Created by wangshuai....  **/

namespace app\api\model;
use think\Model;

class BaseModel extends Model
{
	//img url拼接路径
	protected function prefixImgUrl($value,$data){
		if($data['from'] == 1){
			$value = config('seatting.img_prefix').$value;
		}
		return $value;
	}
}
