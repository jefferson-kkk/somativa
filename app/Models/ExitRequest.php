<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\User;

class ExitRequest extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function booted(): void
    {
        static::created(function ($exitRequest) {
            // Envia para todos que são professores
            $professores = User::where('role', 'professor')->get();

            if ($professores->count() > 0) {
                Notification::make()
                    ->title('NOVA SAÍDA DE ALUNO')
                    ->body("O aluno {$exitRequest->student?->nome} acabou de passar pela portaria.")
                    ->danger()
                    ->icon('heroicon-o-user-minus')
                    ->persistent()
                    ->sendToDatabase($professores);
            }
        });
    }
}