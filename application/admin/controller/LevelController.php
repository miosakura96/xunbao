<?php

namespace app\admin\controller;

use app\common\model\Level;
use app\common\model\Users;
use Thenbsp\Wechat\User\User;
use think\Controller;
use think\Request;

class LevelController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $levels = Level::order('level_id', 'desc')->where('level_send',0)->select();
        $this->assign('levels', $levels);
        return $this->fetch('index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $level = Level::find($id);
        $levelData = $level->getData();
        $this->assign('levelData', $levelData);
        return $this->fetch('read');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function check(Request $request)
    {
        if ($request->isPost()) {
            $state = $request->post()['state'];
            $uid = $request->post()['uid'];
            $levelId = $request->post()['levelId'];
            $user = Users::find($uid);

            if ($state == 1){
                $levelContent = $user->user_name . ' 用户，你好，你提交的会员申请已经被驳回';
                $msg = '驳回成功';
            }elseif($state == 2){
                $levelContent = $user->user_name . ' 用户，你好，你提交的会员申请已被审核通过';
                $msg = '批准成功';

                Users::where('user_id',$uid)->update([
                    'user_type' =>  $levelId
                ]);
            }

            Level::where('level_id',$request->post()['level'])->update([
               'level_state'    =>  $state
            ]);

            $rs = Level::create([
                'level_content'  =>  $levelContent,
                'uid'            =>  $uid,
                'level_state'    =>  $state,
                'level_up_id'    =>  $levelId,
                'level_send'     =>  1
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
