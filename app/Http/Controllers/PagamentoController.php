<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PagamentoController extends Controller
{
    public function index()
    {
        return view('pagamento.index');
    }

    // ─── Listagem principal (DataTable) ──────────────────────────────────────────
    public function listar(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        $resultado = DB::table('contrato_empresarial')
            ->select(
                DB::raw("DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at"),
                'contrato_empresarial.codigo_externo as codigo_externo',
                'users.name as usuario',
                'contrato_empresarial.razao_social',
                'contrato_empresarial.cnpj',
                'contrato_empresarial.quantidade_vidas',
                'contrato_empresarial.valor_plano',
                'contrato_empresarial.cidade as cidade',
                'contrato_empresarial.uf as uf',
                'planos.nome as plano',
                'contrato_empresarial.id as id',
                'contrato_empresarial.valor_plano_saude as valor_saude',
                'contrato_empresarial.valor_plano_odonto as valor_odonto',
                'contrato_empresarial.codigo_saude as codigo_saude',
                'contrato_empresarial.codigo_odonto as codigo_odonto',
                DB::raw("(
                    SELECT MAX(p.parcela)
                    FROM pagamentos p
                    WHERE p.contrato_empresarial_id = contrato_empresarial.id
                ) as ultima_parcela"),
                DB::raw("(
                    SELECT COALESCE(SUM(p.vl_a_pagar), 0)
                    FROM pagamentos p
                    WHERE p.contrato_empresarial_id = contrato_empresarial.id
                ) as total_comissoes"),
                DB::raw("(SELECT COALESCE(SUM(p.vl_a_pagar), 0) FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.tipo_planilha LIKE 'agenciamento_%') as total_agenciamento"),
                DB::raw("(SELECT COALESCE(SUM(p.vl_a_pagar), 0) FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.tipo_planilha LIKE 'recorrencia_%') as total_recorrencia"),
                DB::raw("(SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.tipo_planilha = 'agenciamento_saude') as tem_agenciamento_saude"),
                DB::raw("(SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.tipo_planilha = 'recorrencia_saude') as tem_recorrencia_saude"),
                DB::raw("(SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.tipo_planilha = 'agenciamento_odonto') as tem_agenciamento_odonto"),
                DB::raw("(SELECT CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.tipo_planilha = 'recorrencia_odonto') as tem_recorrencia_odonto"),
                DB::raw("(SELECT CASE WHEN COUNT(DISTINCT p.parcela) > 0 AND (MAX(p.parcela) - MIN(p.parcela) + 1) > COUNT(DISTINCT p.parcela) THEN 1 ELSE 0 END FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.tipo_planilha LIKE 'recorrencia_%' AND p.parcela IS NOT NULL) as tem_gap_recorrencia"),
                DB::raw("(SELECT GROUP_CONCAT(DISTINCT DATE_FORMAT(p.vencimento, '%Y-%m') ORDER BY p.vencimento SEPARATOR ',') FROM pagamentos p WHERE p.contrato_empresarial_id = contrato_empresarial.id AND p.vencimento IS NOT NULL) as meses_pagamento"),
                DB::raw("CASE
                    WHEN contrato_empresarial.plano_saude_id IS NOT NULL AND contrato_empresarial.plano_odonto_id IS NOT NULL THEN 'ambos'
                    WHEN contrato_empresarial.plano_saude_id IS NOT NULL THEN 'saude'
                    WHEN contrato_empresarial.plano_odonto_id IS NOT NULL THEN 'odonto'
                    ELSE NULL
                END as tipo_contrato")
            )
            ->join('users', 'users.id', '=', 'contrato_empresarial.user_id')
            ->join('planos', 'planos.id', '=', 'contrato_empresarial.plano_id')
            ->leftJoin('tabela_origens', 'tabela_origens.id', '=', 'contrato_empresarial.tabela_origens_id')
            ->where('contrato_empresarial.etapa_atual', '=', 8)
            ->orderBy('contrato_empresarial.created_at', 'desc')
            ->get();

        return response()->json(['data' => $resultado]);
    }

    // ─── Upload de planilha (Agenciamento / Recorrência) ─────────────────────────
    public function uploadPlanilha(Request $request)
    {
        if (!$request->ajax()) abort(403);

        $request->validate([
            'arquivo' => 'required|file|extensions:xlsx,xls,csv',
            'tipo'    => 'required|in:agenciamento_saude,recorrencia_saude,agenciamento_odonto,recorrencia_odonto',
        ]);

        $file = $request->file('arquivo');
        $tipo = $request->input('tipo');

        // ── Leitura da planilha ──────────────────────────────────────────────────
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'csv') {
            $reader = IOFactory::createReader('Csv');
            $reader->setDelimiter(';');
            $reader->setEnclosure('"');
        } else {
            $reader = IOFactory::createReaderForFile($file->getPathname());
        }

        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getPathname());
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        // ── Detectar linha de cabeçalho e início dos dados ───────────────────────
        $dataStart = 0;
        foreach ($rows as $i => $row) {
            $first = strtoupper(trim((string)($row[0] ?? '')));
            if (str_contains($first, 'COD') || str_contains($first, 'COMISSIONADO') || str_contains($first, 'EMPRESA')) {
                $dataStart = $i + 1;
                break;
            }
        }

        $inseridos   = 0;
        $naoVinculados = 0;
        $ignorados   = 0;

        foreach (array_slice($rows, $dataStart) as $row) {
            // Col index: 0=COD.COMISSIONADO 1=NOME 2=CD_ORIGEM 3=EMPRESA_CONVENIADA
            //            4=VENCIMENTO 5=PARCELA 6=VL_BASE 7=PCT_IMP 8=VL_LIQ 9=PC_DIST 10=VL_PAGAR
            $empresaRaw = trim((string)($row[3] ?? ''));

            // Linha vazia — pular
            if ($empresaRaw === '' && empty(array_filter($row))) {
                $ignorados++;
                continue;
            }

            // ── Extração do código e razão social ────────────────────────────────
            $codigoIdentificado  = null;
            $razaoSocialPlanilha = null;

            if (str_contains($empresaRaw, ' - ')) {
                $partes = explode(' - ', $empresaRaw, 2);
                $codigoIdentificado  = strtoupper(trim($partes[0]));
                $razaoSocialPlanilha = trim($partes[1]);
            } else {
                $codigoIdentificado = strtoupper(trim($empresaRaw));
            }

            // ── Tentativa de vincular ao contrato ────────────────────────────────
            $contratoId = null;

            if ($codigoIdentificado) {
                $contrato = DB::table('contrato_empresarial')
                    ->where('codigo_saude', $codigoIdentificado)
                    ->select('id')
                    ->first();

                if (!$contrato && $razaoSocialPlanilha) {
                    $contrato = DB::table('contrato_empresarial')
                        ->where('razao_social', 'like', '%' . $razaoSocialPlanilha . '%')
                        ->select('id')
                        ->first();
                }

                $contratoId = $contrato->id ?? null;
            }

            if (!$contratoId) $naoVinculados++;

            // ── Conversão de data ────────────────────────────────────────────────
            $vencimento = null;
            $rawDate = trim((string)($row[4] ?? ''));
            if ($rawDate !== '') {
                // Tenta formatos comuns: d/m/Y, Y-m-d, número serial Excel
                if (is_numeric($rawDate)) {
                    try {
                        $vencimento = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$rawDate)
                            ->format('Y-m-d');
                    } catch (\Exception $e) {}
                } else {
                    foreach (['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y'] as $fmt) {
                        $dt = \DateTime::createFromFormat($fmt, $rawDate);
                        if ($dt) { $vencimento = $dt->format('Y-m-d'); break; }
                    }
                }
            }

            $toNum = fn($v) => $v !== null && $v !== '' ? (float)str_replace(['.', ','], ['', '.'], (string)$v) : null;

            Pagamento::create([
                'contrato_empresarial_id' => $contratoId,
                'tipo_planilha'           => $tipo,
                'empresa_conveniada'      => $empresaRaw,
                'codigo_identificado'     => $codigoIdentificado,
                'razao_social_planilha'   => $razaoSocialPlanilha,
                'vencimento'              => $vencimento,
                'parcela'                 => $toNum($row[5]),
                'vl_base_com'             => $toNum($row[6]),
                'pct_imposto'             => $toNum($row[7]),
                'vl_liquido'              => $toNum($row[8]),
                'pc_dist'                 => $toNum($row[9]),
                'vl_a_pagar'              => $toNum($row[10]),
                'arquivo_original'        => $file->getClientOriginalName(),
            ]);

            $inseridos++;
        }

        return response()->json([
            'success'        => true,
            'inseridos'      => $inseridos,
            'nao_vinculados' => $naoVinculados,
            'ignorados'      => $ignorados,
            'mensagem'       => "{$inseridos} registros importados. {$naoVinculados} não vinculados a contratos.",
        ]);
    }

    // ─── Registros não vinculados ────────────────────────────────────────────────
    public function naoVinculados()
    {
        $registros = DB::table('pagamentos')
            ->whereNull('contrato_empresarial_id')
            ->orderBy('created_at', 'desc')
            ->select('id','tipo_planilha','empresa_conveniada','codigo_identificado',
                     'vencimento','parcela','vl_a_pagar','arquivo_original','created_at')
            ->get();

        return response()->json([
            'count'     => $registros->count(),
            'registros' => $registros,
        ]);
    }

    // ─── Buscar contratos para vincular ─────────────────────────────────────────
    public function buscarContratos(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 2) return response()->json([]);

        $contratos = DB::table('contrato_empresarial')
            ->join('planos', 'planos.id', '=', 'contrato_empresarial.plano_id')
            ->where(function ($query) use ($q) {
                $query->where('contrato_empresarial.razao_social', 'like', '%'.$q.'%')
                      ->orWhere('contrato_empresarial.codigo_saude',  'like', '%'.$q.'%')
                      ->orWhere('contrato_empresarial.codigo_odonto', 'like', '%'.$q.'%')
                      ->orWhere('contrato_empresarial.cnpj',          'like', '%'.$q.'%');
            })
            ->select(
                'contrato_empresarial.id',
                'contrato_empresarial.razao_social',
                'contrato_empresarial.cnpj',
                'contrato_empresarial.codigo_saude',
                'contrato_empresarial.codigo_odonto',
                'contrato_empresarial.etapa_atual',
                'planos.nome as plano'
            )
            ->orderBy('contrato_empresarial.razao_social')
            ->limit(12)
            ->get();

        return response()->json($contratos);
    }

    // ─── Vincular registro a contrato ────────────────────────────────────────────
    public function vincular(Request $request, $id)
    {
        $contratoId = $request->input('contrato_id');

        $existe = DB::table('contrato_empresarial')->where('id', $contratoId)->exists();
        if (!$existe) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $atualizado = DB::table('pagamentos')
            ->whereNull('contrato_empresarial_id')
            ->where('id', $id)
            ->update(['contrato_empresarial_id' => $contratoId]);

        if (!$atualizado) {
            return response()->json(['error' => 'Registro não encontrado ou já vinculado.'], 404);
        }

        $restam = DB::table('pagamentos')->whereNull('contrato_empresarial_id')->count();

        return response()->json(['success' => true, 'restam' => $restam]);
    }

    // ─── Detalhe: todos os pagamentos de um contrato ─────────────────────────────
    public function detalheContrato(Request $request, $id)
    {
        if (!$request->ajax()) abort(403);

        $pagamentos = DB::table('pagamentos')
            ->where('contrato_empresarial_id', $id)
            ->orderBy('parcela')
            ->orderBy('vencimento')
            ->select(
                'id', 'tipo_planilha', 'empresa_conveniada',
                'vencimento', 'parcela',
                'vl_base_com', 'pct_imposto', 'vl_liquido', 'pc_dist', 'vl_a_pagar',
                'arquivo_original', 'created_at'
            )
            ->get();

        $contrato = DB::table('contrato_empresarial')
            ->where('id', $id)
            ->select('razao_social', 'cnpj', 'codigo_saude', 'codigo_odonto')
            ->first();

        return response()->json([
            'contrato'   => $contrato,
            'pagamentos' => $pagamentos,
        ]);
    }
}
