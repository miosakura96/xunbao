<?php

namespace app\admin\controller;

use app\common\model\Admin;
use think\Controller;
use think\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        if ($request->isPost()){
            $postData = $request->post();


            if ($postData['name'] == '' || $postData['password'] == '') return $this->error('信息不完整，请填写完整');
            $password = $postData['password'];
            $password = md5($password);

            $loginer = Admin::where('admin_name',$postData['name'])->where('admin_password',$password)->find();

            if (empty($loginer)){
                echo "<script>alert('账号密码不正确，请重新输入'); location.href = '" . url('/admin/login') . "'</script>";
            }else{
                Admin::where('id',$loginer->id)->update([
                    'login_at'  =>  date('Y-m-d H:i:s')
                ]);
                session('_ADMIN_INFO_',$loginer);
                echo "<script>alert('登录成功'); location.href = '" . url('/notAdmin') . "'</script>";
            }
        }else{
            return $this->fetch('login');
        }
    }

    // 退出登录
    public function logout()
    {
        session('_ADMIN_INFO_',null);

        urlToRed('退出成功', url('/admin/login'));
    }

    public function changePassword(Request $request)
    {
        if ($request->isPost()){
            $password = $request->post()['password'];
            if (empty($password)){
                parToURLExit('没有做任何修改', url('/notAdmin'));
            }

            $rs = Admin::where('id', $request->post()['id'])->update([
                'admin_password'    =>  md5($password)
            ]);

            if ($rs) parToURLExit('密码已经重新修改', url('/notAdmin'));

        }else{
            return $this->fetch('changePassword');
        }
    }

    // 一键重新登陆
    public function oneChange()
    {
        $adminId = session('_ADMIN_INFO_')->id;
        $admin = Admin::find($adminId);
        session('_ADMIN_INFO_',$admin);
        urlToRed('重新登陆登录成功',url('/notAdmin'));
    }
}
