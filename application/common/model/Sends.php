<?php

namespace app\common\model;

use think\Model;

class Sends extends Model
{
    protected $table = 'sends';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';
}
