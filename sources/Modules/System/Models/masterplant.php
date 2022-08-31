<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class masterplant extends Model{
    protected $table = 'masterplant';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama',
    ];
}