<?php

namespace app\admin\controller;

use app\common\model\Collect;
use app\common\model\Goods;
use app\common\model\Ips;
use app\common\model\Join;
use app\common\model\Sends;
use app\common\model\Types;
use think\Controller;
use think\Request;

class GoodsController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $types = Types::select();
        $goods = Goods::select();
        $this->assign('goods',$goods);
        return $this->fetch('index',compact('types'));
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
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if (empty($id)) parToURLExit('非法操作', url('/goods'));
        $good = Goods::find($id);
        $goodData = $good->getData();

        $goodsKey = [
          '商品序号','商品名称','商品类型','商品描述','商品图片','最高价','最低价','心理价','发布者','发布时间','更新时间','目前状态','到期时间','收藏数','喜爱数','是否虚拟用户'
        ];

        $this->assign('goodData', $goodData);
        $this->assign('goodsKey', $goodsKey);

        $strRe = config('view_replace_str')['__IMG__'];

        return $this->fetch('read',compact('strRe'));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
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

    public function bidder()
    {
        $id = input('id');

        $joins = Join::where('gid',$id)->where('join_type',0)->select();

        $this->assign('joins',$joins);
        return $this->fetch('bidder');
    }
}
