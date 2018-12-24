<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class BaseController extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->checkIsLogin();
    }

    public function checkIsLogin()
    {
        $admin = session('_ADMIN_INFO_');
        if (empty($admin)) {
            urlToRed('请勿翻墙',url('/admin/login'));
        }
    }
}
