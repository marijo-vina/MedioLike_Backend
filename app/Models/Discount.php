<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $tables = "discounts"; // Nombre de la tabla en la base de datos
    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'code',
        'discount_type',
        'discount_value',
        'description',
        'is_active', 
        'created_at',
        'updated_at',
    ];
}
