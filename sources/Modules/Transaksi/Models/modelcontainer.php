<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;

class modelcontainer extends Model
{
    protected $table = 'containershipment';
    protected $primaryKey = 'id_numbercontainer';
    protected $guarded = [];
}
