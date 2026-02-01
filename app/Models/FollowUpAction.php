<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUpAction extends Model
{
    protected $table = 'follow_up_actions'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'enrollment_id',
        'action_type',
        'notes',
        'performed_by',
        'performed_at',
        'created_at',
        'updated_at',
    ];
    // Relaciones con otros modelos
    public function enrollment()  { 
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }   
}