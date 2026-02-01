<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'trainings'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'name',
        'description',
        'created_at',
        'updated_at',
    ];
}
