<?php

namespace app\admin\controller;

use app\common\model\Admin;
use app\common\model\Role;
use think\Controller;
use think\Request;

class AdminController extends BaseController
{
    public $roles;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->roles();
    }

    public function roles()
    {
        $this->roles = Role::select();
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $admins = Admin::select();
        $this->assign('admins', $admins);
        return $this->fetch('index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $roles = $this->roles;
        return $this->fetch('add', compact('roles'));
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
            if ($postData['admin_name'] == 'admin') sonToReuExit('管理员账户不能是admin');
            $admin = Admin::where('admin_name', $postData['admin_name'])->find();
            if (!empty($admin)) sonToReuExit('此名称已经被使用...');
            $postData['admin_password'] = md5($postData['admin_password']);
            $postData['login_at'] = date('Y-m-d H:i:s');
            $rs = Admin::create($postData);
            if ($rs) {
                parToURLExit('添加成功', url('/admin'));
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
        if (empty($id)) parToURLExit('非法操作', url('/admin'));
        $admin = Admin::find($id);
        $roles = $this->roles;
        return $this->fetch('edit', compact('admin', 'roles'));
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

            if (empty($putData['admin_password'])) {
                unset($putData['admin_password']);
            } else {
                md5($putData['admin_password']);
            }

            unset($putData['_method']);

            $rs = Admin::where('id', $id)->update($putData);
            if ($rs) {
                parToURLExit('修改成功', url('/admin'));
            }
            parToURLExit('暂无修改', url('/admin'));
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
        if (empty($id)) return json(['state' => 'error', 'msg' => '非法操作']);
        $rs = Admin::destroy($id);
        if ($rs) return json(['state' => 'success', 'msg' => '删除成功']);
    }
}
