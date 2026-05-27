<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->boolean('importado_historico')->default(false)->after('finalizado_pdf_path');
            $table->boolean('historico_cancelado')->default(false)->after('importado_historico');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn(['importado_historico', 'historico_cancelado']);
        });
    }
};
