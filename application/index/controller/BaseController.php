<?php

namespace app\index\controller;

use app\common\model\Collect;
use app\common\model\Flower;
use app\common\model\Goods;
use app\common\model\Users;
use think\cache\driver\Redis;
use think\Controller;
use think\Request;

class BaseController extends Controller
{

    public $flower;

    public $collect;

    public $redis;

    public $nowUser;

    public $viewRepStr;

    public $file_path;


    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->newRedis();
        $this->isLogin();
        $this->setUser();
        $this->checkGoods();
        $this->setVal();
    }

    public function newRedis()
    {
        $this->redis = new Redis();
    }

    public function setUser()
    {
        return $this->nowUser = Users::find(session('user_info')->user_id);
    }

    public function checkGoods()
    {
        $now = time();
        $goods = Goods::where('expriy_time','<',$now)->where('goods_state',0)->select();
        if (!empty($goods)){
            foreach ($goods as $good){
                $goodsIds[] = $good->goods_id;
            }
            Goods::whereIn('goods_id',$goodsIds)->update([
                'goods_state'   =>    1
            ]);
        }
    }


    public function isLogin()
    {
        $recordVal = empty($_SERVER['PATH_INFO']) ? '/' : str_replace('.html','',$_SERVER['PATH_INFO']);
        // 一次性缓存
        session('onceUrl',$recordVal);

        $user_info = session('user_info');
        if (empty($user_info)) {
            echo "<script> window.location.href = '" . url('/index/login/login'). "' </script>";
            die();
        }
    }

    public function setVal()
    {
        $flows = Flower::where('uid', $this->nowUser->user_id)->select();
        $collects = Collect::where('uid', $this->nowUser->user_id)->select();

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

        $this->file_path = config('view_replace_str')['__IMG__'];
        $this->flower = $tempFlows;
        $this->collect = $tempCollects;
        $this->viewRepStr = config('view_replace_str')['__UPL__'];
    }

}
