<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class RegistroFluxoNotification extends Notification
{
    use Queueable;

    public function __construct(public $aluno, public $tipo) {}

    public function via($notifiable): array
    {
        // Dispara para o E-mail e executa a simulação do WhatsApp no Log
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // Simulação do WhatsApp no Log no momento do disparo do e-mail
        Log::info("🟢 [SAFE - SIMULAÇÃO WHATSAPP]");
        Log::info("Para: " . ($notifiable->whatsapp ?? 'Responsável'));
        Log::info("Mensagem: Olá, o aluno {$this->aluno} registrou {$this->tipo} às " . now()->format('H:i'));

        return (new MailMessage)
            ->subject("SAFE - Registro de {$this->tipo}")
            ->line("O aluno {$this->aluno} realizou a {$this->tipo} na instituição.")
            ->line("Horário: " . now()->toDateTimeString())
            ->action('Ver no Sistema', url('/'))
            ->line('Obrigado por usar o SAFE!');
    }
}