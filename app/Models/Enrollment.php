<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'user_id',
        'training_group_id',
        'enrolled_at',
        'created_at',
        'updated_at',
    ];
    // Relaciones con otros modelos
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function trainingGroup() {
        return $this->hasOne(TrainingGroup::class, 'id', 'training_group_id');
    }

}
