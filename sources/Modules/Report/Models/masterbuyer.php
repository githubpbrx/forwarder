<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class masterbuyer extends Model
{
    protected $table = 'masterbuyer';
    protected $primaryKey = 'id_buyer';
    protected $fillable = [
        'id_buyer',
        'nama_buyer',
    ];
}
