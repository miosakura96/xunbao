<?php

namespace app\index\controller;

use app\common\model\Level;
use think\Controller;
use think\Request;

class MemberController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $imgs = [
          'zs1.png','zs2.png','zs3.png','zs4.png','zs5.png',
        ];
        return $this->fetch('index',compact('imgs'));
    }


    public function single(Request $request)
    {
        if ($request->isPost()){

            $id = empty($request->post()['level_id']) ? 0 : $request->post()['level_id'];
            $levelUser = Level::where('level_state',0)->where('uid',$this->nowUser->user_id)->find();
            if (!empty($levelUser)) sonToReuExit('之前的信息还处于审核中');

            if (empty($img = $request->file('img3')))  sonToReuExit('请先上传会员支付截图');

            $info = $img->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info){
                 $level_img = $info->getSaveName();
            }else{
                sonToReuExit($info->getError());
            }

            $rs = Level::create([
                'level_content' =>  $this->nowUser->user_name . '用户，提交了会员等级申请',
                'uid' =>  $this->nowUser->user_id,
                'level_img' => $level_img,
                'level_up_id'   =>  $id,
                'level_send'    =>  0
            ]);


            if ($rs){
                urlToRed('已经提交会员升级',url('/iMember'));
            }

        }else{
            $id = input('id',null,'int');
            $this->assign('id',$id);
            return $this->fetch('single');
        }

    }


}
