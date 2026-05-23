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
    Schema::create('exit_requests', function (Blueprint $table) {
        $table->id();
        // Relacionamento com o aluno
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->string('motivo_saida');
        $table->time('horario_saida');
        // Status da liberação
        $table->enum('status', ['pendente', 'concluido'])->default('pendente');
        $table->timestamps();
    });
}   
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_requests');
    }
};
