<?php

namespace Modules\System\Models\Privileges;

use Illuminate\Database\Eloquent\Model;

class modelmenu extends Model{
    protected $table = 'menu';
    protected $primaryKey = 'menu_id';
    protected $fillable = [
        'menu_name',
        'menu_system_id',
        'menu_is_active',
    ];

    public function system(){
        return $this->belongsTo('Modules\System\Models\modelsystem', 'menu_system_id');
    }

    public function role_access(){
        return $this->hasMany('Modules\System\Models\Privileges\modelrole_access', 'role_access_menu_id');
    }
}