<?php

namespace app\common\model;

use think\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';
}
