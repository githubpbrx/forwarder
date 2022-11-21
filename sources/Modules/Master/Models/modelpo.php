<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;

class modelpo extends Model
{
    protected $table = 'po';
    protected $primaryKey = 'id';
    protected $guard = [];

    public function hscodeku()
    {
        return $this->hasOne('Modules\Master\Models\masterhscode', 'matcontent', 'matcontents')->where('aktif', 'Y');
    }
}
