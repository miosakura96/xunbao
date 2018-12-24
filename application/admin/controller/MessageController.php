<?php

namespace app\admin\controller;

use app\common\model\Message;
use app\common\model\Users;
use think\Controller;
use think\Request;

class MessageController extends BaseController
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 只查询用户消息
        $messages = Message::where('check_type','=',0)->select();
        $this->assign('messages', $messages);
        return $this->fetch('index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return $this->fetch('add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if ($request->isPost()) {
            $postData = $request->post();
            if (!$file = $request->file('img')) {
                sonToReuExit('请选择图片');
            }
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            if ($info) {
                $postData['member_img'] = $info->getSaveName();
            } else {
                sonToReuExit($file->getError());
            };

            $rs = Member::create($postData);
            if ($rs) {
                parToURLExit('添加成功', url('/member'));
            }
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        if (empty($id)) parToURLExit('非法操作', url('/message'));
        $user = Users::find($id);
        $userData = $user->getData();
        $key = ['会员id', '会员头像', '用户名', '用户电话', '用户等级', '是否认证', '创建于', '更新于', '是否付费', '维修名称', '真实姓名', '用户地址', '会员身份证号码', '发布次数', '身份证正面', '身份证背面', '手持身份证', '认证支付截图', 'open_id', '是否短信验证', '账号类型', '发送计数', '父级id', '虚拟用户id'];
        return $this->fetch('read', compact('key', 'userData', 'user'));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        if (empty($id)) parToURLExit('非法操作', url('/member'));
        $member = Member::find($id);
        return $this->fetch('edit', compact('member'));
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isPut()) {
            if (empty($id)) parToURLExit('非法操作', url('/staff'));

            $putData = $request->put();
            if ($file = $request->file('img')) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $putData['member_img'] = $info->getSaveName();
                } else {
                    sonToReuExit($file->getError());
                };
            }
            unset($putData['_method']);

            $rs = Member::where('member_id', $id)->update($putData);
            if ($rs) {
                parToURLExit('修改成功', url('/member'));
            }
            parToURLExit('暂无修改', url('/member'));
        }
    }

    public function check(Request $request)
    {
        if ($request->isPost()) {
            $state = $request->post()['state'];
            $message_id = $request->post()['message_id'];
            $uid = $request->post()['uid'];
            $checkType = 1;

            // 1为驳回
            if ($state == 1){
                $messageState = 1;
                $messageContent = '你的审核无法通过呀';
                $isAuth = 0;
                $msg = '驳回成功';
                $userType = 1;
            }elseif($state == 2){
                // 2 为批准
                $messageState = 2;
                $isAuth = 2;
                $messageContent = '你提交的认证已审核通过';
                $msg = '批准成功';
                $userType = 2;
            }

            Message::where('message_id',$message_id)->update([
                'message_state' =>  $messageState
            ]);

            Users::where('user_id',$uid)->update([
                'is_auth'    =>  $isAuth,
                'user_type'  =>  $userType
            ]);

            $rs = Message::create([
                'check_type'    =>  '1',
                'message_content'   =>  $messageContent,
                'uid'   =>  $uid
            ]);

            if ($rs){
                return json([
                   'state'  =>  'success',
                   'msg'    =>  $msg
                ]);
            }
        }
    }


}
