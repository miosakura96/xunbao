<?php

namespace app\system\controller;

use app\common\model\Users;
use think\Controller;
use think\Request;

/*
 * shua xin
 * */
class FlushController extends Controller
{
    public function release()
    {
        $rs = Users::where("1 = 1")->update([
           'send_count' =>  0
        ]);
        dump($rs);
    }
}
