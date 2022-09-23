<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modellogproses extends Model{
    protected $table = 'logproses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
    ];

}