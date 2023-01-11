<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class modelforwarder extends Model
{
    protected $table = 'forwarder';
    protected $primaryKey = 'id_forwarder';
    protected $fillable = [
        'id_forwarder',
        'idpo',
        'idmasterfwd',
        'qty_allocation',
        'date_fwd',
    ];

    public function masterfwd()
    {
        return $this->hasOne('Modules\Report\Models\masterforwarder', 'id', 'idmasterfwd')->where('aktif', 'Y');
    }

    public function formpo()
    {
        return $this->hasOne('Modules\Report\Models\modelformpo', 'idforwarder', 'id_forwarder')->where('aktif', 'Y');
    }

    public function po()
    {
        return $this->hasOne('Modules\Report\Models\modelpo', 'id', 'idpo');
    }
}
