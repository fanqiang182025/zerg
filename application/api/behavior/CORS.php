<?php

namespace app\api\behavior;

class CORS{

	public function appInit(&$params){
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: token,Origin,X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
		if(request()->isOptions) {
			exit;
		}
	}
}