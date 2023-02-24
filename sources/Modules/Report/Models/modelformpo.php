<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class modelformpo extends Model
{
    protected $table = 'formpo';
    protected $primaryKey = 'id_formpo';
    protected $guarded = [];

    public function route()
    {
        return $this->hasOne('Modules\Report\Models\masterroute', 'id_route', 'idroute')->where('aktif', 'Y');
    }

    public function loading()
    {
        return $this->hasOne('Modules\Report\Models\masterportofloading', 'id_portloading', 'idportloading')->where('aktif', 'Y');
    }

    public function destination()
    {
        return $this->hasOne('Modules\Report\Models\masterportofdestination', 'id_portdestination', 'idportdestination')->where('aktif', 'Y');
    }
}
