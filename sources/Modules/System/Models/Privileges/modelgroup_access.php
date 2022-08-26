<?php

namespace Modules\System\Models\Privileges;

use Illuminate\Database\Eloquent\Model;

class modelgroup_access extends Model{
    protected $table = 'group_access';
    protected $primaryKey = 'group_access_id';
    protected $fillable = [
        'group_access_id',
        'group_access_name',
    ];

    public function role_access(){
        return $this->hasMany('Modules\System\Models\Privileges\modelrole_access', 'role_access_group_access_id');
    }

    public function privilege(){
        return $this->hasMany('Modules\System\Models\modelprivilege', 'privilege_group_access_id');
    }
}