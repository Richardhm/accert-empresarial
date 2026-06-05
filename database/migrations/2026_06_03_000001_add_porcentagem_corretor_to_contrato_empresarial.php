<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->decimal('porcentagem_corretor', 5, 2)->nullable()->default(30)->after('valor_pagar');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn('porcentagem_corretor');
        });
    }
};
