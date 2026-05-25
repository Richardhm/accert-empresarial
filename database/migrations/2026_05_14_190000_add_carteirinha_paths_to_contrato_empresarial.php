<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->json('carteirinha_paths')->nullable()->after('data_vigencia');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn('carteirinha_paths');
        });
    }
};
