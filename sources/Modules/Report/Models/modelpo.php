<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class modelpo extends Model
{
    protected $table = 'po';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function mastersup()
    {
        return $this->hasOne('Modules\Report\Models\mastersupplier', 'id', 'vendor')->where('aktif', 'Y');
    }

    public function masterhscode()
    {
        return $this->hasOne('Modules\Report\Models\masterhscode', 'matcontent', 'matcontents')->where('aktif', 'Y');
    }

    public function postatus()
    {
        return $this->hasOne('Modules\Report\Models\modelpo', 'pono', 'pono');
    }
}
