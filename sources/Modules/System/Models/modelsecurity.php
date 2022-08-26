<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelsecurity extends Model{
    protected $table = 'security';
    protected $primaryKey = 'security_id';
    protected $fillable = [
        'security_id',
        'security_nik',
        'security_name',
        'security_location',
        'security_mapping',
    ];
}