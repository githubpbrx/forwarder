<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;

class modelpo extends Model
{
    protected $table = 'po';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function supplier()
    {
        return $this->hasOne('Modules\Transaksi\Models\mastersupplier', 'id', 'vendor')->where('aktif', 'Y');
    }

    public function forwarder()
    {
        return $this->hasMany('Modules\Transaksi\Models\modelforwarder', 'idpo', 'id')->where('aktif', 'Y');
    }
}
