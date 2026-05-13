<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cards: por plano — total de vidas e total vendido
        $cardsPlanos = DB::select("
            SELECT
                p.id,
                p.nome,
                COALESCE(SUM(ce.quantidade_vidas), 0) AS total_vidas,
                COALESCE(SUM(ce.valor_plano), 0)       AS total_valor,
                COUNT(ce.id)                           AS total_contratos
            FROM planos p
            LEFT JOIN contrato_empresarial ce ON ce.plano_id = p.id
            GROUP BY p.id, p.nome
            ORDER BY total_contratos DESC
        ");

        // Tabela usuários: total contratos, vidas e comissão paga
        $rankingUsuarios = DB::select("
            SELECT
                ce.user_id,
                u.name                                                                  AS nome,
                COUNT(ce.id)                                                            AS total_contratos,
                COALESCE(SUM(ce.quantidade_vidas), 0)                                  AS total_vidas,
                COALESCE(SUM(CASE WHEN ce.pago = 1 THEN ce.valor_pagar ELSE 0 END), 0) AS total_comissao_paga
            FROM contrato_empresarial ce
            INNER JOIN users u ON u.id = ce.user_id
            GROUP BY ce.user_id, u.name
            ORDER BY total_contratos DESC
        ");

        return view('dashboard', compact('cardsPlanos', 'rankingUsuarios'));
    }

    public function empresasPorVendedor(Request $request)
    {
        $userId = intval($request->user_id);

        $empresas = DB::select("
            SELECT
                ce.razao_social,
                ce.cidade,
                ce.cnpj,
                p.nome                                       AS plano_nome,
                ce.quantidade_vidas,
                ce.valor_plano,
                ce.valor_pagar                               AS comissao,
                ce.pago,
                DATE_FORMAT(ce.created_at, '%d/%m/%Y')       AS data_cadastro,
                DATE_FORMAT(ce.data_baixa_finalizado, '%d/%m/%Y') AS data_pagamento
            FROM contrato_empresarial ce
            INNER JOIN planos p ON p.id = ce.plano_id
            WHERE ce.user_id = {$userId}
            ORDER BY ce.created_at DESC
        ");

        return response()->json($empresas);
    }
}
