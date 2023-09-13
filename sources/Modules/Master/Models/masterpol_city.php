<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Master\Models\mastercountry;

class masterpol_city extends Model
{
    protected $table = 'masterpolcity';
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
}
