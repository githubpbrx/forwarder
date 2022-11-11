<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;

class modelformpo extends Model
{
    protected $table = 'formpo';
    protected $primaryKey = 'id_formpo';
    protected $guarded = [];

    public function withpo()
    {
        return $this->hasOne('Modules\Transaksi\Models\modelpo', 'id', 'idpo');
    }

    public function withforwarder()
    {
        return $this->hasOne('Modules\Transaksi\Models\modelforwarder', 'id_forwarder', 'idforwarder')->where('aktif', 'Y');
    }

    public function shipment()
    {
        return $this->hasOne('Modules\Transaksi\Models\modelformshipment', 'idformpo', 'id_formpo')->where('aktif', 'Y');
    }
}
