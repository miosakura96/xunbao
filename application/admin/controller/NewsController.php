<?php

namespace app\admin\controller;

use app\common\model\News;
use think\Controller;
use think\Request;

class NewsController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $news = News::select();
        $this->assign('news',$news);
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
                $postData['news_img'] = $info->getSaveName();
            }else{
                sonToReuExit($file->getError());
            };

            $rs = News::create($postData);
            if ($rs){
                parToURLExit('添加成功',url('/news'));
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
        if (empty($id)) parToURLExit('非法操作',url('/news'));
        $new = News::find($id);
        return $this->fetch('edit',compact('new'));
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
                    $putData['news_img'] = $info->getSaveName();
                }else{
                    sonToReuExit($file->getError());
                };
            }
            unset($putData['_method']);

            $rs = News::where('news_id',$id)->update($putData);
            if ($rs){
                parToURLExit('修改成功',url('/news'));
            }
            parToURLExit('暂无修改',url('/news'));
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
        $rs = News::destroy($id);
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }
}
