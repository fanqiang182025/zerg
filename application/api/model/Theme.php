<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class Theme extends BaseModel
{

	protected $hidden = ['topic_img_id','delete_time','head_img_id','update_time'];

	public function topicImg(){
		return $this->belongsTo('Image','topic_img_id','id');
	}

	public function headImg(){
		return $this->belongsTo('Image','topic_img_id','id');
	}

	public function products(){
		return $this->belongsToMany('Product','theme_product','product_id','theme_id');
	}

	public static function getThemeWithProduct($id){
		$theme = self::with(['headImg','topicImg','products'])
			->find($id);
		return $theme;
	}


	
}
