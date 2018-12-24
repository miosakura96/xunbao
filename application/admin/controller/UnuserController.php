<?php

namespace app\admin\controller;

use app\common\model\Member;
use app\common\model\UnUser;
use think\Controller;
use think\Request;

class UnuserController extends BaseController
{

    public $members;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->members();
    }

    public function members()
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
        $users = UnUser::select();
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
        $members = $this->members;
        $this->assign('members', $members);
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
                $postData['unuser_face_img'] = $info->getSaveName();
            }else{
                sonToReuExit($file->getError());
            };

            $rs = UnUser::create($postData);
            if ($rs){
                parToURLExit('添加成功',url('/unuser'));
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
        if (empty($id)) parToURLExit('非法操作',url('/unuser'));
        $unuser = UnUser::find($id);
        $members = $this->members;
        $this->assign('members', $members);
        return $this->fetch('edit',compact('unuser'));
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
            if (empty($id)) parToURLExit('非法操作',url('/unuser'));

            $putData = $request->put();
            if ($file = $request->file('img')){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info){
                    $putData['unuser_face_img'] = $info->getSaveName();
                }else{
                    sonToReuExit($file->getError());
                };
            }
            unset($putData['_method']);

            $rs = UnUser::where('unuser_id',$id)->update($putData);
            if ($rs){
                parToURLExit('修改成功',url('/unuser'));
            }
            parToURLExit('暂无修改',url('/unuser'));
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
        $rs = UnUser::destroy($id);
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }
}
