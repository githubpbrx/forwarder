<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelpo extends Model{
    protected $table = 'po';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama',
    ];
}