<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;

class modelformshipment extends Model
{
    protected $table = 'formshipment';
    protected $primaryKey = 'id_shipment';
    protected $guarded = [];

    public function withformpo()
    {
        return $this->hasOne('Modules\Transaksi\Models\modelformpo', 'id_formpo', 'idformpo')->where('aktif', 'Y');
    }
}
