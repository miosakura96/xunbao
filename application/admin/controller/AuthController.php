<?php

namespace app\admin\controller;

use app\common\model\Auth;
use think\Controller;
use think\Request;

class AuthController extends BaseController
{
    public $authFathers;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->authFathers();
    }

    public function authFathers()
    {
        $authFathers = Auth::where('auth_pid','=',0)->select();
        $this->authFathers = $authFathers;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $auths = Auth::select();
        $this->assign('auths',$auths);
        return $this->fetch('index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $this->assign('authFathers',$this->authFathers);
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

            $rs = Auth::create($postData);
            if ($rs){
                parToURLExit('添加成功',url('/auth'));
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
        if (empty($id)) parToURLExit('非法操作',url('/auth'));
        $auth = Auth::find($id);
        $authFathers = $this->authFathers;
        return $this->fetch('edit',compact('auth', 'authFathers'));
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
            if (empty($id)) parToURLExit('非法操作',url('/auth'));

            $putData = $request->put();

            unset($putData['_method']);

            $rs = Auth::where('auth_id',$id)->update($putData);
            if ($rs){
                parToURLExit('修改成功',url('/auth'));
            }
            parToURLExit('暂无修改',url('/auth'));
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
        $rs = Auth::destroy($id);
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }
}
