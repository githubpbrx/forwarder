<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelformpo extends Model
{
    protected $table = 'formpo';
    protected $primaryKey = 'id_formpo';
    protected $guarded = [];

    public function po()
    {
        return $this->hasOne('Modules\System\Models\modelpo', 'id', 'idpo');
    }

    public function forwarder()
    {
        return $this->hasOne('Modules\System\Models\modelforwarder', 'id_forwarder', 'idforwarder')->where('aktif', 'Y');
    }

    public function shipment()
    {
        return $this->hasOne('Modules\System\Models\modelformshipment', 'idformpo', 'id_formpo')->where('aktif', 'Y');
    }

    public function privilege()
    {
        return $this->hasOne('Modules\System\Models\modelprivilege', 'idforwarder', 'idmasterfwd')->where('privilege_aktif', 'Y');
    }
}
