<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Cadastra a tabela Sinditransportes para Goiânia-GO:
     * - Saúde: Enfermaria R$ 183,92 em todas as 10 faixas etárias; Apartamento R$ 0,00
     * - Odonto: R$ 0,00
     */
    public function up(): void
    {
        $now = now();

        $cidadeId = DB::table('cidades')->where('nome', 'Goiânia')->where('uf', 'GO')->value('id');
        if (!$cidadeId) {
            $cidadeId = DB::table('cidades')->insertGetId([
                'nome' => 'Goiânia', 'uf' => 'GO', 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // ── Saúde ──
        $saudeId = DB::table('planos')->where('nome', 'Sinditransportes - Saúde')->value('id');
        if (!$saudeId) {
            $saudeId = DB::table('planos')->insertGetId([
                'nome' => 'Sinditransportes - Saúde', 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        for ($faixa = 0; $faixa <= 9; $faixa++) {
            DB::table('faixas_etarias_valores')->updateOrInsert(
                ['plano_id' => $saudeId, 'cidade_id' => $cidadeId, 'faixa' => $faixa],
                ['apartamento' => 0.00, 'enfermaria' => 183.92, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        // ── Odonto ──
        $odontoId = DB::table('planos')->where('nome', 'Sinditransportes - Odonto')->value('id');
        if (!$odontoId) {
            $odontoId = DB::table('planos')->insertGetId([
                'nome' => 'Sinditransportes - Odonto', 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        DB::table('odonto_valores')->updateOrInsert(
            ['plano_id' => $odontoId, 'cidade_id' => $cidadeId],
            ['valor' => 0.00, 'updated_at' => $now, 'created_at' => $now]
        );
    }

    public function down(): void
    {
        $cidadeId = DB::table('cidades')->where('nome', 'Goiânia')->where('uf', 'GO')->value('id');
        $saudeId  = DB::table('planos')->where('nome', 'Sinditransportes - Saúde')->value('id');
        $odontoId = DB::table('planos')->where('nome', 'Sinditransportes - Odonto')->value('id');

        if ($saudeId && $cidadeId) {
            DB::table('faixas_etarias_valores')->where('plano_id', $saudeId)->where('cidade_id', $cidadeId)->delete();
        }
        if ($odontoId && $cidadeId) {
            DB::table('odonto_valores')->where('plano_id', $odontoId)->where('cidade_id', $cidadeId)->delete();
        }
    }
};
