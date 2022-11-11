<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;

class modelprivilege extends Model
{
    protected $table = 'privilege';
    protected $primaryKey = 'privilege_id';
    protected $fillable = [
        'privilege_user_nik',
        'privilege_user_location',
        'privilege_user_name',
        'privilege_group_access_id',
    ];

    public function group_access()
    {
        return $this->belongsTo('Modules\System\Models\Privileges\modelgroup_access', 'privilege_group_access_id');
    }
}
