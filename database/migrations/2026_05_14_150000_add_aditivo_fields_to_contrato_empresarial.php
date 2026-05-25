<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->string('aditivo_path')->nullable()->after('planilha_path');
            $table->date('data_aditivo')->nullable()->after('aditivo_path');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn(['aditivo_path', 'data_aditivo']);
        });
    }
};
