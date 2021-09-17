<?php

/** Created by wangshuai....  **/

namespace app\api\controller\v1;

use app\api\validate\IdCollection;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;
use app\api\validate\IdMustBePositiveInt;

class Theme
{

    /**
     * 获取theme信息
     * @ur /v1/theme/?ids=id1,id2,id3.....
     * @return 一组theme模型
    */
    public function getSimpleList($ids='')
    { 
        (new IdCollection())->goCheck();
        $ids = explode(',',$ids);
        $list = ThemeModel::with(['topicImg','headImg'])->select($ids);
        if($list->isEmpty()){
            throw new ThemeException();
        }
        return $list;

    }

    /**
     * 获取theme信息
     * @ur /v1/theme/id
     * @return 一组theme模型
    */
    public function getComplexOne($id){
        (new IdMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProduct($id);
        if(!$theme){
            throw new ThemeException();  
        }
        return $theme;
    }
}
