<?php

namespace app\common\model;

use think\Model;

class Users extends Model
{
    protected $table = 'users';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    // is_auth
    public function isAuth()
    {
        if ($this->is_auth == 1){
            $str = '申请中';
        }elseif ($this->is_auth == 2){
            $str = '已认证';
        }else{
            $str = '未认证';
        }
        return $str;
    }

    public function member()
    {
        return $this->hasOne('Member','member_id','user_type');
    }

    // user_type
    public function userType()
    {
        if ($this->is_true == 0){
            $str = "<font color='red'>真实账户</font>";
        }elseif ($this->is_true == 1){
            $str = '<font color="navy">首页置顶</font>';
        }else{
            $str = '<font color="#ff8c00">分类置顶</font>';
        }

        return $str;
    }

    public function isBond()
    {
        if ($this->is_bond == 1){
            $str = '未付费';
        }else{
            $str = '已付费';
        }
        return $str;
    }

    public function isSms()
    {
        if ($this->is_sms){
            $str = '未验证';
        }else{
            $str = '已验证';
        }
        return $str;
    }

    public function isPid()
    {
        if ($this->pid == 0){
            $str = '没有父级id';
        }else{
            $str = '父级id为' . $this->pid;
        }
        return $str;
    }

    public function isUnUserId()
    {
        if ($this->unuser_id == 0){
            $str = '非虚拟用户';
        }else{
            $str = '虚拟用户对应的id为 ' . $this->unuser_id;
        }
        return $str;
    }



}
