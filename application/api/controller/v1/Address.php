<?php

/** Created by wangshuai....  **/

namespace app\api\controller\v1;
use app\api\validate\Address as AddressValidate;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\lib\exception\UserException;
use app\lib\exception\SuccessMessage;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope'=>['only'=>'createOrUpdateAddress,getAddress']
    ];

    /**
     * 添加或者更新地址
     * @ur /v1/banner/:id
     * @http get
     * @id banner的id号
    */
    public function createOrUpdateAddress(){ 
        $address = new AddressValidate();
        $address->goCheck();

        $uid = TokenService::getCurrentUid(); 
        $user = UserModel::find($uid); 
        if(!$user){
            throw new UserException();
        }

        $dataArray = $address->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if(!$userAddress) {
            $user->address()->save($dataArray);
        }else{
            $user->address->save($dataArray);
        }

        throw new SuccessMessage();
    }

    public function getAddress(){
        $uid = TokenService::getCurrentUid();

        $address = UserAddress::where('user_id','=',$uid)->find();
        if(!$address){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode'=>60001
            ]);
        }

        return $address;

    }
}
