<?php

/** Created by wangshuai....  **/

namespace app\api\model;

class Product extends BaseModel
{
	protected $hidden = ['delete_time','from','create_time','update_time','pivot','category_id'];

	public function imgs(){
		return $this->hasMany('ProductImage','product_id','id');
	}

	public function properties(){
		return $this->hasMany('ProductProperty','product_id','id');
	}

	//img url 自动拼接
	public function getMainImgUrlAttr($value,$data){
		return $this->prefixImgUrl($value,$data);
	}

	public static function getMostRecent($count)
	{
		$products = self::limit($count)
			->order('create_time','desc')
			->select();
		return $products;
	}

	public static function getProductByCategoryId($id){
		$products = self::where('category_id','=',$id)
			->select();
		return $products;
	}

	public static function getProductDetail($id){
		$product = self::with([
			'imgs'=>function($query){
				$query->with('imgUrl')
					->order('order','asc');
			}
		])
			->with(['properties'])
			->find($id);
		return $product;
	}
}
