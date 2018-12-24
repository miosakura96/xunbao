<?php

namespace app\admin\controller;

use app\common\model\Auth;
use think\Controller;
use think\Request;

class IndexController extends BaseController
{
    public function index()
    {
        $menus = session('_ADMIN_INFO_')->role->role_ps_ids;
        $topMenus = Auth::where('auth_id','in',$menus)->where('auth_pid',0)->select();
        $menus = Auth::where('auth_id','in',$menus)->where('auth_pid','<>',0)->select();
        $this->assign('menus',$menus);
        $this->assign('topMenus',$topMenus);

        return $this->fetch('index');
    }


    public function welcome()
    {
        return $this->fetch('welcome');
    }


}
