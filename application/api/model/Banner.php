<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class Banner extends BaseModel
{
    //banner表隐藏字段
    protected $hidden = ['delete_time','update_time'];

    //关联bannerItem模型
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
    //根据id获取banner数据库信息
    public static function getBannerId($id)
    { 
        $result = self::with(['items.img'])
            ->find($id); 
        return $result;

    }
}
