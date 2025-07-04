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
        Schema::create('tabela_origens', function (Blueprint $table) {
            $table->id(); // Cria a coluna `id` como bigint unsigned e auto-incremento
            $table->string('nome', 255); // Cria a coluna `nome` com até 255 caracteres
            $table->string('uf', 2); // Cria a coluna `uf` com até 2 caracteres
            $table->string('descricao', 255)->nullable(); // Cria a coluna `descricao` com possibilidade de ser NULL
            $table->timestamps(); // Cria as colunas `created_at` e `updated_at` como timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabela_origens');
    }
};
