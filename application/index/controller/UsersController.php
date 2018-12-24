<?php

namespace app\index\controller;

use app\common\model\Users;
use Thenbsp\Wechat\User\User;
use think\Controller;
use think\Request;

class UsersController extends BaseController
{
    public function index()
    {
        $rs = Users::where('user_id',$this->nowUser->user_id)->update([
            'is_bond'   =>  2
        ]);

        if ($rs) urlToRed('跳转首页',url('/'));
    }
}
