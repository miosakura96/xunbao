<?php

namespace app\common\model;

use think\Model;

class Flower extends Model
{
    protected $table = 'flow';

    protected $autoWriteTimestamp = false;

    public function fans()
    {
        return $this->hasOne('Users','user_id','eid');
    }

    public function follow()
    {
        return $this->hasOne('Users','user_id','uid');
    }
}
