<?php

namespace app\common\model;

use think\Model;

class Collect extends Model
{
    protected $table = 'collect';

    protected $autoWriteTimestamp = false;

    public function good()
    {
        return $this->hasOne('Goods','goods_id','gid');
    }

}
