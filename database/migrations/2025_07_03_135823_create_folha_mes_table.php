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
        Schema::create('folha_mes', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('mes'); // Mês (formato: YYYY-MM)
            $table->string('status'); // Status da folha (ex.: aberta, fechada)

            $table->timestamps(); // created_at e updated_at


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folha_mes');
    }
};
