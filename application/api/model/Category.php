<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class Category extends BaseModel
{
	protected $hidden = ['topic_img_id','delete_time','update_time'];

	public function img(){
		return $this->belongsTo('image','topic_img_id','id');
	}
}
