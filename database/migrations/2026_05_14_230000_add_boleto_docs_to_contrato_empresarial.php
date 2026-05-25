<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->string('boleto_saude_path', 500)->nullable()->after('primeiro_boleto_vencimento');
            $table->string('demonstrativo_saude_path', 500)->nullable()->after('boleto_saude_path');
            $table->string('boleto_odonto_path', 500)->nullable()->after('demonstrativo_saude_path');
            $table->string('demonstrativo_odonto_path', 500)->nullable()->after('boleto_odonto_path');
            $table->date('data_primeiro_boleto')->nullable()->after('demonstrativo_odonto_path');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn([
                'boleto_saude_path',
                'demonstrativo_saude_path',
                'boleto_odonto_path',
                'demonstrativo_odonto_path',
                'data_primeiro_boleto',
            ]);
        });
    }
};
