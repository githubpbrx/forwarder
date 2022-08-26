<?php

namespace Modules\System\Models\Privileges;

use Illuminate\Database\Eloquent\Model;

class modelrole_access extends Model{
    protected $table = 'role_access';
    protected $primaryKey = 'role_access_id';
    protected $fillable = [
        'role_access',
        'role_access_menu_id',
        'role_access_group_access_id',
    ];

    public function menu(){
        return $this->belongsTo('Modules\System\Models\Privileges\modelmenu', 'role_access_menu_id');
    }

    public function group_access(){
        return $this->belongsTo('Modules\System\Models\Privileges\modelgroup_access', 'role_access_group_access_id');
    }
}