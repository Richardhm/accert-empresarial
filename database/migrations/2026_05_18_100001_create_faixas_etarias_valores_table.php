<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faixas_etarias_valores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plano_id');
            $table->unsignedBigInteger('cidade_id');
            $table->tinyInteger('faixa')->unsigned(); // 0=0-18 … 9=59+
            $table->decimal('com_copart_apart', 10, 2)->nullable();
            $table->decimal('com_copart_enfer', 10, 2)->nullable();
            $table->decimal('sem_copart_apart', 10, 2)->nullable();
            $table->decimal('sem_copart_enfer', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['plano_id', 'cidade_id', 'faixa']);
            $table->foreign('plano_id')->references('id')->on('planos')->onDelete('cascade');
            $table->foreign('cidade_id')->references('id')->on('cidades')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faixas_etarias_valores');
    }
};
