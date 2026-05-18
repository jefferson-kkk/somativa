<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SolicitacaoSaida extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'motivo',
        'status',
        'observacao',
        'horario_saida',
        'email_professor', // ADICIONE ISSO AQUI!
    ];

    protected static function booted()
    {
        static::created(function ($solicitacao) {
            // Envia para o professor indicado no formulário
            \Illuminate\Support\Facades\Mail::raw(
                "O aluno {$solicitacao->aluno->nome} (Turma: {$solicitacao->aluno->turma}) registrou uma saída antecipada. Motivo: {$solicitacao->motivo}",
                function ($message) use ($solicitacao) {
                    $message->to($solicitacao->email_professor)
                            ->subject("⚠️ Alerta SAFE: Saída de Aluno em Andamento");
                }
            );
        });
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}