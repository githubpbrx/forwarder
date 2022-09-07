<?php

namespace Modules\Transaksi\Models;

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

     public function masterforwarder()
    {
        return $this->hasOne('Modules\Transaksi\Models\masterforwarder', 'id', 'id_forwarder');
    }
}
