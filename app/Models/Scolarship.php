<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scolarship extends Model
{
    protected $tables = 'scolarships'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'enrollment_id',
        'discount_id',
        'approved_by',
        'approved_at',
        'notes',
        'created_at',
        'updated_at',
    ];
    // Relaciones con otros modelos
    public function enrollment()  { 
        return $this->hasOne(Enrollment::class, 'id', 'enrollment_id');
    }
    public function discount()  { 
        return $this->hasOne(Discount::class, 'id', 'discount_id');
    }
}
