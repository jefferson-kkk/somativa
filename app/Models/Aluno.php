<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // Importante para as notificações funcionarem!

class Aluno extends Model
{
    use Notifiable;

    // Autoriza o preenchimento dos campos
protected $fillable = [
    'nome',
    'email_responsavel',
    'whatsapp_responsavel', // <--- Verifique se este nome está IGUAL ao da migration
    'turma',
];
}