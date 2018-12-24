<?php

namespace app\common\model;

use think\Model;

class Role extends Model
{
    protected $table = 'role';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';
}
