<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->date('data_pgto')->nullable()->after('data_adesao');
            $table->string('forma_pagamento', 30)->nullable()->after('data_pgto');
            $table->string('oriundo', 50)->nullable()->default('Accert')->after('forma_pagamento');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn(['data_pgto', 'forma_pagamento', 'oriundo']);
        });
    }
};
