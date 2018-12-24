<?php

namespace app\common\model;

use think\Model;

class Ips extends Model
{
    protected $table = 'ips';

    protected $autoWriteTimestamp = true;

    protected $updateTime = 'updated_at';

    protected $createTime = 'created_at';
}
