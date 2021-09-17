<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class BannerItem extends BaseModel
{
	//banner_item表隐藏的字段
	protected $hidden = ['id','banner_id','delete_time','update_time','img_id'];

	//关联img模型
	public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}
