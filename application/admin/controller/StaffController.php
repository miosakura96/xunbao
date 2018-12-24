<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Staff;

class StaffController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $staffs = Staff::select();
        $this->assign('staffs',$staffs);
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

            $staff = Staff::where('staff_num',$postData['staff_num'])->find();
            if (!empty($staff)) {
                sonToReuExit('该员工编号已被使用');
            }
            $rs = Staff::create($postData);
            if ($rs){
                parToURLExit('添加成功',url('/staff'));
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
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        if (empty($id)) parToURLExit('非法操作',url('/staff'));
        $staff = Staff::find($id);
        return $this->fetch('edit',compact('staff'));
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
            if (empty($id)) parToURLExit('非法操作',url('/staff'));

            $putData = $request->put();
            if ($file = $request->file('img')){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info){
                    $putData['staff_img'] = $info->getSaveName();
                }else{
                    sonToReuExit($file->getError());
                };
            }
            unset($putData['_method']);

            $rs = Staff::where('staff_id',$id)->update($putData);
            if ($rs){
                parToURLExit('修改成功',url('/staff'));
            }
            parToURLExit('暂无修改',url('/staff'));
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
        $rs = Staff::destroy($id);
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }
}
