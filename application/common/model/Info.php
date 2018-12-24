<?php

namespace app\common\model;

use think\Model;

class Info extends Model
{
    protected $table = 'info';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

}
