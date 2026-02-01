<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $tables = 'payments'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'enrollment_id',
        'amount',
        'payment_method',
        'payment_reference',
        'receipt_path',
        'payment_date',
        'validated_at',
        'validated_by',
        'created_at',
        'updated_at',
    ];
    // Relaciones con otros modelos
    public function enrollment()  { 
        return $this->hasOne(Enrollment::class, 'id', 'enrollment_id');
    }
}
