<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table =  'certificates'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'enrollment_id',
        'certificate_code',
        'certificate_path',
        'deliverated_at',
        'created_at',
        'updated_at',
    ];
    // Relaciones con otros modelos
    public function enrollment()  { 
        return $this->hasOne(Enrollment::class, 'id', 'enrollment_id');
    }

}
