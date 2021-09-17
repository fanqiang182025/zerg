<?php

/** Created by wangshuai....  **/

namespace app\api\controller\v1;

use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    public function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    public function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }
}
