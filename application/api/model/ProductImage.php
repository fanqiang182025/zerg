<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class ProductImage extends BaseModel
{
	protected $hidden = ['id','img_id','delete_time','product_id'];
	public function imgUrl(){
		return $this->hasOne('Image','id','img_id');
		//return $this->belongsTo('Image','img_id','id');
	}
}
