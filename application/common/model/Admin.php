<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    protected $table = 'admin';

    protected $autoWriteTimestamp = true;

    protected $updateTime = 'updated_at';

    protected $createTime = 'created_at';

    public function role()
    {
        return $this->hasOne('Role','role_id','role_id');
    }

}
