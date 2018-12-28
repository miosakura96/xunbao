<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class HomeController extends Controller
{
    public function home()
    {
        return $this->fetch('index');
    }
}
