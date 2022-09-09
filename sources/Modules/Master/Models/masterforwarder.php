<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;

class masterforwarder extends Model
{
    protected $table = 'masterforwarder';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama',
    ];
}
