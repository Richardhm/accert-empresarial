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
        Schema::create('planos', function (Blueprint $table) {
            $table->id(); // Cria a coluna `id` como bigint unsigned e auto-incremento
            $table->string('nome', 255); // Cria a coluna `nome` com até 255 caracteres
            $table->timestamps(); // Cria as colunas `created_at` e `updated_at` como timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};
