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
}
