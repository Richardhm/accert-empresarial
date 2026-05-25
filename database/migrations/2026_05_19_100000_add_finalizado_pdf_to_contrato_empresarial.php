<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->string('finalizado_pdf_path')->nullable()->after('data_baixa_finalizado');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn('finalizado_pdf_path');
        });
    }
};
