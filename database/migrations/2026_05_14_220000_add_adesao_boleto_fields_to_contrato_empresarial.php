<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->string('boleto_adesao_path')->nullable()->after('data_adesao');
            $table->decimal('boleto_adesao_valor', 10, 2)->nullable()->after('boleto_adesao_path');
            $table->text('justificativa_diferenca')->nullable()->after('boleto_adesao_valor');
            $table->tinyInteger('tem_diferenca_valor')->default(0)->after('justificativa_diferenca');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn(['boleto_adesao_path', 'boleto_adesao_valor', 'justificativa_diferenca', 'tem_diferenca_valor']);
        });
    }
};
