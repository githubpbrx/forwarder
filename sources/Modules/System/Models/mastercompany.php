<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class mastercompany extends Model{
    protected $table = 'mastercompany';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama',
    ];
}