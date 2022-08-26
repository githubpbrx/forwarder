<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modeltlog extends Model{
    protected $connection = 'mysql_2';

    public $timestamps = false;
    protected $table = 'tlog';
    protected $fillable = [
        'userid',
        'activity',
        'date',
        'time',
        'microtime',
        'ipcom',
    ];
}