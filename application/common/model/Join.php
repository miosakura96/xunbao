<?php

namespace app\common\model;

use think\Model;

class Join extends Model
{
    protected $table = 'join';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    public function buyer()
    {
        return $this->hasOne('Users','user_id','uid');
    }

    public function seller()
    {
        return $this->hasOne('Users','user_id','sid');
    }

    public function order()
    {
        return $this->hasOne('Order','gid','gid');
    }

    public function goods()
    {
        return $this->hasOne('goods','goods_id','gid');
    }

}
