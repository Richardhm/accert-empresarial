<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Diferenciar saude vs odonto por beneficiário
        Schema::table('planilha_beneficiarios', function (Blueprint $table) {
            $table->string('tipo_plano', 10)->nullable()->after('contrato_empresarial_id');
        });

        // Caminho da planilha odonto (separado da saúde)
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->string('planilha_odonto_path')->nullable()->after('planilha_path');
        });
    }

    public function down(): void
    {
        Schema::table('planilha_beneficiarios', function (Blueprint $table) {
            $table->dropColumn('tipo_plano');
        });
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn('planilha_odonto_path');
        });
    }
};
