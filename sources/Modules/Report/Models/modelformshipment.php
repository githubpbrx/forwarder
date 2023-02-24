<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class modelformshipment extends Model
{
    protected $table = 'formshipment';
    protected $primaryKey = 'id_shipment';
    protected $guarded = [];

    public function container()
    {
        return $this->hasOne('Modules\Report\Models\modelcontainership', 'noinv', 'noinv')->where('aktif', 'Y');
    }
}
