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
        Schema::create('folha_pagamento', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->unsignedBigInteger('folha_mes_id'); // ID da folha do mês
            $table->unsignedBigInteger('valores_corretores_lancados_id'); // ID dos valores consolidados
            $table->timestamps(); // created_at e updated_at

            // Índices e relacionamentos
            $table->foreign('folha_mes_id')->references('id')->on('folha_mes')->cascadeOnDelete();
            $table->foreign('valores_corretores_lancados_id')->references('id')->on('valores_corretores_lancadas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folha_pagamento');
    }
};
