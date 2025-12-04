<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkCatalog extends Model
{
      protected $table = 'work_catalog';

    protected $fillable = [
        'code',
        'name',
        'category',
        'default_unit',
        'default_price',
        'price_type',
        'is_active',
    ];
}
