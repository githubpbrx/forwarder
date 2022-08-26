<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelsystem extends Model{
    protected $table = 'system';
    protected $primaryKey = 'system_id';
    protected $fillable = [
        'system_program_name',
        'system_copyright',
        'system_sidebar_title',
        'system_login_notify',
        'system_login_descryption',
        'system_default_language',
    ];

    public function menu(){
        return $this->hasMany('Modules\System\Models\Privileges\modelmenu', 'menu_system_id');
    }
}