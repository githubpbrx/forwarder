<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelforwarder extends Model
{
    protected $table = 'forwarder';
    protected $primaryKey = 'id_forwarder';
    protected $guarded = [];

    public function poku()
    {
        return $this->hasOne('Modules\System\Models\modelpo', 'id', 'idpo');
    }

    public function privilege()
    {
        return $this->hasOne('Modules\System\Models\modelprivilege', 'idforwarder', 'idmasterfwd')->where('privilege_aktif', 'Y');
    }
}
