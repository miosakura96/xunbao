<?php

namespace app\system\controller;

use app\common\model\Flower;
use app\common\model\Goods;
use app\common\model\Ufans;
use app\common\model\Users;
use Thenbsp\Wechat\User\User;
use think\Controller;
use think\Db;
use think\Request;

/*
 * xu ni dian zan
 * */

class ThumbsController extends Controller
{
    // xu ni dian zan
    public function dianZan()
    {
        $goods = Goods::where('goods_state', 0)->select();
        foreach ($goods as $good) {
            if ($good->goods_fav > $good->user->member->max_zan) {
                $rs = Goods::where('goods_id', $good->goods_id)->setDec('goods_fav', shuffleNumber(config('xuni')['zan'], 2, 2));
            } else {
                $rs = Goods::where('goods_id', $good->goods_id)->setInc('goods_fav', shuffleNumber(config('xuni')['zan'], 2, 2));
            }
        }
        if ($rs) {
            return json([
                'state' => 'success',
                'msg' => '虚拟点赞执行成功'
            ]);
        }
    }

    // xu ni shou cang
    public function ShouCang()
    {
        $goods = Goods::where('goods_state', 0)->select();
        foreach ($goods as $good) {
            if ($good->goods_collect > $good->user->member->max_explorer) {
                continue;
            } else {
                $rs = Goods::where('goods_id', $good->goods_id)->setInc('goods_collect', shuffleNumber(config('xuni')['see'], 2, 2));
            }
        }
        if ($rs) {
            return json([
                'state' => 'success',
                'msg' => '虚拟收藏执行成功'
            ]);
        }
    }

    // xu ni fans
    public function fans()
    {
        $len = config('xuni')['fans'];
        $users = Users::where('unuser_id', '0')->select();
        foreach ($users as $user) {

            $count = Ufans::where('nfans_uid', $user->user_id)->count();

            if ($count > $user->member->max_fans) {
                continue;
            } else {
                for ($i = 0; $i < $len; $i++) {
                    $stuffleUnUser = Db::query('select * from unuser order by rand() limit 0,1');
                    $stuffleUnUser = $stuffleUnUser[0];

                    $uFans = Ufans::where('nfans_id', $user->user_id)->where('nfans_eid', $stuffleUnUser['unuser_id'])->find();
                    if (empty($uFans)) {
                        $rs = Ufans::create([
                            'nfans_uid' => $user->user_id,
                            'nfans_eid' => $stuffleUnUser['unuser_id']
                        ]);
                    }
                }
            }

        }

        if ($rs) {
            return json([
                'state' => 'success',
                'msg' => '虚拟粉丝添加成功'
            ]);
        }
    }


}
