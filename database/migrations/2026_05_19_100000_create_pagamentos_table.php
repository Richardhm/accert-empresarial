<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrato_empresarial_id')->nullable()->index();
            $table->string('tipo_planilha'); // agenciamento_saude | recorrencia_saude | agenciamento_odonto | recorrencia_odonto
            $table->string('empresa_conveniada');          // texto bruto da coluna 4
            $table->string('codigo_identificado')->nullable();    // código extraído (antes do " - ")
            $table->string('razao_social_planilha')->nullable();  // nome extraído (após o " - ")
            $table->date('vencimento')->nullable();
            $table->integer('parcela')->nullable();
            $table->decimal('vl_base_com', 14, 2)->nullable();
            $table->decimal('pct_imposto', 8, 4)->nullable();
            $table->decimal('vl_liquido', 14, 2)->nullable();
            $table->decimal('pc_dist', 8, 4)->nullable();
            $table->decimal('vl_a_pagar', 14, 2)->nullable();
            $table->string('arquivo_original')->nullable();
            $table->timestamps();

            $table->foreign('contrato_empresarial_id')
                  ->references('id')
                  ->on('contrato_empresarial')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
