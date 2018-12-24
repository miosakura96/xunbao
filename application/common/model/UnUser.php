<?php

namespace app\common\model;

use think\Model;

class UnUser extends Model
{
    protected $table = 'unuser';

    protected $autoWriteTimestamp = true;

    protected $updateTime = 'updated_at';

    protected $createTime = 'created_at';


}
