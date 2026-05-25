<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planilha_beneficiarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrato_empresarial_id');
            $table->string('tipo')->nullable();             // Titular / Dependente
            $table->string('nome_completo')->nullable();
            $table->string('nome_titular')->nullable();
            $table->string('cpf', 20)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->unsignedSmallInteger('idade')->nullable();
            $table->string('nome_mae')->nullable();
            $table->string('acomodacao')->nullable();      // SAÚDE/Acomodação
            $table->string('sexo', 20)->nullable();
            $table->string('grau_parentesco')->nullable();
            $table->date('data_casamento')->nullable();
            $table->string('telefone', 30)->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('contrato_empresarial_id')
                  ->references('id')
                  ->on('contrato_empresarial')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planilha_beneficiarios');
    }
};
