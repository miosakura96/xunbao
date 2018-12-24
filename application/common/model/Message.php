<?php

namespace app\common\model;

use think\Model;

class Message extends Model
{
    protected $table = 'message';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    public function isCheckType()
    {
        if ($this->check_type == 0) {
            $str = '用户';
        } else {
            $str = '系统';
        }

        return $str;
    }

}
