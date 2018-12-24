<?php

namespace app\common\model;

use think\Model;

class News extends Model
{
    protected $table = 'news';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';
}
