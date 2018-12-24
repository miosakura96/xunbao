<?php

namespace app\common\model;

use think\Model;

class Auth extends Model
{
    protected $table = 'auth';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';
}
