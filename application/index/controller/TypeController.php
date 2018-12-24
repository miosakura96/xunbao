<?php

namespace app\index\controller;

use app\common\model\BroadCast;
use app\common\model\Collect;
use app\common\model\Flower;
use app\common\model\Types;
use think\Controller;
use think\Db;
use think\Request;

class TypeController extends BaseController
{

    public function index()
    {
        $types = Types::select();
        $this->assign('types',$types);
        return $this->fetch('index');
    }

    public function type()
    {
        $id = input('id',null,'int');
        $types = Types::select();
        $user = $this->nowUser;
        $viewRepStr = $this->viewRepStr;
        return $this->fetch('type', compact('types', 'user', 'id','viewRepStr'));
    }

    public function typeData()
    {
        $id = input('id',null,'int');
        $goods = Db::query("SELECT goods.goods_collect,member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.goods_type = {$id} AND goods.is_true = 0 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC limit 0,10");
        $notTrueUserGoods = Db::query("SELECT goods.goods_collect,member.member_img,goods.is_true,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.goods_type = {$id} AND goods.is_true = 2 AND goods.goods_state = 0 ORDER BY goods.goods_id DESC");
        $count = count($goods);
        $goods = array_merge($notTrueUserGoods,$goods);

        $arr = [
            'count' => $count,
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

            $typeId = $request->post()['type_id'];
            $num = $request->post()['num'];
            $goods = Db::query("SELECT goods.goods_collect,member.member_img,goods.goods_fav,users.user_id,goods.goods_uid,goods.created_at,goods.goods_name,goods.goods_id,goods.goods_desc,goods.goods_imgs,goods.goods_max_price,goods.goods_min_price,goods.goods_price,goods.expriy_time,users.user_face_img,users.user_name,types.type_name FROM goods INNER JOIN types ON goods.goods_type = types.type_id INNER JOIN users ON goods.goods_uid = users.user_id INNER JOIN member ON member.member_id = users.user_type WHERE goods.goods_type = $typeId AND goods.goods_state = 0 AND goods.is_true = 0 ORDER BY goods.goods_id DESC limit $num,3");

            return json([
                'state' =>  'success',
                'goods'  =>  empty($goods) ? 0 : $goods
            ]);

        }
    }


}
