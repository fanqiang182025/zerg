<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class Image extends BaseModel
{
	//接口中图片只展示url
	protected $visible = ['url'];

	//img url 自动拼接
	public function getUrlAttr($value,$data){
		return $this->prefixImgUrl($value,$data);
	}
}
