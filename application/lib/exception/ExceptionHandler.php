<?php

/** Created by wangshuai....  **/

namespace app\lib\exception;

use think\exception\Handle;
use think\Request;
use think\Log;

class ExceptionHandler extends Handle
{

    private $code;
    private $msg;
    private $errorCode;
    //返回请求http的url

    public function render(\Exception $e){
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg  = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            if(config('app_debug')){
                //调试模式
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg  = '服务器内部错误';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }
        $request = Request::instance();
        $err = array(
            'msg'=>$this->msg,
            'errorCode'=>$this->errorCode,
            'requestUrl'=>$request->url(),
        );

        return json($err,$this->code);
    }


    private function recordErrorLog($e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error'],
        ]);
        Log::record($e->getMessage(),'error');
    }
    
}
