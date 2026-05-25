<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->decimal('primeiro_boleto_valor', 10, 2)->nullable()->after('carteirinha_paths');
            $table->date('primeiro_boleto_vencimento')->nullable()->after('primeiro_boleto_valor');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn(['primeiro_boleto_valor', 'primeiro_boleto_vencimento']);
        });
    }
};
