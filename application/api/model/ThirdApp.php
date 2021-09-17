<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class ThirdApp extends BaseModel
{

	public static function check($ac,$se){
		$result = self::where('app_id','=',$ac)
			->where('app_secret','=',$se)
			->find();
		return $result;
	}

}
