<?php

/** Created by wangshuai....  **/

namespace app\api\controller\v1;

use app\api\validate\IdMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use Exception;

class Banner
{

    /**
     * 获取指定id的banner信息
     * @ur /v1/banner/:id
     * @http get
     * @id banner的id号
    */
    public function getBanner($id)
    { 
        (new IdMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerId($id); 
        if(!$banner){
            throw new BannerMissException();    
        }
        return $banner;
    }
}
