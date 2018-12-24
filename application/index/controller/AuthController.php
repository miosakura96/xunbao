<?php

namespace app\index\controller;

use app\common\model\Message;
use app\common\model\Users;
use think\Controller;
use think\Request;

class AuthController extends BaseController
{

    public function index(Request $request)
    {
        $userAuth = $this->nowUser->is_auth;
        if ($userAuth == 1) {
            sonToReuExit('你目前是申请中呀');
        } elseif ($userAuth == 2) {
            sonToReuExit('你已认证');
        } else {
            urlToRed('点击跳转申请页面',url('/nOne'));
        }
    }

    public function one(Request $request)
    {
        if ($request->isPost()) {
            $postData = $request->post();

            if (empty($img1 = $request->file('img1')) || empty($img2 = $request->file('img2'))) {
//                return json([
//                   'msg'    =>  '11'
//                ]);
                sonToReuExit('请选择身份证正反面');
            }

            $info1 = $img1->move(ROOT_PATH . 'public' . DS . 'uploads');
            $info2 = $img2->move(ROOT_PATH . 'public' . DS . 'uploads');

            if ($info1 && $info2) {
                $postData['user_card_front_img'] = $info1->getSaveName();
                $postData['user_card_after_img'] = $info2->getSaveName();
            } else {
                sonToReuExit($info1->getError() . '---' . $info2->getError());
            };

            $rs = Users::where('user_id', $this->nowUser->user_id)->update([
                'user_card_front_img' => $postData['user_card_front_img'],
                'user_card_after_img' => $postData['user_card_after_img'],
                'real_name' => $postData['real_name'],
                'user_card' => $postData['user_card'],
                'user_tel' => $postData['user_tel'],
            ]);

            if ($rs) $this->redirect(url('/nTwo'));

        } else {
            return $this->fetch('one');

        }
    }

    public function two()
    {
        return $this->fetch('two');
    }

    public function three(Request $request)
    {
        if ($request->isPost()) {
            if (empty($img3 = $request->file('img3'))) sonToReuExit('请选择支付截图');
            $info3 = $img3->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info3) {
                $auth_user_card_img = $info3->getSaveName();
                $rs = Users::where('user_id', $this->nowUser->user_id)->update([
                    'auth_user_card_img' => $auth_user_card_img,
                    'is_auth' => 1
                ]);

                $rs2 = Message::create([
                    'message_content' => $this->nowUser->user_name . '提交了身份检查申请',
                    'uid' => $this->nowUser->user_id
                ]);


                if ($rs && $rs2) $this->redirect(url('/nFour'));
            } else {
                sonToReuExit($info3->getError());
            }

        } else {
            return $this->fetch('three');
        }
    }

    public function four()
    {
        return $this->fetch('four');
    }


}
