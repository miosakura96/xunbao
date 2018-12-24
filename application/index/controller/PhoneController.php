<?php

namespace app\index\controller;

use app\common\model\Users;
use Mrgoon\AliSms\AliSms;
use think\Controller;
use think\Request;

class PhoneController extends BaseController
{

    public function phoneCheck(Request $request)
    {
        if ($request->isPost()){
            $postData = $request->post();
            if ($this->redis->get($postData['phone']) == $postData['code']) {
                $this->redis->rm($postData['phone']);
                Users::where('user_id',$this->nowUser->user_id)->update([
                    'is_sms'    =>  1,
                    'user_tel'  =>  $postData['phone']
                ]);
                return json([
                    'state' =>  'success',
                    'msg'   =>  '验证合格'
                ]);
            } else {
                return json([
                    'state'  =>  'error',
                    'msg'    =>  '验证失败'
                ]);
            }
        }else{
            return $this->fetch('index');
        }
    }

    public function sendCode(Request $request)
    {
        if ($request->isPost()){
            $phone = $request->post()['phone'];
            $shuffleCode = substr(str_shuffle('1234567890'), 0, 4);
            $this->redis->set($phone,$shuffleCode,1200);
            $config = [
                'access_key' => config('ali')['access_key'],
                'access_secret' => config('ali')['access_secret'],
                'sign_name' => config('ali')['sign_name']
            ];

            $aliSms = new AliSms();
            $response = $aliSms->sendSms($phone, config('ali')['code'],[
                'code'  =>  $shuffleCode
            ],$config);

            return json($response);
        }
    }


}
