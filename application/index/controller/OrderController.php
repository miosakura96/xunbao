<?php

namespace app\index\controller;

use app\common\model\Goods;
use think\Controller;
use think\Request;

class OrderController extends BaseController
{
    public function seeGood()
    {
        $id = input('id',null,'int');
        $good = Goods::find($id);
        if (empty($good))   sonToReuExit('此商品已被删除',url('/seeGood'));

        $this->assign('good',$good);
        return $this->fetch('seeGood');
    }


}
