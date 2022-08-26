<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelsbu extends Model{
    protected $table = 'sbu';
    protected $primaryKey = 'sbu_id';
    protected $fillable = [
        'sbu_code',
        'sbu_location',
        'sbu_factory_code',
    ];

    public function factory(){
        return $this->hasOne('Modules\System\Models\modelfactory', 'factory_code', 'sbu_factory_code');
    }
}