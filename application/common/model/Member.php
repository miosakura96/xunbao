<?php

namespace app\common\model;

use think\Model;

class Member extends Model
{
    protected $table = 'member';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';


}
