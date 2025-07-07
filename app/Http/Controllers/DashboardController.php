<?php

namespace App\Http\Controllers;

use App\Models\ContratoEmpresarial;
use App\Models\Plano;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        // Dados dos cards: Busca todos os planos e calcula as métricas
        $dadosPlanos = Plano::with(['contratos' => function ($q) {
            $q->selectRaw('plano_id, SUM(quantidade_vidas) as total_vidas')
                ->selectRaw('SUM(valor_plano) as total_valor')
                ->groupBy('plano_id');
        }])->get();



        // Top planos mais vendidos (agrupados por plano_id)
        $topPlanos = ContratoEmpresarial::select('plano_id')
            ->with('plano:id,nome')
            ->selectRaw('COUNT(*) as total_vendas, SUM(valor_pagar) as total_recebido')
            ->groupBy('plano_id')
            ->orderByDesc('total_vendas')
            ->take(5)
            ->get();







        // Classificação de usuários por maior número de vendas
        $usuariosRanking = ContratoEmpresarial::select('user_id')
            ->with('user:id,name,email')
            ->selectRaw('COUNT(*) as total_contratos')
            ->groupBy('user_id')
            ->orderByDesc('total_contratos')
            ->take(5)
            ->get();

        // Dados para tabela detalhada
        $tabelaDetalhada = ContratoEmpresarial::with(['plano:id,nome', 'user:id,name,email'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('dadosPlanos','topPlanos', 'usuariosRanking', 'tabelaDetalhada'));
    }
}
