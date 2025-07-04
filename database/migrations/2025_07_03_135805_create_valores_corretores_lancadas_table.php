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
        Schema::create('valores_corretores_lancadas', function (Blueprint $table) {
            $table->id(); // Chave primária (bigint unsigned AUTO_INCREMENT)
            $table->unsignedBigInteger('user_id'); // Relacionado ao vendedor
            $table->date('data'); // Data no formato YYYY-MM-DD

            // Valores financeiros
            $table->decimal('valor_comissao', 10, 2)->nullable();
            $table->decimal('valor_salario', 10, 2)->nullable();
            $table->decimal('valor_premiacao', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->decimal('valor_desconto', 10, 2)->nullable();
            $table->decimal('valor_estorno', 10, 2)->nullable();

            $table->timestamps(); // created_at e updated_at

            // Índices e relacionamentos
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valores_corretores_lancadas');
    }
};
