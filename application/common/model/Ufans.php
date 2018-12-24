<?php

namespace app\common\model;

use think\Model;

class Ufans extends Model
{
    protected $table = 'ufans';

    protected $autoWriteTimestamp = false;

    public function unUser()
    {
        return $this->hasOne('UnUser','unuser_id','nfans_eid');
    }
}
