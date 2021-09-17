<?php

/** Created by wangshuai....  **/

namespace app\lib\exception;
use think\Exception;

class SuccessMessage extends BaseException
{
    
	public $code = 200;
	public $msg = 'ok';
	public $errorCode = 0;
    
}