<?php

/** Created by wangshuai....  **/

namespace app\api\controller\v1;
use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IdMustBePositiveInt;
use app\lib\exception\ProductException;


class Product
{

    /**
     * 获取最新商品信息
     * @ur /v1/product/?count=15
     * @http get
     * @count 最新产品的个数
    */
    public function getRecent($count=15)
    { 
        (new count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        $products= $products->hidden(['summary']);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return $products;
    }

    /**
     * 获取分类内的商品
     * @ur /v1/product/by_category/?id=15
     * @http get
     * @id 分类ID
    */
    public function getAllInCategory($id){
        (new IdMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductByCategoryId($id);
        $products= $products->hidden(['summary']);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return $products;
    }

    public function getOne($id){
        (new IdMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if(!$product) {
             throw new ProductException();
        }
        return $product;
    }
}
