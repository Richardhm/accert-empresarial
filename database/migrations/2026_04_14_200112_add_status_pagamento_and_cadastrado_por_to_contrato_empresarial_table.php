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
            $table->string('status_pagamento', 50)->nullable()->default(null)->after('pago');
            $table->unsignedBigInteger('cadastrado_por')->nullable()->after('status_pagamento');
            $table->foreign('cadastrado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropForeign(['cadastrado_por']);
            $table->dropColumn(['status_pagamento', 'cadastrado_por']);
        });
    }
};
