<?php

namespace app\admin\controller;

use app\common\model\Collect;
use app\common\model\Goods;
use app\common\model\Ips;
use app\common\model\Join;
use app\common\model\Member;
use app\common\model\Sends;
use app\common\model\Types;
use app\common\model\Users;
use think\Controller;
use think\Request;

class DummyController extends BaseController
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
        $types = Types::select();
        $users = Users::where('unuser_id','<>',0)->select();
        foreach ($users as $user) {
            $tempGood = Goods::where('goods_uid', $user->user_id)->find();
            if (!empty($tempGood)){
                $goods[] = $tempGood;
            }
        }

        return $this->fetch('index' ,compact('goods', 'types'));
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
        if (empty($id)) parToURLExit('非法操作', url('/dummy'));
        $good = Goods::find($id);
        $goodData = $good->getData();
        $this->assign('goodData', $goodData);
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

        Collect::where('gid',$id)->delete();
        Join::where('gid',$id)->delete();
        Ips::where('gid',$id)->delete();
        Sends::where('gid',$id)->delete();
        $rs = Goods::destroy($id);
        if ($rs) return json([ 'state' => 'success', 'msg' => '删除成功' ]);
    }

    public function veryDown(Request $request)
    {
        if ($request->isPost()){
            $id = $request->post()['id'];
            $rs = Goods::where('goods_id',$id)->update([
                'goods_state'   =>   1
            ]);

            if ($rs){
                $state = 'success';
                $msg = '商品已经成功下架';
            } else{
                $state = 'error';
                $msg = '商品已下架，请勿再次点击';
            }

            return json([
                'state'  =>  $state,
                'msg'    =>  $msg
            ]);
        }
    }


    public function veryUp(Request $request)
    {
        if ($request->isPost()){
            $id = $request->post()['id'];
            $good = Goods::find($id);

            if ($good->goods_state == 0){
                return json([
                    'state' =>  'error',
                    'msg'   =>  '此商品处于上架中，请在下架后，再点击重新上架'
                ]);
            }

            Collect::where('gid',$id)->delete();
            Join::where('gid',$id)->delete();
            Ips::where('gid',$id)->delete();
            Sends::where('gid',$id)->delete();

            // 生成过期时间
            $expiryTime = time() + ($good->user->member->member_time * 60 * 60);

            $rs2 = Goods::create([
                'goods_name' => $good->goods_name,
                'goods_desc' => $good->goods_desc,
                'goods_imgs' => $good->goods_imgs,
                'goods_max_price' => $good->goods_max_price,
                'goods_min_price' => $good->goods_min_price,
                'goods_price' => $good->goods_price,
                // 商品发布者
                'goods_uid' => $good->goods_uid ,
                'goods_type' => $good->goods_type,
                'expriy_time' => $expiryTime,

                // 账户区分
                'is_true'   =>  $good->is_true,
            ]);

            $rs = Goods::destroy($id);

            if ($rs && $rs2){
                return json([
                    'state' =>  'success',
                    'msg'   =>  '商品开始上架'
                ]);
            }


        }
    }
}
