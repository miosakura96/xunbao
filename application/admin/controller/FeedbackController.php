<?php

namespace app\admin\controller;

use app\common\model\Feedback;
use think\Controller;
use think\Request;

class FeedbackController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $feedbacks = Feedback::order('feedback_id','desc')->select();
        $this->assign('feedbacks',$feedbacks);
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
            if (!$file = $request->file('img')){
                sonToReuExit('请选择图片');
            }
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            if ($info){
                $postData['broad_img'] = $info->getSaveName();
            }else{
                sonToReuExit($file->getError());
            };

            $rs = BroadCast::create($postData);
            if ($rs){
                parToURLExit('添加成功',url('/broad'));
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
        if (empty($id)) parToURLExit('非法操作', url('/feedback'));
        $feedBack = Feedback::find($id);
        $key = ['投诉id', '投诉内容', '投诉电话', '创建于', '更新于', '投诉用户id', '投诉是否审核'];
        $feedBackData = $feedBack->getData();
        return $this->fetch('read',compact('feedBackData', 'key'));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        if (empty($id)) parToURLExit('非法操作',url('/broad'));
        $broad = BroadCast::find($id);
        return $this->fetch('edit',compact('broad'));
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
        if ($request->isPut()){
            if (empty($id)) parToURLExit('非法操作',url('/broad'));

            $putData = $request->put();
            if ($file = $request->file('img')){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info){
                    $putData['broad_img'] = $info->getSaveName();
                }else{
                    sonToReuExit($file->getError());
                };
            }
            unset($putData['_method']);

            $rs = BroadCast::where('broad_id',$id)->update($putData);
            if ($rs){
                parToURLExit('修改成功',url('/broad'));
            }
            parToURLExit('暂无修改',url('/broad'));
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if (empty($id)) return json([ 'state' => 'error', 'msg' => '非法操作' ]);
        $rs = Feedback::destroy($id);
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }
}
