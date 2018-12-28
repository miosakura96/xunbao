<?php

namespace app\index\controller;

use app\common\model\Flower;
use app\common\model\Goods;
use app\common\model\Join;
use app\common\model\Level;
use app\common\model\Message;
use app\common\model\Sends;
use app\common\model\Users;
use Thenbsp\Wechat\User\User;
use think\Controller;
use think\Request;

class MessageController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch('index');
    }

    public function sysMsg()
    {
        $messages = Message::where('uid',$this->nowUser->user_id)->where('check_type',1)->select();
        $levels = Level::where('level_send','1')->where('uid',$this->nowUser->user_id)->select();
        return $this->fetch('systemMsg',compact('levels','messages'));
    }

    public function priceMsg()
    {
        $joins = Join::where('uid',$this->nowUser->user_id)->where('join_type', 0)->whereOr(
            "sid = " . $this->nowUser->user_id . " AND join_type = " .   1
        )->order('join_id','desc')->select();
//        echo Join::getLastSql();

//        die();
        $this->assign('joins',$joins);
        return $this->fetch('priceMsg');
    }


    public function sends()
    {
        $sends = Sends::where('uid',$this->nowUser->user_id)->select();
        return $this->fetch('sends',compact('sends'));
    }

    public function msgGoods()
    {
        $id = input('id',null,'int');
        $join_id = input('join_id',null,'int');
        $join = Join::where('join_id',$join_id)->find();
        $good = Goods::where('goods_id',$join->gid)->find();
        if (empty($good)) sonToReuExit('此商品已被删除');

        $filePath = $this->file_path;
        // 买家
        if ($id == 1){
            // 下架中
            if ($good->goods_state != 0){
                return $this->fetch('downGood',compact('good', 'filePath'));
            }else{
                // 上架中
                return $this->redirect(url('/detail',['id' => $good->goods_id]));
            }
        }else{
            // 卖家
            // 下架中
            if ($good->goods_state != 0){
                return $this->fetch('downGood',compact('good', 'filePath'));
            }else{
                // 上架中
                return $this->redirect(url('/selGood',['id' => $good->goods_id]));
            }
        }
    }

    public function sendMsg(Request $request)
    {
        if ($request->isPost()){
            $goodIds = explode(',',$request->post()['goods_ids']);
            $nowCount = $this->nowUser->member->member_now_count;

            $user = Users::where('user_id',$this->nowUser->user_id)->find();
            if ($user->send_count > $nowCount) {
                return json([
                    'state' =>  'error',
                    'msg'   =>  '发送次数已上线'
                ]);
            }

            Users::where('user_id',$this->nowUser->user_id)->setInc('send_count');
            $flowers = Flower::where('eid',$this->nowUser->user_id)->select();

            foreach ($flowers as $flower){
                foreach ($goodIds as $goodId){
                    Sends::create([
                       'send_msg'   => 'id为' . $goodId . ' 的商品，已上架，点击查看详情',
                       'uid'   =>  $flower->uid,
                       'sid'   =>  $this->nowUser->user_id,
                       'gid'   =>  $goodId,
                    ]);
                }
            }

            return json([
               'state'  =>  'success',
               'msg'    =>  '发送成功'
            ]);

        }else{
            $goods = Goods::where('goods_state',0)->where('goods_uid',$this->nowUser->user_id)->select();
            $filePath = $this->file_path;
            return $this->fetch('messgeList',compact('filePath','goods'));
        }
    }

}
