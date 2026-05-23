<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExitRequest extends Model
{
    protected $fillable = ['student_id', 'motivo_saida', 'horario_saida', 'status'];

    // Toda saída pertence a um aluno
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
