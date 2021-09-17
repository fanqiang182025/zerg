<?php

/** Created by wangshuai....  **/

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{

    /**
     * 获取分类信息
     * @ur /v1/category/
     * @http get
    */
    public function getAllCategory()
    { 
        $categorys = CategoryModel::all([],'img'); 
        if($categorys->isEmpty()){
            throw new CategoryException();    
        }
        return $categorys;
    }
}
