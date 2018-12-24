<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
    protected $table = 'goods';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    public function user()
    {
        return $this->hasOne('Users','user_id','goods_uid');
    }

    public function goodState()
    {

        if ($this->goods_state == 0){
            $str = "<font color='green'>上架中</font>";
        }elseif($this->goods_state == 1){
            $str = "<font color='red'>下架中</font>";
        }else{
            $str = "<font color='orange'>议价成功</font>";
        }
        return $str;
    }
}
