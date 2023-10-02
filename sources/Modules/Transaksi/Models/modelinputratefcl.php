<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Transaksi\Models\masterforwarder;
use Modules\Transaksi\Models\modelmappingratefcl;
use Illuminate\Database\Eloquent\Relations\HasOne;

class modelinputratefcl extends Model
{
    protected $table = 'inputratefcl';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * Get the country associated with the masterpol_city
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function maprate(): HasOne
    {
        return $this->hasOne(modelmappingratefcl::class, 'id', 'id_mappingrate')->where('aktif', 'Y');
    }

    public function masterfwd(): HasOne
    {
        return $this->hasOne(masterforwarder::class, 'id', 'id_forwarder')->where('aktif', 'Y');
    }
}
