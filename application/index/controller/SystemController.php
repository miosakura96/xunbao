<?php

namespace app\index\controller;

use app\common\model\Collect;
use app\common\model\Feedback;
use app\common\model\Flower;
use app\common\model\Info;
use app\common\model\Staff;
use think\Controller;
use think\Request;

class SystemController extends BaseController
{
    // flow and unFlow
    public function flower(Request $request)
    {
        if ($request->isPost()) {
            $id = input('id', null, 'int');
            $flower = Flower::where('eid', $id)->where('uid', $this->nowUser->user_id)->find();

            if (empty($flower)) {
                $rs = Flower::create([
                    'uid' => $this->nowUser->user_id,
                    'eid' => $id
                ]);
                $state = 'flow';
                $msg = '关注成功';
            } else {
                $rs = Flower::where('eid', $id)->where('uid', $this->nowUser->user_id)->delete();
                $state = 'unflow';
                $msg = '取消关注';
            }

            if ($rs) {
                return json([
                    'state' => $state,
                    'msg' => $msg
                ]);
            }

        }
    }


    public function collect(Request $request)
    {
        if ($request->isPost()) {
            $id = input('id', null, 'int');
            $collect = Collect::where('gid', $id)->where('uid', $this->nowUser->user_id)->find();

            if (empty($collect)) {
                $rs = Collect::create([
                    'uid'   =>    $this->nowUser->user_id,
                    'gid'   =>    $id
                ]);

                $state = 'success';
                $msg = '收藏成功';
            } else {
                $rs = Collect::where('gid',$id)->where('uid',$this->nowUser->user_id)->delete();

                $state = 'error';
                $msg = '取消收藏';
            }

            if ($rs){
                return json([
                    'state' =>  $state,
                    'msg'   =>  $msg
                ]);
            }
        }
    }


    public function agreement()
    {
        $id = input('id',null,'int');
        $info = Info::find($id);
        $this->assign('info',$info);
        return $this->fetch('agreement');
    }

    public function ask()
    {
        return $this->fetch('ask');
    }

    public function complain(Request $request)
    {
        if ($request->isPost()){
            $staff = Staff::where('staff_tel',$request->post()['staff_id'])->find();
            if (empty($staff)){
                $state = 'error';
                $msg = '没有该员工';
            }else{
                $state = 'success';
                $msg = '该员工为我司员工';
            }

            return json([
                'state' =>  $state,
                'msg'   =>  $msg
            ]);
        }else{
            return $this->fetch('complain');
        }
    }

    public function saleAfter(Request $request)
    {
        if ($request->isPost()){
            $rs = Feedback::create([
                'feedback_content'  =>  $request->post()['feedback_content'],
                'feedback_phone'  =>  $request->post()['user_tel'],
                'uid'             =>  $this->nowUser->user_id
            ]);

            if ($rs){
                return json([
                   'state'  =>  'success',
                   'msg'    =>  '已经反馈，请等待客服联系'
                ]);
            }
        }else{
            return $this->fetch('saleAfter');
        }
    }
}
