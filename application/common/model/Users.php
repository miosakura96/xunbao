<?php

namespace app\common\model;

use think\Model;

class Users extends Model
{
    protected $table = 'users';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';


    public function userLevel()
    {
        if ($this->user_type == 10){
            $str = '无';
        }elseif ($this->user_type == 7){
            $str = '<font color="#8b0000">至尊服务</font>';
        }elseif ($this->user_type == 6){
            $str = '<font color="#006400">钻石会员</font>';
        }elseif ($this->user_type == 5){
            $str = '<font color="#ffa07a">金牌会员</font>';
        }elseif ($this->user_type == 4){
            $str = '<font color="green">银牌会员</font>';
        }elseif ($this->user_type == 3){
            $str = '<font color="#6b8e23">铜牌用户</font>';
        }elseif ($this->user_type == 2){
            $str = '<font color="aqua">实名用户</font>';
        }elseif ($this->user_type == 1){
            $str = '<font color="#ccc">游客</font>';
        }

        return $str;
    }

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
