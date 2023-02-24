<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class modelcontainership extends Model
{
    protected $table = 'containershipment';
    protected $primaryKey = 'id_numbercontainer';
    protected $guarded = [];
}
