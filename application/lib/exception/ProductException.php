<?php

/** Created by wangshuai....  **/

namespace app\lib\exception;

class ProductException extends BaseException
{
    
	public $code = 404;
	public $msg = '请求的商品不存在';
	public $errorCode = 20000;
    
}
