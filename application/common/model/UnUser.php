<?php

namespace app\common\model;

use think\Model;

class UnUser extends Model
{
    protected $table = 'unuser';

    protected $autoWriteTimestamp = true;

    protected $updateTime = 'updated_at';

    protected $createTime = 'created_at';

    public function member()
    {
        return $this->hasOne('Member','member_id', 'unuser_type');
    }

    public function unUserType()
    {
        if ($this->unuser_type == 10){
            $str = '无';
        }elseif ($this->unuser_type == 7){
            $str = '<font color="#8b0000">至尊服务</font>';
        }elseif ($this->unuser_type == 6){
            $str = '<font color="#006400">钻石会员</font>';
        }elseif ($this->unuser_type == 5){
            $str = '<font color="#ffa07a">金牌会员</font>';
        }elseif ($this->unuser_type == 4){
            $str = '<font color="green">银牌会员</font>';
        }elseif ($this->unuser_type == 3){
            $str = '<font color="#6b8e23">铜牌用户</font>';
        }elseif ($this->unuser_type == 2){
            $str = '<font color="aqua">实名用户</font>';
        }elseif ($this->unuser_type == 1){
            $str = '<font color="#ccc">游客</font>';
        }

        return $str;
    }

}
