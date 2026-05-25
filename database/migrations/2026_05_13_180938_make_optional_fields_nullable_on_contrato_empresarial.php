<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->integer('quantidade_vidas')->nullable()->change();
            $table->string('codigo_externo', 50)->nullable()->change();
            $table->string('uf', 255)->nullable()->change();
            $table->integer('plano_contrado')->nullable()->change();
            $table->string('celular', 255)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('cidade', 255)->nullable()->change();
            $table->string('responsavel', 255)->nullable()->change();
            $table->string('codigo_vendedor', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->integer('quantidade_vidas')->nullable(false)->change();
            $table->string('codigo_externo', 50)->nullable(false)->change();
            $table->string('uf', 255)->nullable(false)->change();
            $table->integer('plano_contrado')->nullable(false)->change();
            $table->string('celular', 255)->nullable(false)->change();
            $table->string('email', 255)->nullable(false)->change();
            $table->string('cidade', 255)->nullable(false)->change();
            $table->string('responsavel', 255)->nullable(false)->change();
            $table->string('codigo_vendedor', 255)->nullable(false)->change();
        });
    }
};
