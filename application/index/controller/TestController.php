<?php

namespace app\index\controller;

use app\common\model\Base;
use app\common\model\BroadCast;
use app\common\model\Collect;
use app\common\model\Flower;
use app\common\model\Types;
use app\common\model\Users;
use think\Controller;
use think\Db;
use think\Request;

class TestController extends BaseController
{

    public function index()
    {
        $num = 20;
        $broadcasts = BroadCast::select();
        $types = Types::select();
        // 虚拟用户的首页置顶商品
        $notTrueUserGoods = Db::query("SELECT member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.is_true = 1 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC");
        $goods = Db::query("SELECT member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.is_true = 0 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC limit 0,$num");
        $goods = array_merge($notTrueUserGoods, $goods);
        $user = Users::where('user_id',1)->find();

        $flows = Flower::where('uid', $user->user_id)->select();
        $collects = Collect::where('uid', $user->user_id)->select();


        if (empty($flows)) {
            $tempFlows = [];
        } else {
            foreach ($flows as $flow) {
                $tempFlows[] = $flow->eid;
            }
        }

        if (empty($collects)) {
            $tempCollects = [];
        } else {
            foreach ($collects as $collect) {
                $tempCollects[] = $collect->gid;
            }
        }

//        dump($goods);
//        die();

        $arr = [
            'goods' => $goods,
            'flows' => $tempFlows,
            'collects' => $tempCollects,
            'user' => $user

        ];
        return json($arr);
//        return $this->fetch('index', compact('broadcasts', 'types', 'goods', 'flows', 'tempFlows', 'user', 'tempCollects','num'));
    }

    public function getAll()
    {
        dump($_COOKIE);
        dump(json_decode($_COOKIE['allImgs']));
    }

    public function testPage()
    {
        return $this->fetch('index');
    }


    public function testDown(Request $request)
    {
        if ($request->isPost()) {

//            if($this->nowUser->is_bond == 1){
//                return json([
//                    'state' =>  'error',
//                    'msg'   =>  '请先交纳保证金'
//                ]);
//            }
            $num = empty($request->post()['num']) ? 1 : $request->post()['num'];
            $user = Users::where('user_id',1)->find();
            $goods = Db::query("SELECT member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.is_true = 0 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC limit $num,1");
            $flows = Flower::where('uid', $user->user_id)->select();
            $collects = Collect::where('uid', $user->user_id)->select();

            if (empty($flows)) {
                $tempFlows = [];
            } else {
                foreach ($flows as $flow) {
                    $tempFlows[] = $flow->eid;
                }
            }

            if (empty($collects)) {
                $tempCollects = [];
            } else {
                foreach ($collects as $collect) {
                    $tempCollects[] = $collect->gid;
                }
            }

            return json([
                'state' => 'success',
                'flows' => $tempFlows,
                'collects' => $tempCollects,
                'goods' => empty($goods) ? 0 : $goods[0]
            ]);

        }
    }


}
