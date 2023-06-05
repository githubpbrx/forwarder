<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelpo extends Model
{
    protected $table = 'po';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $guard = [];

    public function supplier()
    {
        return $this->hasOne('Modules\System\Models\mastersupplier', 'id', 'vendor')->where('aktif', 'Y');
    }

    public function hscode()
    {
        return $this->hasOne('Modules\System\Models\masterhscode', 'matcontent', 'matcontents')->where('aktif', 'Y');
    }
}
