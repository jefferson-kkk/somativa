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
        Schema::create('alunos', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('email_responsavel');
        $table->string('whatsapp_responsavel');
        $table->string('email_professor_padrao')->nullable(); // E-mail fixo do prof. daquele aluno
        $table->string('turma')->nullable(); // Certifica-te que esta linha existe!
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
