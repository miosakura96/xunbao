<?php

namespace app\admin\controller;

use app\common\model\Admin;
use app\common\model\Auth;
use app\common\model\Role;
use think\Controller;
use think\Request;

class RoleController extends BaseController
{
    public $authFathers;

    public $authSons;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->setVal();
    }

    public function setVal()
    {
        $this->authFathers = Auth::where('auth_pid', 0)->select();
        $this->authSons = Auth::where('auth_pid', '<>', 0)->select();
    }


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $roles = Role::select();

        return $this->fetch('index',compact('roles'));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $authFathers = $this->authFathers;
        $authSons = $this->authSons;
        return $this->fetch('add',compact('authFathers','authSons'));
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

            if (empty($request->post()['auth_ids'])) sonToReuExit('必须选择权限');

            $auth_ids = implode(',', $request->post()['auth_ids']);

            $rs = Role::create([
                'role_name'     =>  $request->post()['role_name'],
                'role_ps_ids'   => $auth_ids
            ]);
            if ($rs){
                parToURLExit('添加成功',url('/role'));
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
        if (empty($id)) parToURLExit('非法操作',url('/role'));
        $role = Role::find($id);

        $this->assign('authFathers', $this->authFathers);
        $this->assign('authSons', $this->authSons);
        $this->assign('role', $role);

        return $this->fetch('edit');
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
            if (empty($id)) parToURLExit('非法操作',url('/role'));
            if (empty($request->post()['auth_ids'])) sonToReuExit('必须选择权限');
            $putData = $request->put();

            unset($putData['_method']);

            $role_ps_ids = implode(',', $request->post()['auth_ids']);
            $rs = Role::update([
                'role_id' => $id,
                'role_name' => $putData['role_name'],
                'role_ps_ids' => $role_ps_ids
            ]);

            if ($rs){
                parToURLExit('修改成功',url('/role'));
            }
            parToURLExit('暂无修改',url('/role'));
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
        $rs = Role::destroy($id);
        // 删除角色的同时去删除处于此角色的用户
        Admin::where('role_id', $id)->delete();
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }
}
