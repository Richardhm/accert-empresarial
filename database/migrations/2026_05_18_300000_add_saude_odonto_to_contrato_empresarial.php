<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->unsignedBigInteger('plano_saude_id')->nullable()->after('plano_id');
            $table->string('saude_uf', 2)->nullable()->after('plano_saude_id');
            $table->string('saude_cidade')->nullable()->after('saude_uf');
            $table->unsignedBigInteger('saude_user_id')->nullable()->after('saude_cidade');
            $table->string('saude_acomodacao', 20)->nullable()->after('saude_user_id');   // apartamento | enfermaria
            $table->string('saude_coparticipacao', 5)->nullable()->after('saude_acomodacao'); // com | sem

            $table->unsignedBigInteger('plano_odonto_id')->nullable()->after('saude_coparticipacao');
            $table->string('odonto_uf', 2)->nullable()->after('plano_odonto_id');
            $table->string('odonto_cidade')->nullable()->after('odonto_uf');
            $table->unsignedBigInteger('odonto_user_id')->nullable()->after('odonto_cidade');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropColumn([
                'plano_saude_id', 'saude_uf', 'saude_cidade', 'saude_user_id',
                'saude_acomodacao', 'saude_coparticipacao',
                'plano_odonto_id', 'odonto_uf', 'odonto_cidade', 'odonto_user_id',
            ]);
        });
    }
};
