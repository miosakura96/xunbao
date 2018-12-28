<?php

namespace app\index\controller;

use app\common\model\Collect;
use app\common\model\Goods;
use app\common\model\Ips;
use app\common\model\Join;
use app\common\model\Sends;
use think\Controller;
use think\Request;

class ManageController extends BaseController
{

    public function index(Request $request)
    {
        if ($request->isPost()){
            $state = $request->post()['state'];
            $goodsIds = explode(',',$request->post()['goods_ids']);

            // 当前上架个数
            $nowGoodsCount = Goods::where('goods_uid', $this->nowUser->user_id)->where('goods_state', 0)->count();

            foreach ($goodsIds as $key => $goodsId){
                if ($state == 0){

                    $msg = '你选中的商品已经下架';

                    Goods::where('goods_id',$goodsId)->update([
                        'goods_state'   =>  1
                    ]);
                }elseif ($state == 1){
                    // 重新上架

                    if ($nowGoodsCount > $this->nowUser->member->member_now_count) {
                        return json([
                            'state' => 'error',
                            'msg' => '选择的商品过多，超过了会员的上架次数'
                        ]);
                    }

                    $nowGood = Goods::find($goodsId);
                    // 生成当前商品的过期时间
                    $expiryTime = time() + ($this->nowUser->member->member_time * 60 * 60);
                    Goods::create([
                        'goods_name' => $nowGood->goods_name,
                        'goods_desc' => $nowGood->goods_desc,
                        'goods_imgs' => $nowGood->goods_imgs,
                        'goods_max_price' => $nowGood->goods_max_price,
                        'goods_min_price' => $nowGood->goods_min_price,
                        'goods_price' => $nowGood->goods_price,
                        'goods_uid' => $nowGood->goods_uid,
                        'goods_type' => $nowGood->goods_type,
                        'expriy_time' => $expiryTime,
                    ]);

                    // 上架后删除
                    Goods::where('goods_id',$goodsId)->delete();
                    Collect::where('gid',$goodsId)->delete();
                    Ips::where('gid',$goodsId)->delete();
                    Join::where('gid',$goodsId)->delete();
                    Sends::where('gid',$goodsId)->delete();

                    $msg = '此商品已经重新上架';


                }elseif ($state == 2){
                    Goods::where('goods_id',$goodsId)->delete();
                    Collect::where('gid',$goodsId)->delete();
                    Ips::where('gid',$goodsId)->delete();
                    Join::where('gid',$goodsId)->delete();
                    Sends::where('gid',$goodsId)->delete();

                    $msg = '已经强制删除你选中的商品';
                }

            }

            return json([
                'state' =>  'success',
                'msg'   =>  $msg
            ]);

        }else{
            $id = input('id', null, 'int');

            if ($id == 1) {
                $goodState = 0;
            } elseif ($id == 2) {
                $goodState = 2;
            } elseif ($id == 3) {
                $goodState = 1;
            } elseif ($id == 4) {
                $goodState = 1;
            }

            $filePath = $this->file_path;
            $goods = Goods::where('goods_uid', $this->nowUser->user_id)->where('goods_state', $goodState)->select();
            return $this->fetch('index',compact('goods','id','filePath'));
        }
    }



}
