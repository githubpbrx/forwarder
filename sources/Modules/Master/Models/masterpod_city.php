<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Master\Models\mastercountry;
use Modules\Master\Models\masterpol_city;

class masterpod_city extends Model
{
    protected $table = 'masterpodcity';
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
}
