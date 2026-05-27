<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faixas_etarias_valores', function (Blueprint $table) {
            $table->dropColumn(['com_copart_apart', 'com_copart_enfer', 'sem_copart_apart', 'sem_copart_enfer']);
            $table->decimal('apartamento', 10, 2)->nullable()->after('faixa');
            $table->decimal('enfermaria',  10, 2)->nullable()->after('apartamento');
        });

        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn('saude_coparticipacao');
        });
    }

    public function down(): void
    {
        Schema::table('faixas_etarias_valores', function (Blueprint $table) {
            $table->dropColumn(['apartamento', 'enfermaria']);
            $table->decimal('com_copart_apart', 10, 2)->nullable();
            $table->decimal('com_copart_enfer', 10, 2)->nullable();
            $table->decimal('sem_copart_apart', 10, 2)->nullable();
            $table->decimal('sem_copart_enfer', 10, 2)->nullable();
        });

        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->string('saude_coparticipacao', 5)->nullable();
        });
    }
};
