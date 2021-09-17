<?php

/** Created by wangshuai....  **/

namespace app\lib\exception;

class AddressException extends BaseException
{
    
	public $code = 404;
	public $msg = '地址错误';
	public $errorCode = 40000;
    
}
