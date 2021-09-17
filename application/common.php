<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function curl_get($url,&$httpCode = 0){
	//初始化
    $curl  =  curl_init ( ) ;
    curl_setopt ( $curl , CURLOPT_URL ,  $url) ;
    curl_setopt ( $curl , CURLOPT_RETURNTRANSFER ,  1 ) ;
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    //执行命令
    $file_contents = curl_exec($curl) ;
    $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
    //关闭URL请求
    curl_close ( $curl ) ;
    //显示获得的数据
    return $file_contents;
}

function curl_post($url,$rawData=array()){
    $data_string = json_encode($rawData);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'content-Type:application/json'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

//随机获取$length位字符串
function getRandChars($length){
	$str = null;
	$strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrsrtuvwxyz';
	$max = strlen($strPol)-1;

	for ($i=0; $i < $length; $i++) { 
		$str .= $strPol[rand(0,$max)];
	}
	return $str;
}
