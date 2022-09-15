<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class mastersupplier extends Model
{
    protected $table = 'mastersupplier';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama',
    ];
}
