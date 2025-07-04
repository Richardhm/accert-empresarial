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
        Schema::create('contrato_empresarial', function (Blueprint $table) {
            $table->id(); // Cria a coluna `id` como bigint unsigned e auto-incremento

            // Chaves estrangeiras
            $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade'); // FK para `planos`
            $table->foreignId('tabela_origens_id')->constrained('tabela_origens')->onDelete('cascade'); // FK para `tabela_origens`
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK para `users`

            // Colunas adicionais
            $table->string('codigo_vendedor', 255); // Coluna `codigo_vendedor`
            $table->string('cnpj', 255); // Coluna `cnpj`
            $table->string('razao_social', 255); // Coluna `razao_social`
            $table->integer('quantidade_vidas'); // Coluna `quantidade_vidas`

            $table->decimal('valor_plano', 10, 2); // Coluna `valor_plano`
            $table->date('vencimento_boleto'); // Coluna `vencimento_boleto`
            $table->string('senha_cliente', 255)->nullable(); // Coluna `senha_cliente` (opcional)

            $table->decimal('valor_plano_odonto', 10, 2); // Coluna `valor_plano_odonto`
            $table->decimal('valor_plano_saude', 10, 2); // Coluna `valor_plano_saude`
            $table->string('codigo_saude', 150)->nullable(); // Coluna `codigo_saude` (opcional)
            $table->string('codigo_odonto', 150)->nullable(); // Coluna `codigo_odonto` (opcional)
            $table->string('codigo_externo', 50); // Coluna `codigo_externo`
            $table->date('data_boleto'); // Coluna `data_boleto`

            $table->string('responsavel', 255); // Coluna `responsavel`
            $table->string('telefone', 255)->nullable(); // Coluna `telefone` (opcional)
            $table->string('celular', 255); // Coluna `celular`
            $table->string('email', 255); // Coluna `email`
            $table->string('cidade', 255); // Coluna `cidade`
            $table->string('uf', 255); // Coluna `uf`

            $table->integer('plano_contrado'); // Coluna `plano_contrado`
            $table->boolean('pago')->default(0); // Coluna `plano` com valor default 0

            // Timestamp padrão
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato_empresarial');
    }
};
