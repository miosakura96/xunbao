<?php

namespace app\index\controller;

use app\common\model\Collect;
use app\common\model\Flower;
use app\common\model\Order;
use app\common\model\Ufans;
use app\common\model\Users;
use think\Controller;
use think\Request;

class MyselfController extends BaseController
{

    public $flowsCount;

    public $fansCount;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->setVal();
    }

    public function setVal()
    {
        $this->fansCount = Flower::where('uid',$this->nowUser->user_id)->count();
        $this->flowsCount = Flower::where('eid',$this->nowUser->user_id)->count()  + Ufans::where('nfans_uid', $this->nowUser->user_id)->count();
    }

    public function index()
    {
        $flowsCount = $this->flowsCount;
        $fansCount = $this->fansCount;
        $user = $this->nowUser;
        $this->assign('user', $user);
        return $this->fetch('index',compact('flowsCount','fansCount'));
    }

    public function info(Request $request)
    {
        if ($request->isPost()) {
            $postData = $request->post();
            $rs = Users::update($postData);
            if ($rs){
                urlToRed('会员资料更新成功',url('/pInfo'));
            }
        } else {
            $user = $this->nowUser;
            $this->assign('user', $user);
            return $this->fetch('info');
        }
    }

    // myStore
    public function myStore()
    {
        return $this->fetch('myStore');
    }

    public function myOrder()
    {

        $orders = Order::where('uid',$this->nowUser->user_id)->select();
        $this->assign('orders',$orders);
        return $this->fetch('myOrder');
    }

    // buyCenter
    public function buyCenter()
    {
        $collectsCount = Collect::where('uid',$this->nowUser->user_id)->count();
        $flowsCount = $this->flowsCount;
        $fansCount = $this->fansCount;
        $user = $this->nowUser;
        $this->assign('user', $user);
        return $this->fetch('buyCenter',compact('fansCount','flowsCount','collectsCount'));
    }

    public function fans()
    {
        $id = input('id',null,'int');
        $fans = Flower::where('eid',$id)->select();
        $uFans = Ufans::where('nfans_uid', $this->nowUser->user_id)->select();

        $this->assign('uFans',$uFans);
        $this->assign('fans',$fans);
        return $this->fetch('FansList');
    }

    public function follow()
    {
        $id = input('id',null,'int');
        $flowers = Flower::where('uid',$id)->select();
        $this->assign('flowers',$flowers);
        return $this->fetch('FllowsList');
    }

    public function myCollect()
    {
        $collects = Collect::where('uid',$this->nowUser->user_id)->select();
        $this->assign('collects',$collects);
        return $this->fetch('myCollect');
    }
}
