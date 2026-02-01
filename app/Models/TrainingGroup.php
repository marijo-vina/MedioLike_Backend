<?php
//existe una llave foranea a training_groups
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingGroup extends Model
{
    protected $table = 'training_groups'; // Nombre de la tabla en la base de datos

    protected $fillable = [ // nombres de los atributos menos el id porque es autoincrementable
        'training_id',
        'group_name',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];
    // Relaciones con otros modelos

    public function training()
    {
        return $this->hasOne(Training::class, 'id', 'training_id');
    }
}