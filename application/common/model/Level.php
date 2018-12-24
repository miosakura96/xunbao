<?php

namespace app\common\model;

use think\Model;

class Level extends Model
{
    protected $table = 'level';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    public function user()
    {
        return $this->hasOne('Users','user_id','uid');
    }

}
