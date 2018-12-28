<?php

namespace app\admin\controller;

use app\common\model\Auth;
use app\common\model\Member;
use app\common\model\Users;
use think\Controller;
use think\Request;

class UsersController extends BaseController
{

    public $members;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->setVal();
    }

    public function setVal()
    {
        $this->members = Member::select();
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $users = Users::where('unuser_id',0)->select();

        $this->assign('users',$users);
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
                $postData['staff_img'] = $info->getSaveName();
            }else{
                sonToReuExit($file->getError());
            };

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
        if (empty($id)) parToURLExit('非法操作',url('/Users'));
        $user = Users::find($id);
        $members = $this->members;
        return $this->fetch('edit',compact('user','members'));
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
            unset($putData['_method']);

            $rs = Users::where('user_id',$id)->update($putData);
            if ($rs){
                parToURLExit('修改成功',url('/users'));
            }
            parToURLExit('暂无修改',url('/users'));
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
        $rs = Users::destroy($id);
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }
}
