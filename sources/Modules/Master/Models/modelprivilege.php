<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;

class modelprivilege extends Model
{
    protected $table = 'privilege';
    protected $primaryKey = 'privilege_id';
    protected $guarded = [];

    public function group_access()
    {
        return $this->belongsTo('Modules\System\Models\Privileges\modelgroup_access', 'privilege_group_access_id');
    }
}
