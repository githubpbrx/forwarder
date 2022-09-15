<?php

namespace Modules\Report\Models;

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

    public function to_masterfwd()
    {
        return $this->hasOne('Modules\Report\Models\masterforwarder', 'id', 'idforwarder')->where('aktif', 'Y');
    }

    public function to_kyc()
    {
        return $this->hasOne('Modules\System\Models\modelkyc', 'idmasterfwd', 'idforwarder')->where('aktif', 'Y');
    }

    public function to_formpo()
    {
        return $this->hasOne('Modules\Report\Models\modelformpo', 'idmasterfwd', 'idforwarder')->where('aktif', 'Y');
    }
}
