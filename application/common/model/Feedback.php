<?php

namespace app\common\model;

use think\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    public function user()
    {
        return $this->hasOne('Users','user_id','uid');
    }
}
