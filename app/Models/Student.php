<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nome', 'turma', 'termo', 'responsavel'];

    // Um aluno tem um professor responsável
    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    // Um aluno pode ter várias saídas
    public function exitRequests()
    {
        return $this->hasMany(ExitRequest::class);
    }
}
