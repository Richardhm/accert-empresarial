<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odonto_valores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plano_id');
            $table->unsignedBigInteger('cidade_id');
            $table->decimal('valor', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['plano_id', 'cidade_id']);
            $table->foreign('plano_id')->references('id')->on('planos')->onDelete('cascade');
            $table->foreign('cidade_id')->references('id')->on('cidades')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odonto_valores');
    }
};
