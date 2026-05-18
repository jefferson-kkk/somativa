<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitacao_saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->string('motivo');
            $table->string('status')->default('Pendente'); // Pendente, Aprovado, Recusado
            $table->text('observacao')->nullable();
            $table->timestamp('horario_saida')->nullable();
            $table->timestamps();
            $table->string('email_professor'); // E-mail institucional para notificação prévia
            $table->string('codigo_identificacao')->nullable(); // QR Code ou PIN para o porteiro conferir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacao_saidas');
    }
};
