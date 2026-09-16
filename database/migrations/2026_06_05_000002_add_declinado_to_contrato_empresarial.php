<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('contrato_empresarial', 'declinado')) {
            return;
        }
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->boolean('declinado')->default(0)->after('historico_cancelado');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn('declinado');
        });
    }
};
