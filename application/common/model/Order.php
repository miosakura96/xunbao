<?php

namespace app\common\model;

use think\Model;

class Order extends Model
{
    protected $table = 'order';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    public function buyer()
    {
        return $this->hasOne('Users', 'user_id', 'uid');
    }

    public function seller()
    {
        return $this->hasOne('Users', 'user_id', 'sid');
    }
}
