<?php

namespace app\index\controller;

use app\common\model\Users;
use think\Controller;
use think\Request;

class LoginController extends Controller
{

    public $appid;

    public $appsec;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->setVal();
    }

    public function setVal()
    {
        $this->appid = config('weixin')['appid'];
        $this->appsec = config('weixin')['appsec'];
    }


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function login()
    {
        $link = 'http://yipaizaixian.cn/' . url('index/login/autoLogin');
        $appid = $this->appid;
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $link . '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        $this->redirect($url);
    }


    public function autoLogin()
    {
        $appid = $this->appid;
        $secret = $this->appsec;
        $code = $_GET["code"];


        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $get_token_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        $json_obj = json_decode($res, true);



        //根据openid和access_token查询用户信息
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
        $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $get_user_info_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);

        //解析json
        $user_obj = json_decode($res, true);


        $user_open_id = $user_obj['openid'];
        $user = Users::where('open_id',$user_open_id)->find();
        // 如果用户已经存在，取出信息直接登录，否则创建用户
        if (empty($user)){
            // first Login
            $user = Users::create([
                'open_id'           =>  $user_obj['openid'],
                'user_name'         =>  $user_obj['nickname'],
                'user_face_img'     =>  $user_obj['headimgurl'],
                'we_chat_name'      =>  $user_obj['nickname'],
            ]);
        }
        // 写入session
        session('user_info',$user);
        // 跳转主页面
        return $this->redirect(url('/'));
    }



}
