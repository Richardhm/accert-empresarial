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
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->tinyInteger('etapa_atual')->default(0)->after('pago');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn('etapa_atual');
        });
    }
};
