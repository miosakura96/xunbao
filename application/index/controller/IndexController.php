<?php

namespace app\index\controller;

use app\common\model\BroadCast;
use app\common\model\Collect;
use app\common\model\Flower;
use app\common\model\Types;
use think\Db;
use think\Request;

class IndexController extends BaseController
{


    public function index()
    {
        $broadcasts = BroadCast::select();
        $types = Types::select();
        $user = $this->nowUser;
        $viewRepStr = $this->viewRepStr;
        $qiNiuUrl = config('qiniu')['qiniuUrl'];

        return $this->fetch('index', compact('broadcasts', 'types', 'user' , 'viewRepStr', 'qiNiuUrl'));
    }


    public function getData()
    {
        // 虚拟用户的首页置顶商品
        $notTrueUserGoods = Db::query("SELECT goods.goods_collect,member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.is_true = 1 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC");
        $goods = Db::query("SELECT goods.goods_collect,member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.is_true = 0 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC limit 0,10");
        $goods = array_merge($notTrueUserGoods, $goods);

        $arr = [
            'goods' => $goods,
            'flows' =>  $this->flower,
            'collects' => $this->collect
        ];
        return json($arr);
    }


    public function down(Request $request)
    {
        if ($request->isPost()) {

            if($this->nowUser->is_bond == 1){
                return json([
                    'state' =>  'error',
                    'msg'   =>  '请先交纳保证金'
                ]);
            }

            $num = $request->post()['num'];
            $goods = Db::query("SELECT goods.goods_collect,member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.is_true = 0 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC limit $num,3");

            return json([
                'state' =>  'success',
                'goods'  =>  empty($goods) ? 0 : $goods
            ]);

        }
    }

}
