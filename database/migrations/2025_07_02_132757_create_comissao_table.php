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
        Schema::create('comissao', function (Blueprint $table) {
            $table->id(); // Cria a coluna `id` como bigint unsigned e auto-incremento
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK para `users` com exclusão em cascata
            $table->decimal('valor', 10, 2); // Coluna `valor` como decimal com precisão de 10 dígitos e 2 casas decimais
            $table->timestamps(); // Cria as colunas `created_at` e `updated_at` como timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comissao');
    }
};
