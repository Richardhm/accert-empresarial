<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\FaixaEtariaValor;
use App\Models\OdontoValor;
use App\Models\Plano;
use Illuminate\Http\Request;

class ConfiguracoesController extends Controller
{
    public function faixas()
    {
        $planos = Plano::orderBy('nome')->get();
        return view('configuracoes.faixas', compact('planos'));
    }

    public function cidadesPorUf(Request $request)
    {
        $uf = strtoupper(trim($request->input('uf', '')));
        $cidades = Cidade::where('uf', $uf)->orderBy('nome')->get(['id', 'nome']);
        return response()->json($cidades);
    }

    public function carregar(Request $request)
    {
        $planoId   = $request->input('plano_id');
        $uf        = strtoupper(trim($request->input('uf', '')));
        $cidadeNome = trim($request->input('cidade', ''));

        if (!$planoId || !$uf || !$cidadeNome) {
            return response()->json(['error' => 'Selecione plano, UF e cidade.'], 422);
        }

        $cidade = Cidade::where('nome', $cidadeNome)->where('uf', $uf)->first();
        if (!$cidade) {
            return response()->json(['valores' => [], 'cidade_id' => null]);
        }

        $valores = FaixaEtariaValor::where('plano_id', $planoId)
            ->where('cidade_id', $cidade->id)
            ->get()
            ->keyBy('faixa');

        return response()->json(['valores' => $valores, 'cidade_id' => $cidade->id]);
    }

    public function salvar(Request $request)
    {
        $planoId    = $request->input('plano_id');
        $uf         = strtoupper(trim($request->input('uf', '')));
        $cidadeNome = trim($request->input('cidade', ''));
        $faixas     = $request->input('faixas', []);

        if (!$planoId || !$uf || !$cidadeNome) {
            return response()->json(['error' => 'Selecione plano, UF e cidade.'], 422);
        }

        $plano = Plano::find($planoId);
        if (!$plano) {
            return response()->json(['error' => 'Plano não encontrado.'], 404);
        }

        $cidade = Cidade::firstOrCreate(['nome' => $cidadeNome, 'uf' => $uf]);

        foreach ($faixas as $index => $vals) {
            FaixaEtariaValor::updateOrCreate(
                ['plano_id' => $planoId, 'cidade_id' => $cidade->id, 'faixa' => (int) $index],
                [
                    'com_copart_apart' => $this->parseMoeda($vals['com_copart_apart'] ?? null),
                    'com_copart_enfer' => $this->parseMoeda($vals['com_copart_enfer'] ?? null),
                    'sem_copart_apart' => $this->parseMoeda($vals['sem_copart_apart'] ?? null),
                    'sem_copart_enfer' => $this->parseMoeda($vals['sem_copart_enfer'] ?? null),
                ]
            );
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Tabela de faixas salva com sucesso!',
            'cidade_id'  => $cidade->id,
        ]);
    }

    // ── Odonto ──────────────────────────────────────────────────────────────

    public function odonto()
    {
        $planos = Plano::orderBy('nome')->get();
        return view('configuracoes.odonto', compact('planos'));
    }

    public function odontoCarregar(Request $request)
    {
        $planoId    = $request->input('plano_id');
        $uf         = strtoupper(trim($request->input('uf', '')));
        $cidadeNome = trim($request->input('cidade', ''));

        if (!$planoId || !$uf || !$cidadeNome) {
            return response()->json(['error' => 'Selecione plano, UF e cidade.'], 422);
        }

        $cidade = Cidade::where('nome', $cidadeNome)->where('uf', $uf)->first();
        $registro = $cidade
            ? OdontoValor::where('plano_id', $planoId)->where('cidade_id', $cidade->id)->first()
            : null;

        return response()->json([
            'valor'     => $registro ? number_format($registro->valor, 2, ',', '.') : '',
            'cidade_id' => $cidade?->id,
            'existe'    => $registro !== null,
        ]);
    }

    public function odontoSalvar(Request $request)
    {
        $planoId    = $request->input('plano_id');
        $uf         = strtoupper(trim($request->input('uf', '')));
        $cidadeNome = trim($request->input('cidade', ''));
        $valor      = $this->parseMoeda($request->input('valor'));

        if (!$planoId || !$uf || !$cidadeNome) {
            return response()->json(['error' => 'Selecione plano, UF e cidade.'], 422);
        }

        $plano = Plano::find($planoId);
        if (!$plano) return response()->json(['error' => 'Plano não encontrado.'], 404);

        $cidade = Cidade::firstOrCreate(['nome' => $cidadeNome, 'uf' => $uf]);

        OdontoValor::updateOrCreate(
            ['plano_id' => $planoId, 'cidade_id' => $cidade->id],
            ['valor' => $valor]
        );

        return response()->json(['success' => true, 'message' => 'Valor odonto salvo com sucesso!']);
    }

    public function listarPlanos()
    {
        return response()->json(Plano::orderBy('nome')->get(['id', 'nome']));
    }

    public function salvarPlano(Request $request)
    {
        $nome = trim($request->input('nome', ''));
        if ($nome === '') {
            return response()->json(['error' => 'Nome é obrigatório.'], 422);
        }
        if (Plano::where('nome', $nome)->exists()) {
            return response()->json(['error' => 'Já existe um plano com esse nome.'], 422);
        }
        $plano = Plano::create(['nome' => $nome]);
        return response()->json(['success' => true, 'plano' => $plano]);
    }

    public function atualizarPlano(Request $request, $id)
    {
        $plano = Plano::find($id);
        if (!$plano) return response()->json(['error' => 'Plano não encontrado.'], 404);

        $nome = trim($request->input('nome', ''));
        if ($nome === '') {
            return response()->json(['error' => 'Nome é obrigatório.'], 422);
        }
        if (Plano::where('nome', $nome)->where('id', '!=', $id)->exists()) {
            return response()->json(['error' => 'Já existe um plano com esse nome.'], 422);
        }
        $plano->update(['nome' => $nome]);
        return response()->json(['success' => true, 'plano' => $plano]);
    }

    public function excluirPlano($id)
    {
        $plano = Plano::find($id);
        if (!$plano) return response()->json(['error' => 'Plano não encontrado.'], 404);

        $temUso = FaixaEtariaValor::where('plano_id', $id)->exists()
               || OdontoValor::where('plano_id', $id)->exists()
               || \App\Models\ContratoEmpresarial::where('plano_id', $id)->exists();

        if ($temUso) {
            return response()->json(['error' => 'Este plano possui contratos ou tabelas associadas e não pode ser excluído.'], 422);
        }

        $plano->delete();
        return response()->json(['success' => true]);
    }

    public function cadastro()
    {
        $planos = Plano::orderBy('nome')->get();

        $saudeCards = [];
        foreach ($planos as $plano) {
            $cidadeIds = FaixaEtariaValor::where('plano_id', $plano->id)
                ->distinct()->pluck('cidade_id');
            if ($cidadeIds->isEmpty()) continue;
            $cidades = Cidade::whereIn('id', $cidadeIds)->orderBy('nome')->get();
            $default = $cidades->firstWhere('nome', 'Goiânia') ?? $cidades->first();
            $saudeCards[] = ['plano' => $plano, 'cidades' => $cidades, 'default_cidade' => $default];
        }

        $odontoCards = [];
        foreach ($planos as $plano) {
            $cidadeIds = OdontoValor::where('plano_id', $plano->id)->pluck('cidade_id');
            if ($cidadeIds->isEmpty()) continue;
            $cidades = Cidade::whereIn('id', $cidadeIds)->orderBy('nome')->get();
            $default = $cidades->firstWhere('nome', 'Goiânia') ?? $cidades->first();
            $odontoCards[] = ['plano' => $plano, 'cidades' => $cidades, 'default_cidade' => $default];
        }

        return view('configuracoes.cadastro', compact('planos', 'saudeCards', 'odontoCards'));
    }

    private function parseMoeda($val): float
    {
        if ($val === null || trim((string) $val) === '') return 0.0;
        $val = str_replace('.', '', (string) $val);
        $val = str_replace(',', '.', $val);
        return max(0.0, (float) $val);
    }
}
