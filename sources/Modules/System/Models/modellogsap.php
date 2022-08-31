<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modellogsap extends Model{
    protected $table = 'logsap';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'tanggal',
    ];

}