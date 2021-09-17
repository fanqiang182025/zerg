<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

use think\Route;
//Route::rule("路由表达式","路由地址"," 请求类型","路由参数 数组","变量规则 数组");
//请求类型 POST GET DELETE PUT *
//Route::any("test","sample/index/test");
//Route::rule("test","sample/index/test","GET|POST",['https'=>false]);

//banner接口
Route::get("api/:version/banner/:id","api/:version.Banner/getBanner");

//theme接口---专题
Route::group("api/:version/theme",function(){
	//theme列表
	Route::get("","api/:version.Theme/getSimpleList");
	//theme详情
	Route::get(":id","api/:version.Theme/getComplexOne",[],['id'=>'\d+']);
});

//product接口
Route::group("api/:version/product",function(){
	//最新商品
	Route::get("recent","api/:version.Product/getRecent");
	//分类商品
	Route::get("by_category","api/:version.Product/getAllInCategory");
	//商品详情
	Route::get(":id","api/:version.Product/getOne",[],['id'=>'\d+']);
});


//category接口---分类
Route::get("api/:version/category/","api/:version.Category/getAllCategory");

//token接口
Route::post("api/:version/token/user","api/:version.Token/getToken");
Route::post("api/:version/token/verify","api/:version.Token/verifyToken");
//第三方获取Token
Route::post("api/:version/token/app","api/:version.Token/getAppToken");



//地址接口
Route::post("api/:version/address","api/:version.Address/createOrUpdateAddress");
Route::get("api/:version/address","api/:version.Address/getAddress");


//下单接口
Route::group("api/:version/order",function(){
	//下单
	Route::post("","api/:version.Order/placeOrder");
	//订单列表
	Route::get("by_user","api/:version.Order/getSummaryByUser");
	//订单详情
	Route::get(":id","api/:version.Order/getDetail",[],['id'=>'\d+']);

	//第三方获取全部订单简要信息(分页)
	Route::get("paginate","api/:version.Order/getSummary");
	Route::get("delivery","api/:version.Order/delivery");
	
	
});


//支付接口
Route::group("api/:version/pay",function(){
	//最新商品
	Route::post("pre_order","api/:version.Pay/getPreOrder");
	//分类商品
	Route::post("notify","api/:version.Pay/reciveNotify");
});









