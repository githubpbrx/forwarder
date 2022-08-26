<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class modelfactory extends Model{
    protected $table = 'factory';
    protected $primaryKey = 'factory_id';
    protected $fillable = [
        'factory_code',
        'factory_name',
        'factory_company_name',
        'factory_company_address',
        'factory_logo',
    ];
}