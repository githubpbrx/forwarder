<?php

namespace Modules\Transaksi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Master\Models\mastercountry;
use Modules\Master\Models\masterpol_city;
use Modules\Master\Models\masterpod_city;
use Modules\Master\Models\mastershippingline;

class modelmappingratefcl extends Model
{
    protected $table = 'mappingratefcl';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * Get the country associated with the masterpol_city
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country(): HasOne
    {
        return $this->hasOne(mastercountry::class, 'id', 'id_country')->where('aktif', 'Y')->select('id', 'country');
    }

    public function polcity(): HasOne
    {
        return $this->hasOne(masterpol_city::class, 'id', 'id_polcity')->where('aktif', 'Y')->select('id', 'id_country', 'city');
    }

    public function podcity(): HasOne
    {
        return $this->hasOne(masterpod_city::class, 'id', 'id_podcity')->where('aktif', 'Y')->select('id', 'id_country', 'id_polcity', 'city');
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(mastershippingline::class, 'id', 'id_shippingline')->where('aktif', 'Y')->select('id', 'id_country', 'id_polcity', 'id_podcity', 'name');
    }
}
