<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use App\Models\Cidade;
use App\Models\Comissao;
use App\Models\ContratoEmpresarial;
use App\Models\FaixaEtariaValor;
use App\Models\OdontoValor;
use App\Models\Plano;
use App\Models\TabelaOrigem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function index()
    {
        $users  = User::all();
        $planos = Plano::all();
        return view('financeiro.index', compact('users', 'planos'));
    }

    public function formCreateEmpresarial()
    {
        $users = User::all();
        $plano_empresarial = Plano::all();
        $tabela_origem = TabelaOrigem::all();
        return view('financeiro.cadastrar-empresa',[
            "users" => $users,
            "planos_empresarial" => $plano_empresarial,
            "origem_tabela" =>  $tabela_origem
        ]);
    }

    public function storeEmpresarialFinanceiro(Request $request)
    {
        $dados = $request->all();
        $dados['valor_plano_saude'] = str_replace([".", ","], ["", "."], $dados['valor_plano_saude']);
        $dados['valor_plano_odonto'] = str_replace([".", ","], ["", "."], $dados['valor_plano_odonto']);
        $dados['valor_plano'] = str_replace([".", ","], ["", "."], $dados['valor_plano']);
        $comissao_valor = Comissao::where("user_id",$request->user_id)->first()->valor;
        $dados['valor_pagar'] = $dados['valor_plano'] * ($comissao_valor / 100);

        // Garantir que as datas estejam no formato correto (YYYY-MM-DD)
        $dados['data_boleto'] = Carbon::parse($dados['data_boleto'])->format('Y-m-d'); // Usando Carbon para formatar datas
        $dados['vencimento_boleto'] = Carbon::parse($dados['vencimento_boleto'])->format('Y-m-d');
        $dados['codigo_vendedor'] = User::where('id',$request->user_id)->first()->codigo_vendedor;
        $dados['cadastrado_por'] = auth()->id();
        ContratoEmpresarial::query()->create($dados);
        Cache::forget('listarContratoEmpresaPendentes');
        return redirect('/financeiro');
    }


    public function storeColarEmpresarial(Request $request)
    {
        $texto = trim($request->input('texto_colado', ''));
        if (empty($texto)) return response()->json(['error' => 'Cole os dados no campo de texto.'], 422);

        $saudePlanoId  = $request->input('saude_plano_id')       ?: null;
        $saudeUf       = $request->input('saude_uf')             ?: null;
        $saudeCidade   = $request->input('saude_cidade')         ?: null;
        $saudeUserId   = $request->input('saude_user_id')        ?: null;
        $saudeAcomoda  = $request->input('saude_acomodacao')     ?: null;
        $saudeCopart   = $request->input('saude_coparticipacao') ?: null;
        $odontPlanoId  = $request->input('odonto_plano_id')      ?: null;
        $odontUf       = $request->input('odonto_uf')            ?: null;
        $odontCidade   = $request->input('odonto_cidade')        ?: null;
        $odontUserId   = $request->input('odonto_user_id')       ?: null;

        $temSaude  = $saudePlanoId && $saudeUf && $saudeCidade && $saudeUserId;
        $temOdonto = $odontPlanoId && $odontUf && $odontCidade && $odontUserId;

        if (!$temSaude && !$temOdonto) {
            return response()->json(['error' => 'Preencha pelo menos uma aba (Saúde ou Odonto) completamente.'], 422);
        }

        $normalizeKey = function ($s) {
            $from = ['Á','É','Í','Ó','Ú','Â','Ê','Î','Ô','Û','À','È','Ì','Ò','Ù','Ã','Õ','Ä','Ë','Ï','Ö','Ü','Ç'];
            $to   = ['A','E','I','O','U','A','E','I','O','U','A','E','I','O','U','A','O','A','E','I','O','U','C'];
            return str_replace($from, $to, mb_strtoupper(trim($s), 'UTF-8'));
        };

        $campos = [];
        foreach (explode("\n", $texto) as $linha) {
            $linha = trim($linha);
            if ($linha === '') continue;
            $pos = strpos($linha, ':');
            if ($pos === false) continue;
            $campos[$normalizeKey(substr($linha, 0, $pos))] = trim(substr($linha, $pos + 1));
        }

        $razao_social = $campos['RAZAO SOCIAL'] ?? null;
        $cnpj         = $campos['CNPJ']         ?? null;
        $responsavel  = $campos['CONTATO']      ?? null;
        $celular      = $campos['TELEFONE']     ?? null;
        $email        = $campos['EMAIL']        ?? null;

        $faltando = [];
        if (!$razao_social) $faltando[] = 'RAZÃO SOCIAL';
        if (!$cnpj)         $faltando[] = 'CNPJ';
        if (!$responsavel)  $faltando[] = 'CONTATO';
        if (!$celular)      $faltando[] = 'TELEFONE';
        if (!$email)        $faltando[] = 'EMAIL';

        if (!empty($faltando)) {
            $lista = implode(', ', $faltando);
            $plural = count($faltando) > 1 ? 'Campos não encontrados' : 'Campo não encontrado';
            return response()->json(['error' => "{$plural}: {$lista}. A ordem não importa, mas todos os campos devem estar presentes."], 422);
        }

        $tabela_origem = TabelaOrigem::first();
        if (!$tabela_origem) return response()->json(['error' => 'Nenhuma tabela de origem cadastrada no sistema.'], 422);

        // Validate saude and resolve primary (legacy) fields
        if ($temSaude) {
            if (!Plano::find($saudePlanoId)) return response()->json(['error' => 'Plano de Saúde não encontrado.'], 404);
            if (!Comissao::where('user_id', $saudeUserId)->exists()) {
                return response()->json(['error' => 'O corretor de Saúde não possui comissão cadastrada.'], 422);
            }
            $primaryPlanoId = $saudePlanoId;
            $primaryUserId  = $saudeUserId;
            $primaryUf      = $saudeUf;
            $primaryCidade  = $saudeCidade;
        } else {
            $primaryPlanoId = $odontPlanoId;
            $primaryUserId  = $odontUserId;
            $primaryUf      = $odontUf;
            $primaryCidade  = $odontCidade;
        }

        if ($temOdonto) {
            if (!Plano::find($odontPlanoId)) return response()->json(['error' => 'Plano Odontológico não encontrado.'], 404);
            if (!Comissao::where('user_id', $odontUserId)->exists()) {
                return response()->json(['error' => 'O corretor de Odonto não possui comissão cadastrada.'], 422);
            }
        }

        $primaryUser = User::find($primaryUserId);

        ContratoEmpresarial::create([
            'tabela_origens_id'     => $tabela_origem->id,
            'razao_social'          => $razao_social,
            'cnpj'                  => $cnpj,
            'responsavel'           => $responsavel,
            'celular'               => $celular,
            'email'                 => $email,
            'valor_plano'           => 0,
            'valor_plano_saude'     => 0,
            'valor_plano_odonto'    => 0,
            'valor_pagar'           => 0,
            'data_boleto'           => Carbon::today()->format('Y-m-d'),
            'vencimento_boleto'     => Carbon::today()->format('Y-m-d'),
            'cadastrado_por'        => auth()->id(),
            'pago'                  => 0,
            // Legacy / primary fields (saude takes precedence)
            'plano_id'              => $primaryPlanoId,
            'user_id'               => $primaryUserId,
            'uf'                    => $primaryUf,
            'cidade'                => $primaryCidade,
            'codigo_vendedor'       => $primaryUser ? $primaryUser->codigo_vendedor : null,
            // Saúde-specific fields
            'plano_saude_id'        => $temSaude ? $saudePlanoId : null,
            'saude_uf'              => $temSaude ? $saudeUf      : null,
            'saude_cidade'          => $temSaude ? $saudeCidade  : null,
            'saude_user_id'         => $temSaude ? $saudeUserId  : null,
            'saude_acomodacao'      => $temSaude ? $saudeAcomoda : null,
            'saude_coparticipacao'  => $temSaude ? $saudeCopart  : null,
            // Odonto-specific fields
            'plano_odonto_id'       => $temOdonto ? $odontPlanoId : null,
            'odonto_uf'             => $temOdonto ? $odontUf      : null,
            'odonto_cidade'         => $temOdonto ? $odontCidade  : null,
            'odonto_user_id'        => $temOdonto ? $odontUserId  : null,
        ]);

        Cache::forget('listarContratoEmpresaPendentes');
        $msg = ($temSaude && $temOdonto)
            ? 'Contrato com Saúde e Odonto cadastrado com sucesso!'
            : 'Contrato cadastrado com sucesso!';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function listar(Request $request)
    {

        if ($request->ajax()) {
            $cacheKey = 'listarContratoEmpresaPendentes';
            $tempoDeExpiracao = 0;
            $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () {
                $query = DB::table('contrato_empresarial')
                    ->select(
                        DB::raw("DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at"),
                        'contrato_empresarial.codigo_externo as codigo_externo',
                        'users.name as usuario',
                        DB::raw("IFNULL((SELECT name FROM users WHERE users.id = contrato_empresarial.cadastrado_por), '-') as cadastrado_por_nome"),
                        'contrato_empresarial.razao_social',
                        'contrato_empresarial.cnpj',
                        'contrato_empresarial.quantidade_vidas',
                        'contrato_empresarial.valor_plano',
                        'contrato_empresarial.valor_pagar as comissao',
                        'contrato_empresarial.email as email',
                        'contrato_empresarial.celular as fone',
                        'contrato_empresarial.cidade as cidade',
                        'contrato_empresarial.uf as uf',
                        'planos.nome as plano',
                        'contrato_empresarial.id as id',
                        'contrato_empresarial.valor_plano_saude as valor_saude',
                        'contrato_empresarial.valor_plano_odonto as valor_odonto',
                        'contrato_empresarial.codigo_saude as codigo_saude',
                        'contrato_empresarial.codigo_odonto as codigo_odonto',
                        'comissao.valor as porcentagem',
                        'contrato_empresarial.senha_cliente as senha_cliente',
                        'contrato_empresarial.pago as status',
                        'contrato_empresarial.status_pagamento as status_pagamento',
                        DB::raw("DATE_FORMAT(contrato_empresarial.vencimento_boleto,'%d/%m/%Y') as vencimento_boleto"),
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_boleto,'%d/%m/%Y') as data_boleto"),
                        'tabela_origens.nome as tabela_origens',
                        'contrato_empresarial.responsavel as responsavel',
                        'contrato_empresarial.plano_contrado as plano_contrado',
                        'contrato_empresarial.etapa_atual as etapa_atual',
                        'contrato_empresarial.planilha_path as planilha_path',
                        'contrato_empresarial.aditivo_path as aditivo_path',
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_aditivo,'%d/%m/%Y') as data_aditivo"),
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_adesao,'%d/%m/%Y') as data_adesao"),
                        'contrato_empresarial.boleto_adesao_path as boleto_adesao_path',
                        'contrato_empresarial.boleto_adesao_valor as boleto_adesao_valor',
                        'contrato_empresarial.justificativa_diferenca as justificativa_diferenca',
                        'contrato_empresarial.tem_diferenca_valor as tem_diferenca_valor',
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_pgto,'%d/%m/%Y') as data_pgto"),
                        'contrato_empresarial.forma_pagamento as forma_pagamento',
                        'contrato_empresarial.oriundo as oriundo',
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_vigencia,'%d/%m/%Y') as data_vigencia"),
                        'contrato_empresarial.carteirinha_paths as carteirinha_paths',
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_carteirinha,'%d/%m/%Y') as data_carteirinha"),
                        'contrato_empresarial.primeiro_boleto_valor as primeiro_boleto_valor',
                        DB::raw("DATE_FORMAT(contrato_empresarial.primeiro_boleto_vencimento,'%d/%m/%Y') as primeiro_boleto_vencimento"),
                        'contrato_empresarial.boleto_saude_path as boleto_saude_path',
                        'contrato_empresarial.demonstrativo_saude_path as demonstrativo_saude_path',
                        'contrato_empresarial.boleto_odonto_path as boleto_odonto_path',
                        'contrato_empresarial.demonstrativo_odonto_path as demonstrativo_odonto_path',
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_primeiro_boleto,'%d/%m/%Y') as data_primeiro_boleto"),
                        DB::raw("DATE_FORMAT(contrato_empresarial.data_baixa_finalizado,'%d/%m/%Y') as data_baixa_finalizado"),
                        'contrato_empresarial.finalizado_pdf_path as finalizado_pdf_path',
                        'contrato_empresarial.plano_saude_id as plano_saude_id',
                        'contrato_empresarial.plano_odonto_id as plano_odonto_id',
                        'contrato_empresarial.saude_coparticipacao as saude_coparticipacao',
                        'contrato_empresarial.saude_uf as saude_uf',
                        'contrato_empresarial.saude_cidade as saude_cidade',
                        'contrato_empresarial.odonto_uf as odonto_uf',
                        'contrato_empresarial.odonto_cidade as odonto_cidade',
                        DB::raw("CASE
                            WHEN contrato_empresarial.plano_saude_id IS NOT NULL AND contrato_empresarial.plano_odonto_id IS NOT NULL THEN 'ambos'
                            WHEN contrato_empresarial.plano_saude_id IS NOT NULL THEN 'saude'
                            WHEN contrato_empresarial.plano_odonto_id IS NOT NULL THEN 'odonto'
                            ELSE NULL
                        END as tipo_contrato")
                    )
                    ->join('users', 'users.id', '=', 'contrato_empresarial.user_id')
                    ->join('comissao', 'users.id', '=', 'comissao.user_id')
                    ->join('planos', 'planos.id', '=', 'contrato_empresarial.plano_id')
                    ->join('tabela_origens', 'tabela_origens.id', '=', 'contrato_empresarial.tabela_origens_id');
                return $query->get();
            });

            return response()->json(['data' => $resultado]);
        }
    }

    public function excluir(Request $request)
    {
        // Valida se o ID foi enviado na requisição
        if (!$request->id) {
            return response()->json(['error' => 'ID não fornecido'], 400);
        }

        // Busca o contrato pelo ID
        $contrato = ContratoEmpresarial::find($request->id);

        // Verifica se o contrato existe
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado'], 404);
        }

        try {
            // Tenta excluir o contrato
            $contrato->delete();
            return response()->json(['success' => 'Contrato excluído com sucesso'], 200);
        } catch (\Exception $e) {
            // Em caso de erro, retorna a mensagem de erro
            return response()->json(['error' => 'Erro ao excluir o contrato: ' . $e->getMessage()], 500);
        }


    }






    public function avancarEtapa(Request $request)
    {
        $id   = $request->input('id');
        $step = (int) $request->input('step');

        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $etapaAtual = (int) $contrato->etapa_atual;

        if ($step !== $etapaAtual + 1 || $step > 8) {
            return response()->json(['error' => 'Etapa inválida.'], 422);
        }

        ContratoEmpresarial::where('id', $id)->update(['etapa_atual' => $step]);
        Cache::forget('listarContratoEmpresaPendentes');
        return response()->json(['success' => true, 'nova_etapa' => $step]);
    }

    public function atualizarCampoEmpresarial(Request $request)
    {
        $id    = $request->input('id');
        $campo = $request->input('campo');
        $valor = $request->input('valor');

        $mapa = [
            'razao_social'                    => ['col' => 'razao_social',        'tipo' => 'texto'],
            'cnpj'                            => ['col' => 'cnpj',                'tipo' => 'texto'],
            'vidas'                           => ['col' => 'quantidade_vidas',    'tipo' => 'texto'],
            'codigo_externo'                  => ['col' => 'codigo_externo',      'tipo' => 'texto'],
            'celular'                         => ['col' => 'celular',             'tipo' => 'texto'],
            'email'                           => ['col' => 'email',               'tipo' => 'texto'],
            'responsavel'                     => ['col' => 'responsavel',         'tipo' => 'texto'],
            'cidade'                          => ['col' => 'cidade',              'tipo' => 'texto'],
            'uf'                              => ['col' => 'uf',                  'tipo' => 'texto'],
            'codigo_saude'                    => ['col' => 'codigo_saude',        'tipo' => 'texto'],
            'codigo_odonto'                   => ['col' => 'codigo_odonto',       'tipo' => 'texto'],
            'senha_cliente'                   => ['col' => 'senha_cliente',       'tipo' => 'texto'],
            'valor_saude'                     => ['col' => 'valor_plano_saude',   'tipo' => 'moeda'],
            'valor_odonto'                    => ['col' => 'valor_plano_odonto',  'tipo' => 'moeda'],
            'total_plano'                     => ['col' => 'valor_plano',         'tipo' => 'moeda'],
            'vencimento_boleto'               => ['col' => 'vencimento_boleto',   'tipo' => 'data'],
            'data_boleto'                     => ['col' => 'data_boleto',         'tipo' => 'data'],
            'data_cadastro'                   => ['col' => 'created_at',          'tipo' => 'data'],
            'mudar_corretor_empresarial'      => ['col' => 'user_id',             'tipo' => 'texto'],
            'mudar_plano_empresarial'         => ['col' => 'plano_id',            'tipo' => 'texto'],
            'mudar_tabela_origem_empresarial' => ['col' => 'tabela_origens_id',   'tipo' => 'texto'],
            'plano_contrado'                  => ['col' => 'plano_contrado',      'tipo' => 'texto'],
        ];

        if (!array_key_exists($campo, $mapa)) {
            return response()->json(['error' => 'Campo não permitido.'], 422);
        }

        $coluna = $mapa[$campo]['col'];
        $tipo   = $mapa[$campo]['tipo'];

        if ($tipo === 'moeda') {
            $valor = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9.,]/', '', $valor));
        } elseif ($tipo === 'data' && $valor) {
            try {
                $valor = Carbon::createFromFormat('d/m/Y', $valor)->format('Y-m-d');
            } catch (\Exception $e) {
                return response()->json(['error' => 'Data inválida. Use o formato dd/mm/aaaa.'], 422);
            }
        }

        ContratoEmpresarial::where('id', $id)->update([$coluna => $valor]);
        Cache::forget('listarContratoEmpresaPendentes');
        return response()->json(['success' => true]);
    }

    public function importarPlanilha(Request $request)
    {
        $contratoId    = $request->input('contrato_id');
        $modoEdicao    = (bool) $request->input('modo_edicao');
        $justificativa = trim($request->input('justificativa_diferenca', ''));

        if (!$request->hasFile('planilha') || !$request->file('planilha')->isValid()) {
            return response()->json(['error' => 'Arquivo inválido ou não enviado.'], 422);
        }

        $contrato = ContratoEmpresarial::find($contratoId);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $file = $request->file('planilha');
        $path = $file->getRealPath();

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return response()->json(['error' => 'Não foi possível abrir o arquivo .xlsx. Certifique-se de que é um arquivo Excel válido.'], 422);
        }

        // Carregar strings compartilhadas
        $sharedStrings = [];
        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssXml) {
            $ss = new \SimpleXMLElement($ssXml);
            foreach ($ss->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } else {
                    $parts = [];
                    foreach ($si->r as $r) {
                        if (isset($r->t)) $parts[] = (string) $r->t;
                    }
                    $sharedStrings[] = implode('', $parts);
                }
            }
        }

        // Carregar planilha
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetXml) {
            return response()->json(['error' => 'Planilha não encontrada dentro do arquivo.'], 422);
        }

        $sheet = new \SimpleXMLElement($sheetXml);
        $ns = $sheet->getNamespaces(true);
        $sheet->registerXPathNamespace('ns', reset($ns) ?: 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        // Converter endereço de coluna (A, B, AA…) para índice 0-based
        $colToIndex = function (string $col): int {
            $col = strtoupper($col);
            $idx = 0;
            for ($i = 0; $i < strlen($col); $i++) {
                $idx = $idx * 26 + (ord($col[$i]) - ord('A') + 1);
            }
            return $idx - 1;
        };

        // Extrair valor de célula
        $cellValue = function ($cell) use ($sharedStrings): string {
            $t = (string) ($cell->attributes()->t ?? '');
            $v = isset($cell->v) ? (string) $cell->v : '';
            if ($t === 's') {
                return $sharedStrings[(int)$v] ?? '';
            }
            return $v;
        };

        // Converter serial de data Excel para Y-m-d
        $excelDateToYmd = function ($serial): ?string {
            if (!is_numeric($serial) || (float)$serial <= 0) return null;
            $serial = (float)$serial;
            if ($serial > 60) $serial--; // bug do Lotus 123 (1900 não é bissexto)
            return gmdate('Y-m-d', (int)(($serial - 1) * 86400 + mktime(0,0,0,1,1,1900)));
        };

        $rows = $sheet->xpath('//ns:row') ?: $sheet->xpath('//row');

        // ── Extrair CNPJ da célula B3 (valor pode ser numérico, sem zeros à esquerda) ──
        $cnpjPlanilha = null;
        foreach ($rows as $row) {
            $rowNum = (int) $row->attributes()->r;
            // Verificar linha 3 primeiro (B3 é o local esperado do CNPJ)
            if ($rowNum === 3) {
                foreach ($row->c as $c) {
                    $ref = strtoupper((string) $c->attributes()->r);
                    if ($ref === 'B3') {
                        $digits = preg_replace('/[^0-9]/', '', $cellValue($c));
                        if (strlen($digits) >= 13) {
                            $cnpjPlanilha = str_pad($digits, 14, '0', STR_PAD_LEFT);
                        }
                        break 2;
                    }
                }
            }
            if ($rowNum > 3) break;
        }

        // Fallback: varre as 10 primeiras linhas procurando qualquer célula com 13-14 dígitos
        if (!$cnpjPlanilha) {
            foreach ($rows as $row) {
                if ((int) $row->attributes()->r > 10) break;
                foreach ($row->c as $c) {
                    $digits = preg_replace('/[^0-9]/', '', $cellValue($c));
                    if (strlen($digits) >= 13 && strlen($digits) <= 14) {
                        $cnpjPlanilha = str_pad($digits, 14, '0', STR_PAD_LEFT);
                        break 2;
                    }
                }
            }
        }

        // ── Comparar com CNPJ do contrato (ambos normalizados a 14 dígitos) ──
        $cnpjContrato = str_pad(preg_replace('/[^0-9]/', '', $contrato->cnpj ?? ''), 14, '0', STR_PAD_LEFT);

        if (!$cnpjPlanilha) {
            return response()->json(['error' => 'CNPJ não encontrado na planilha (esperado na célula B3). Verifique se o arquivo pertence a este contrato.'], 422);
        }

        if ($cnpjPlanilha !== $cnpjContrato) {
            return response()->json([
                'error' => 'O CNPJ da planilha (' . $cnpjPlanilha . ') não corresponde ao CNPJ deste contrato (' . $cnpjContrato . '). Verifique se enviou o arquivo correto.'
            ], 422);
        }

        // ── Encontrar linha de cabeçalho ─────────────────────────────────────
        $headerRowIndex = null;
        $colMap = [];

        $normalizeHeader = function (string $h): string {
            $from = ['á','é','í','ó','ú','â','ê','î','ô','û','à','è','ì','ò','ù','ã','õ','ä','ë','ï','ö','ü','ç',
                     'Á','É','Í','Ó','Ú','Â','Ê','Î','Ô','Û','À','È','Ì','Ò','Ù','Ã','Õ','Ä','Ë','Ï','Ö','Ü','Ç','/'];
            $to   = ['a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','o','a','e','i','o','u','c',
                     'A','E','I','O','U','A','E','I','O','U','A','E','I','O','U','A','O','A','E','I','O','U','C',' '];
            return strtolower(trim(str_replace($from, $to, $h)));
        };

        foreach ($rows as $row) {
            $cells = [];
            foreach ($row->c as $c) {
                $ref  = (string) $c->attributes()->r;
                preg_match('/^([A-Z]+)/', $ref, $m);
                $ci = $colToIndex($m[1]);
                $cells[$ci] = $cellValue($c);
            }
            $joined = strtolower(implode(' ', $cells));
            if (str_contains($joined, 'nome completo') || str_contains($joined, 'titular ou dependente')) {
                foreach ($cells as $ci => $val) {
                    $colMap[$normalizeHeader($val)] = $ci;
                }
                $headerRowIndex = (int) $row->attributes()->r;
                break;
            }
        }

        if ($headerRowIndex === null) {
            return response()->json(['error' => 'Linha de cabeçalho não encontrada na planilha. Verifique o formato do arquivo.'], 422);
        }

        // ── Ler linhas de dados ───────────────────────────────────────────────
        $get = function (array $cells, array $colMap, string ...$keys) {
            foreach ($keys as $k) {
                if (isset($colMap[$k]) && isset($cells[$colMap[$k]]) && $cells[$colMap[$k]] !== '') {
                    return $cells[$colMap[$k]];
                }
            }
            return null;
        };

        $beneficiarios = [];

        foreach ($rows as $row) {
            $rowNum = (int) $row->attributes()->r;
            if ($rowNum <= $headerRowIndex) continue;

            $cells = [];
            foreach ($row->c as $c) {
                $ref = (string) $c->attributes()->r;
                preg_match('/^([A-Z]+)/', $ref, $m);
                $ci = $colToIndex($m[1]);
                $cells[$ci] = $cellValue($c);
            }

            $tipo = $get($cells, $colMap, 'titular ou dependente');
            if (!$tipo) continue;

            $nomeTipo = strtolower(trim($tipo));
            if (!str_contains($nomeTipo, 'titular') && !str_contains($nomeTipo, 'dependente')) continue;

            $nascSerial = $get($cells, $colMap, 'data de nascimento', 'data nascimento');
            $casSerial  = $get($cells, $colMap, 'data do casamento', 'data casamento');
            $valorRaw   = $get($cells, $colMap, 'valor');

            $beneficiarios[] = [
                'contrato_empresarial_id' => $contratoId,
                'tipo'             => $tipo,
                'nome_completo'    => $get($cells, $colMap, 'nome completo'),
                'nome_titular'     => $get($cells, $colMap, 'nome titular'),
                'cpf'              => $get($cells, $colMap, 'cpf'),
                'data_nascimento'  => $excelDateToYmd($nascSerial),
                'idade'            => (int) ($get($cells, $colMap, 'idade') ?? 0) ?: null,
                'nome_mae'         => $get($cells, $colMap, 'nome da mae', 'nome mae'),
                'acomodacao'       => $get($cells, $colMap, 'saude acomodacao', 'acomodacao'),
                'sexo'             => $get($cells, $colMap, 'sexo'),
                'grau_parentesco'  => $get($cells, $colMap, 'grau do parentesco', 'grau parentesco'),
                'data_casamento'   => $excelDateToYmd($casSerial),
                'telefone'         => $get($cells, $colMap, 'telefone'),
                'valor'            => is_numeric($valorRaw) ? (float)$valorRaw : null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }

        if (empty($beneficiarios)) {
            return response()->json(['error' => 'Nenhum beneficiário encontrado na planilha. Verifique o conteúdo do arquivo.'], 422);
        }

        // ── Salvar arquivo físico em public/{cnpj}/planilha.xlsx ─────────────
        $pasta   = public_path($cnpjContrato);
        if (!is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }
        $destino = $pasta . DIRECTORY_SEPARATOR . 'planilha.xlsx';
        copy($path, $destino);
        $planilhaPath = $cnpjContrato . '/planilha.xlsx';

        // ── Reimportar beneficiários ──────────────────────────────────────────
        Beneficiario::where('contrato_empresarial_id', $contratoId)->delete();
        foreach (array_chunk($beneficiarios, 100) as $chunk) {
            Beneficiario::insert($chunk);
        }

        // ── Calcular vidas e valores ──────────────────────────────────────────
        $totalVidas  = count($beneficiarios);
        $valorSaude  = 0.0;
        $valorOdonto = 0.0;

        if ($contrato->plano_saude_id) {
            $cidadeSaude = Cidade::where('nome', $contrato->saude_cidade)
                ->where('uf', $contrato->saude_uf)
                ->first();

            if ($cidadeSaude) {
                $copart = $contrato->saude_coparticipacao;

                $faixasTodas = FaixaEtariaValor::where('plano_id', $contrato->plano_saude_id)
                    ->where('cidade_id', $cidadeSaude->id)
                    ->get()
                    ->keyBy('faixa');

                $ageToFaixa = function (int $age): int {
                    if ($age <= 18) return 0;
                    if ($age <= 23) return 1;
                    if ($age <= 28) return 2;
                    if ($age <= 33) return 3;
                    if ($age <= 38) return 4;
                    if ($age <= 43) return 5;
                    if ($age <= 48) return 6;
                    if ($age <= 53) return 7;
                    if ($age <= 58) return 8;
                    return 9;
                };

                foreach ($beneficiarios as $b) {
                    $faixa    = $ageToFaixa((int) ($b['idade'] ?? 0));
                    $faixaRec = $faixasTodas->get($faixa);
                    if (!$faixaRec) continue;

                    $acomoda = strtolower(trim($b['acomodacao'] ?? ''));
                    $isApart = str_contains($acomoda, 'apart');

                    if ($copart === 'com') {
                        $colFaixa = $isApart ? 'com_copart_apart' : 'com_copart_enfer';
                    } else {
                        $colFaixa = $isApart ? 'sem_copart_apart' : 'sem_copart_enfer';
                    }

                    $valorSaude += (float) ($faixaRec->$colFaixa ?? 0);
                }
            }
        }

        if ($contrato->plano_odonto_id) {
            $cidadeOdonto = Cidade::where('nome', $contrato->odonto_cidade)
                ->where('uf', $contrato->odonto_uf)
                ->first();

            if ($cidadeOdonto) {
                $ov = OdontoValor::where('plano_id', $contrato->plano_odonto_id)
                    ->where('cidade_id', $cidadeOdonto->id)
                    ->first();

                if ($ov) {
                    $valorOdonto = round((float) $ov->valor * $totalVidas, 2);
                }
            }
        }

        // Fallback: se nenhum plano novo estiver configurado, usa coluna "valor" da planilha
        if (!$contrato->plano_saude_id && !$contrato->plano_odonto_id) {
            $totalValor = array_reduce($beneficiarios, fn ($carry, $b) => $carry + (float) ($b['valor'] ?? 0), 0.0);
        } else {
            $valorSaude  = round($valorSaude, 2);
            $totalValor  = round($valorSaude + $valorOdonto, 2);
        }

        $comissao   = Comissao::where('user_id', $contrato->user_id)->first();
        $valorPagar = $comissao ? round($totalValor * ($comissao->valor / 100), 2) : 0;

        // ── Modo edição: checar divergência com boleto de adesão ─────────────
        $etapaAtual = (int) $contrato->etapa_atual;
        if ($modoEdicao && $etapaAtual >= 4) {
            $boletoAdesaoValor = (float) $contrato->boleto_adesao_valor;
            if ($boletoAdesaoValor > 0 && abs($totalValor - $boletoAdesaoValor) > 0.01 && empty($justificativa)) {
                return response()->json([
                    'divergencia'    => true,
                    'valor_anterior' => $boletoAdesaoValor,
                    'valor_novo'     => $totalValor,
                ]);
            }
        }

        // ── Avançar etapa e salvar path + vidas + valor ───────────────────────
        $updates = [
            'planilha_path'      => $planilhaPath,
            'quantidade_vidas'   => $totalVidas,
            'valor_plano'        => $totalValor,
            'valor_pagar'        => $valorPagar,
        ];
        if ($contrato->plano_saude_id || $contrato->plano_odonto_id) {
            $updates['valor_plano_saude']  = $valorSaude;
            $updates['valor_plano_odonto'] = $valorOdonto;
        }

        if ($modoEdicao && $etapaAtual > 0) {
            // Re-import: não avança etapa
            if (!empty($justificativa)) {
                $updates['justificativa_diferenca'] = $justificativa;
                $updates['tem_diferenca_valor']     = 1;
            } elseif ($etapaAtual >= 4) {
                $boletoAdesaoValor = (float) $contrato->boleto_adesao_valor;
                if ($boletoAdesaoValor > 0 && abs($totalValor - $boletoAdesaoValor) <= 0.01) {
                    $updates['tem_diferenca_valor']     = 0;
                    $updates['justificativa_diferenca'] = null;
                }
            }
        } else {
            if ($etapaAtual === 0) {
                $updates['etapa_atual'] = 1;
            }
        }

        $contrato->update($updates);

        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json([
            'success' => true,
            'message' => $totalVidas . ' vida(s) importada(s) · Valor total: R$ ' . number_format($totalValor, 2, ',', '.'),
            'total'   => $totalVidas,
        ]);
    }

    public function uploadAditivoPdf(Request $request)
    {
        $contratoId = $request->input('contrato_id');

        if (!$request->hasFile('aditivo') || !$request->file('aditivo')->isValid()) {
            return response()->json(['error' => 'Arquivo PDF inválido ou não enviado.'], 422);
        }

        $contrato = ContratoEmpresarial::find($contratoId);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $etapaAtual = (int) $contrato->etapa_atual;
        if ($etapaAtual < 1) {
            return response()->json(['error' => 'A planilha de beneficiários deve ser importada antes do aditivo.'], 422);
        }

        $arquivo = $request->file('aditivo');
        $ext     = strtolower($arquivo->getClientOriginalExtension());
        $mime    = $arquivo->getMimeType();
        if ($ext !== 'pdf' || strpos($mime, 'pdf') === false) {
            return response()->json(['error' => 'Apenas arquivos PDF são aceitos.'], 422);
        }

        $dataAditivo = $request->input('data_aditivo');
        if (!$dataAditivo) {
            return response()->json(['error' => 'Informe a data do aditivo.'], 422);
        }
        try {
            $dataAditivo = Carbon::createFromFormat('Y-m-d', $dataAditivo)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data inválida.'], 422);
        }

        $cnpj  = preg_replace('/[^0-9]/', '', $contrato->cnpj ?? '');
        $pasta = public_path($cnpj);
        if (!is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }

        $nomeArquivo = 'aditivo_' . time() . '.pdf';
        $arquivo->move($pasta, $nomeArquivo);
        $path = $cnpj . '/' . $nomeArquivo;

        $updates = ['aditivo_path' => $path, 'data_aditivo' => $dataAditivo];
        if ($etapaAtual === 1) {
            $updates['etapa_atual'] = 2;
        }

        ContratoEmpresarial::where('id', $contratoId)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true, 'message' => 'Aditivo enviado com sucesso!']);
    }

    public function extrairValorBoletoPdf(Request $request)
    {
        if (!$request->hasFile('boleto') || !$request->file('boleto')->isValid()) {
            return response()->json(['error' => 'Arquivo PDF inválido.'], 422);
        }

        $file = $request->file('boleto');
        if (strtolower($file->getClientOriginalExtension()) !== 'pdf') {
            return response()->json(['error' => 'Apenas arquivos PDF são aceitos.'], 422);
        }

        $texto = $this->lerTextoPdf($file->getRealPath());
        $valor = $this->extrairValorDoBoleto($texto);

        if ($valor === null) {
            $dekerned = preg_replace('/(\d) (?=[\d.])/', '$1', $texto);
            $dekerned = preg_replace('/(\.)\s(?=\d)/', '$1', $dekerned);
            $dekerned = preg_replace('/(\d) (?=[\d.])/', '$1', $dekerned);
            $compact  = preg_replace('/\s/', '', $texto);

            // Garante UTF-8 válido nos campos de diagnóstico antes de serializar
            $utf8 = fn(string $s) => mb_convert_encoding($s, 'UTF-8', 'UTF-8');

            return response()->json([
                'error'    => 'Não foi possível identificar o valor neste PDF.',
                'manual'   => true,
                'preview'  => $utf8(mb_substr($texto,    0, 800)),
                'dekerned' => $utf8(mb_substr($dekerned, 0, 800)),
                'compact'  => $utf8(mb_substr($compact,  0, 800)),
            ], 422);
        }

        return response()->json(['success' => true, 'valor' => $valor]);
    }

    // Endpoint de diagnóstico — retorna o texto bruto extraído do PDF
    public function debugExtrairPdf(Request $request)
    {
        if (!$request->hasFile('boleto') || !$request->file('boleto')->isValid()) {
            return response()->json(['error' => 'Arquivo inválido.'], 422);
        }
        $path  = $request->file('boleto')->getRealPath();
        $raw   = file_get_contents($path);
        $texto = $this->lerTextoPdf($path);

        $streamCount = 0;
        $decodedSamples = [];
        $cursor = 0;
        while (($sPos = strpos($raw, 'stream', $cursor)) !== false) {
            $cursor = $sPos + 6;
            $dStart = $sPos + 6;
            if (isset($raw[$dStart]) && $raw[$dStart] === "\r") $dStart++;
            if (isset($raw[$dStart]) && $raw[$dStart] === "\n") $dStart++;
            $ePos = strpos($raw, 'endstream', $dStart);
            if ($ePos === false) continue;
            $data = rtrim(substr($raw, $dStart, $ePos - $dStart));
            $streamCount++;
            $dec = @gzuncompress($data) ?: @gzinflate($data) ?: (strlen($data)>2 ? @gzinflate(substr($data,2)) : false);
            if ($dec !== false) {
                $decodedSamples[] = ['stream' => $streamCount, 'sample' => mb_substr($dec, 0, 300)];
            }
        }

        return response()->json([
            'texto_total'     => $texto,
            'streams_total'   => $streamCount,
            'decoded_samples' => $decodedSamples,
        ]);
    }

    public function uploadAdesao(Request $request)
    {
        try {
            $contratoId    = $request->input('contrato_id');
            $dataAdesao    = $request->input('data_adesao');
            $justificativa = trim($request->input('justificativa_diferenca', ''));

            $contrato = ContratoEmpresarial::find($contratoId);
            if (!$contrato) {
                return response()->json(['error' => 'Contrato não encontrado.'], 404);
            }
            if ((int) $contrato->etapa_atual < 2) {
                return response()->json(['error' => 'O aditivo deve ser enviado antes da adesão.'], 422);
            }
            if (!$dataAdesao) {
                return response()->json(['error' => 'Informe a data de adesão.'], 422);
            }
            if (!$request->hasFile('boleto_adesao') || !$request->file('boleto_adesao')->isValid()) {
                return response()->json(['error' => 'Envie o PDF do boleto.'], 422);
            }

            try {
                $dataAdesao = Carbon::createFromFormat('Y-m-d', $dataAdesao)->format('Y-m-d');
            } catch (\Exception $e) {
                return response()->json(['error' => 'Data inválida.'], 422);
            }

            $arquivo = $request->file('boleto_adesao');

            // Verificar extensão (apenas — MIME pode ser inconsistente no Windows)
            if (strtolower($arquivo->getClientOriginalExtension()) !== 'pdf') {
                return response()->json(['error' => 'Apenas arquivos PDF são aceitos.'], 422);
            }

            // Tentar extrair valor automaticamente do PDF
            $boletoValor = null;
            try {
                $texto       = $this->lerTextoPdf($arquivo->getRealPath());
                $boletoValor = $this->extrairValorDoBoleto($texto);
            } catch (\Throwable $e) {
                // Falha silenciosa; usar valor manual
            }

            // Fallback: valor digitado manualmente
            if ($boletoValor === null) {
                $valorManualRaw = trim($request->input('boleto_valor_manual', ''));
                if ($valorManualRaw !== '') {
                    $boletoValor = $this->converterValorBr($valorManualRaw);
                }
            }

            if ($boletoValor === null || $boletoValor <= 0) {
                return response()->json(['error' => 'Não foi possível determinar o valor do boleto. Preencha o campo de valor manualmente.'], 422);
            }

            $valorPlanilha = (float) $contrato->valor_plano;
            $temDiferenca  = $valorPlanilha > 0 && abs($boletoValor - $valorPlanilha) > 0.01;

            if ($temDiferenca && empty($justificativa)) {
                return response()->json([
                    'error' => 'O valor do boleto (R$ ' . number_format($boletoValor, 2, ',', '.') . ') difere da planilha (R$ ' . number_format($valorPlanilha, 2, ',', '.') . '). Informe a justificativa.',
                ], 422);
            }

            $cnpj  = preg_replace('/[^0-9]/', '', $contrato->cnpj ?? '');
            $pasta = public_path($cnpj);
            if (!is_dir($pasta)) {
                mkdir($pasta, 0755, true);
            }

            $nomeArquivo = 'boleto_adesao_' . time() . '.pdf';
            $arquivo->move($pasta, $nomeArquivo);
            $boletoPath = $cnpj . '/' . $nomeArquivo;

            $updates = [
                'data_adesao'             => $dataAdesao,
                'boleto_adesao_path'      => $boletoPath,
                'boleto_adesao_valor'     => $boletoValor,
                'justificativa_diferenca' => $temDiferenca ? $justificativa : null,
                'tem_diferenca_valor'     => $temDiferenca ? 1 : 0,
            ];
            if ((int) $contrato->etapa_atual === 2) {
                $updates['etapa_atual'] = 3;
            }

            ContratoEmpresarial::where('id', $contratoId)->update($updates);
            Cache::forget('listarContratoEmpresaPendentes');

            return response()->json(['success' => true, 'message' => 'Adesão registrada com sucesso!']);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    // ── Extração de texto de PDF ─────────────────────────────────────────────

    private function lerTextoPdf(string $filePath): string
    {
        // Tenta primeiro com smalot/pdfparser (lida com fontes custom/ToUnicode)
        try {
            $config = new \Smalot\PdfParser\Config();
            $config->setRetainImageContent(false);
            $parser = new \Smalot\PdfParser\Parser([], $config);
            $pdf    = $parser->parseFile($filePath);
            $text   = $pdf->getText();
            if (trim($text) !== '') {
                return preg_replace('/\s+/', ' ', $text);
            }
        } catch (\Throwable $e) {
            // Silencia e cai no extrator manual abaixo
        }

        // Fallback: extrator manual (PDFs sem compressão ou encoding simples)
        $raw = @file_get_contents($filePath);
        if ($raw === false || strlen($raw) < 8) return '';

        $parts = [];

        // Passo 1 — texto em conteúdo bruto (não comprimido)
        $parts[] = $this->extrairTextoPdfContent($raw);

        // Passo 2 — localizar cada stream com cursor (mais confiável que regex)
        $cursor = 0;
        $len    = strlen($raw);
        while ($cursor < $len) {
            $sPos = strpos($raw, 'stream', $cursor);
            if ($sPos === false) break;
            $cursor = $sPos + 6;

            // Pular CR e/ou LF logo após a palavra 'stream'
            $dStart = $sPos + 6;
            if ($dStart < $len && $raw[$dStart] === "\r") $dStart++;
            if ($dStart < $len && $raw[$dStart] === "\n") $dStart++;

            $ePos = strpos($raw, 'endstream', $dStart);
            if ($ePos === false) continue;

            // Remover whitespace final antes de 'endstream'
            $streamData = rtrim(substr($raw, $dStart, $ePos - $dStart), "\r\n ");
            if (strlen($streamData) < 4) continue;

            // Tentar vários métodos de descompressão
            $dec = false;
            // 1) zlib com header (bytes 0x78 xx)
            if (ord($streamData[0]) === 0x78) {
                $dec = @gzuncompress($streamData);
            }
            // 2) deflate raw
            if ($dec === false) $dec = @gzinflate($streamData);
            // 3) pular 2 bytes de header zlib e tentar inflate
            if ($dec === false && strlen($streamData) > 2) {
                $dec = @gzinflate(substr($streamData, 2));
            }
            // 4) pular 1 byte
            if ($dec === false && strlen($streamData) > 1) {
                $dec = @gzinflate(substr($streamData, 1));
            }
            // 5) gzuncompress sem checar header
            if ($dec === false) {
                $dec = @gzuncompress($streamData);
            }

            if ($dec !== false && strlen($dec) > 10) {
                $parts[] = $this->extrairTextoPdfContent($dec);
            }
        }

        return preg_replace('/\s+/', ' ', implode(' ', $parts));
    }

    private function extrairTextoPdfContent(string $data): string
    {
        $tokens = [];

        // ── Operador Tj: (string) Tj ──────────────────────────────────────────
        // Usa expressão que suporta escapes dentro de parênteses
        if (preg_match_all('/\((?:[^()\\\\]|\\\\.)*\)\s*Tj/s', $data, $mm)) {
            foreach ($mm[0] as $hit) {
                preg_match('/\(((?:[^()\\\\]|\\\\.)*)\)/', $hit, $inner);
                if (isset($inner[1])) {
                    $s = $this->decodePdfString($inner[1]);
                    if ($s !== '') $tokens[] = $s;
                }
            }
        }

        // ── Operador TJ: [(str) kern (str)] TJ ───────────────────────────────
        if (preg_match_all('/\[((?:[^[\]]|\((?:[^()\\\\]|\\\\.)*\))*)\]\s*TJ/s', $data, $mm)) {
            foreach ($mm[1] as $tjContent) {
                if (preg_match_all('/\(((?:[^()\\\\]|\\\\.)*)\)/', $tjContent, $parts)) {
                    $seg = '';
                    foreach ($parts[1] as $p) {
                        $seg .= $this->decodePdfString($p);
                    }
                    if ($seg !== '') $tokens[] = $seg;
                }
            }
        }

        // ── Operador ' (move-to-next-line + Tj) ──────────────────────────────
        if (preg_match_all('/\((?:[^()\\\\]|\\\\.)*\)\s*\'/s', $data, $mm)) {
            foreach ($mm[0] as $hit) {
                preg_match('/\(((?:[^()\\\\]|\\\\.)*)\)/', $hit, $inner);
                if (isset($inner[1])) {
                    $s = $this->decodePdfString($inner[1]);
                    if ($s !== '') $tokens[] = $s;
                }
            }
        }

        return implode(' ', $tokens);
    }

    private function decodePdfString(string $s): string
    {
        // Decodificar sequências de escape comuns em strings PDF
        $s = str_replace(['\\n', '\\r', '\\t', '\\b', '\\f'], [' ', ' ', ' ', '', ''], $s);
        $s = preg_replace_callback('/\\\\([0-7]{1,3})/', function ($m) {
            return chr(octdec($m[1]));
        }, $s);
        $s = preg_replace('/\\\\(.)/', '$1', $s);

        // Manter caracteres imprimíveis e converter Latin-1 → UTF-8
        $out = '';
        for ($i = 0; $i < strlen($s); $i++) {
            $c = ord($s[$i]);
            if ($c >= 32 && $c < 127) {
                $out .= $s[$i];                                          // ASCII normal
            } elseif ($c >= 160) {
                $out .= chr(0xC0 | ($c >> 6)) . chr(0x80 | ($c & 0x3F)); // Latin-1 → UTF-8
            }
            // bytes 127–159: caracteres de controlo, descartados
        }
        return trim($out);
    }

    private function extrairValorDoBoleto(string $texto): ?float
    {
        $texto = preg_replace('/\s+/', ' ', trim($texto));

        // Gera variantes do texto para cobrir PDFs com kerning de caracteres
        // Variante 1: remove espaços entre dígitos/pontos adjacentes
        //   "0 0 1 9 0 . 0 0 0 0 9" → "00190.00009"
        $dekerned = preg_replace('/(\d) (?=[\d.])/', '$1', $texto);
        $dekerned = preg_replace('/(\.)\s(?=\d)/',  '$1', $dekerned);
        $dekerned = preg_replace('/(\d) (?=[\d.])/', '$1', $dekerned); // segunda passagem

        // Variante 2: remove todos os espaços
        $compact = preg_replace('/\s/', '', $texto);

        // ── Padrão 1 (mais confiável): linha digitável do boleto bancário ────────
        // Formato: AAAAA.BBBBB CCCCC.CCCCCC DDDDD.DDDDDD D FFFFFFFFFFFFFF
        // 5º campo: 14 dígitos — posições 0-3 = vencimento, 4-13 = valor em centavos
        // Exemplo: 14270000017980 → centavos = 0000017980 = 17980 → R$ 179,80
        // \s* cobre tanto com espaços normais quanto sem espaços entre grupos
        $reLinhadig = '/\d{5}\.\d{5}\s*\d{5}\.\d{6}\s*\d{5}\.\d{6}\s*\d\s*(\d{14})/';
        foreach ([$texto, $dekerned, $compact] as $v) {
            if (preg_match($reLinhadig, $v, $m)) {
                $centavos = (int) substr($m[1], 4, 10);
                if ($centavos > 0) return round($centavos / 100, 2);
            }
        }

        // ── Padrões de texto: tenta no texto normal e no dekerned ────────────────
        foreach ([$texto, $dekerned] as $v) {
            // "Valor do Documento 179,80"
            if (preg_match('/[Vv]alor\s+do\s+[Dd]ocumento\s*[:\s]\s*([\d.,]+)/i', $v, $m)) {
                $r = $this->converterValorBr($m[1]); if ($r) return $r;
            }
            // "(=) 179,80"
            if (preg_match('/\(=\)\s*([\d.,]+)/i', $v, $m)) {
                $r = $this->converterValorBr($m[1]); if ($r) return $r;
            }
            // "Valor Documento" (sem "do")
            if (preg_match('/[Vv]alor\s+[Dd]ocumento\s*[:\s]\s*([\d.,]+)/i', $v, $m)) {
                $r = $this->converterValorBr($m[1]); if ($r) return $r;
            }
            // "=) 179,80"
            if (preg_match('/=\)\s+([\d.,]+)/i', $v, $m)) {
                $r = $this->converterValorBr($m[1]); if ($r) return $r;
            }
        }

        return null;
    }

    private function converterValorBr(string $valor): ?float
    {
        $valor = preg_replace('/[^0-9.,]/', '', $valor);
        // Formato BR: 1.234,56 → remover pontos de milhar, vírgula = decimal
        if (strpos($valor, ',') !== false) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }
        $f = (float) $valor;
        return $f > 0 ? $f : null;
    }

    public function salvarDataAdesao(Request $request)
    {
        $id         = $request->input('id');
        $dataAdesao = $request->input('data_adesao');

        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $etapaAtual = (int) $contrato->etapa_atual;
        if ($etapaAtual < 2) {
            return response()->json(['error' => 'O aditivo deve ser enviado antes da adesão.'], 422);
        }

        if (!$dataAdesao) {
            return response()->json(['error' => 'Informe a data de adesão.'], 422);
        }

        try {
            $dataAdesao = Carbon::createFromFormat('Y-m-d', $dataAdesao)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data inválida.'], 422);
        }

        $updates = ['data_adesao' => $dataAdesao];
        if ($etapaAtual === 2) {
            $updates['etapa_atual'] = 3;
        }

        ContratoEmpresarial::where('id', $id)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true]);
    }

    public function salvarBoleto(Request $request)
    {
        $id             = $request->input('id');
        $dataPgto       = $request->input('data_pgto');
        $formaPagamento = $request->input('forma_pagamento');
        $oriundo        = $request->input('oriundo', 'Accert');

        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        if ((int) $contrato->etapa_atual < 3) {
            return response()->json(['error' => 'A adesão deve ser registrada antes do boleto.'], 422);
        }

        if (!$dataPgto) {
            return response()->json(['error' => 'Informe a data de pagamento.'], 422);
        }
        if (!$formaPagamento) {
            return response()->json(['error' => 'Selecione a forma de pagamento.'], 422);
        }

        try {
            $dataPgto = Carbon::createFromFormat('Y-m-d', $dataPgto)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data inválida.'], 422);
        }

        $updates = [
            'data_pgto'       => $dataPgto,
            'forma_pagamento' => $formaPagamento,
            'oriundo'         => $oriundo,
        ];
        if ((int) $contrato->etapa_atual === 3) {
            $updates['etapa_atual'] = 4;
        }

        ContratoEmpresarial::where('id', $id)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true]);
    }

    public function salvarVigenciaColar(Request $request)
    {
        $contratoId = $request->input('contrato_id');
        $texto      = trim($request->input('texto_colar', ''));

        if (!$texto) {
            return response()->json(['error' => 'Cole o texto no campo.'], 422);
        }

        $contrato = ContratoEmpresarial::find($contratoId);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        if ((int) $contrato->etapa_atual < 4) {
            return response()->json(['error' => 'O boleto deve ser registrado antes da vigência.'], 422);
        }

        $normalize = function (string $s): string {
            $from = ['Á','É','Í','Ó','Ú','Â','Ê','Î','Ô','Û','À','È','Ì','Ò','Ù','Ã','Õ','Ä','Ë','Ï','Ö','Ü','Ç',
                     'á','é','í','ó','ú','â','ê','î','ô','û','à','è','ì','ò','ù','ã','õ','ä','ë','ï','ö','ü','ç'];
            $to   = ['A','E','I','O','U','A','E','I','O','U','A','E','I','O','U','A','O','A','E','I','O','U','C',
                     'A','E','I','O','U','A','E','I','O','U','A','E','I','O','U','A','O','A','E','I','O','U','C'];
            return str_replace($from, $to, mb_strtoupper(trim($s), 'UTF-8'));
        };

        $campos = [];
        foreach (explode("\n", $texto) as $linha) {
            $linha = trim($linha);
            if ($linha === '') continue;
            $linha = preg_replace('/^\d+\.\s*/', '', $linha); // strip "1. "
            $pos   = strpos($linha, ':');
            if ($pos === false) continue;
            $chave = $normalize(substr($linha, 0, $pos));
            $valor = trim(substr($linha, $pos + 1));
            $campos[$chave] = $valor;
        }

        // Parse CÓDIGO line: "SAUDE :UH8XA ODONTO: SJATL"
        $codigoLinha  = $campos['CODIGO'] ?? null;
        $codigoSaude  = null;
        $codigoOdonto = null;
        if ($codigoLinha) {
            if (preg_match('/SAUDE\s*:\s*([A-Z0-9]+)/i', $codigoLinha, $m)) {
                $codigoSaude = strtoupper(trim($m[1]));
            }
            if (preg_match('/ODONTO\s*:\s*([A-Z0-9]+)/i', $codigoLinha, $m)) {
                $codigoOdonto = strtoupper(trim($m[1]));
            }
        }

        $senha       = $campos['SENHA']   ?? null;
        $vigenciaRaw = $campos['VIGENCIA'] ?? null;

        $dataVigencia = null;
        if ($vigenciaRaw) {
            try {
                $dataVigencia = Carbon::createFromFormat('d/m/Y', $vigenciaRaw)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $dataVigencia = Carbon::createFromFormat('Y-m-d', $vigenciaRaw)->format('Y-m-d');
                } catch (\Exception $e2) {}
            }
        }

        if (!$codigoSaude) {
            return response()->json(['error' => 'Código SAUDE não encontrado. Verifique o formato do texto (ex: SAUDE: UH8XA).'], 422);
        }
        if ($contrato->plano_odonto_id && !$codigoOdonto) {
            return response()->json(['error' => 'Contrato Saúde + Odonto: código ODONTO obrigatório (ex: ODONTO: SJATL).'], 422);
        }
        if (!$senha) {
            return response()->json(['error' => 'SENHA não encontrada. Verifique o formato do texto.'], 422);
        }
        if (!$dataVigencia) {
            return response()->json(['error' => 'Data de Vigência não encontrada ou inválida. Use o formato dd/mm/aaaa.'], 422);
        }

        $updates = [
            'codigo_saude'   => $codigoSaude,
            'codigo_externo' => $codigoSaude,
            'codigo_odonto'  => $codigoOdonto,
            'senha_cliente'  => $senha,
            'data_vigencia'  => $dataVigencia,
        ];
        if ((int) $contrato->etapa_atual === 4) {
            $updates['etapa_atual'] = 5;
        }

        ContratoEmpresarial::where('id', $contratoId)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true, 'message' => 'Vigência registrada com sucesso!']);
    }

    public function salvarVigencia(Request $request)
    {
        $id           = $request->input('id');
        $dataVigencia = $request->input('data_vigencia');

        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        if ((int) $contrato->etapa_atual < 4) {
            return response()->json(['error' => 'O boleto deve ser registrado antes da vigência.'], 422);
        }

        if (!$dataVigencia) {
            return response()->json(['error' => 'Informe a data de vigência.'], 422);
        }

        try {
            $dataVigencia = Carbon::createFromFormat('Y-m-d', $dataVigencia)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data inválida.'], 422);
        }

        $updates = ['data_vigencia' => $dataVigencia];
        if ((int) $contrato->etapa_atual === 4) {
            $updates['etapa_atual'] = 5;
        }

        ContratoEmpresarial::where('id', $id)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true]);
    }

    public function atualizarContrato(Request $request)
    {
        $id = $request->input('id');
        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $temSaude  = $request->input('tem_saude')  === '1';
        $temOdonto = $request->input('tem_odonto') === '1';

        if (!$temSaude && !$temOdonto) {
            return response()->json(['error' => 'Selecione ao menos um tipo de plano.'], 422);
        }

        $updates = [];

        // Dados básicos
        foreach (['razao_social', 'cnpj', 'responsavel', 'celular', 'email'] as $campo) {
            $val = trim($request->input($campo, ''));
            if ($val !== '') $updates[$campo] = $val;
        }

        if ($temSaude) {
            $updates['plano_saude_id']      = $request->input('plano_saude_id') ?: null;
            $updates['saude_coparticipacao'] = $request->input('saude_coparticipacao') ?: null;
            $updates['saude_uf']             = trim($request->input('saude_uf', '')) ?: null;
            $updates['saude_cidade']         = trim($request->input('saude_cidade', '')) ?: null;
        } else {
            // Usuário desmarcou Saúde — limpa os campos
            $updates['plano_saude_id']      = null;
            $updates['saude_coparticipacao'] = null;
            $updates['saude_uf']             = null;
            $updates['saude_cidade']         = null;
        }

        if ($temOdonto) {
            $updates['plano_odonto_id'] = $request->input('plano_odonto_id') ?: null;
            $updates['odonto_uf']       = trim($request->input('odonto_uf', '')) ?: null;
            $updates['odonto_cidade']   = trim($request->input('odonto_cidade', '')) ?: null;
        } else {
            $updates['plano_odonto_id'] = null;
            $updates['odonto_uf']       = null;
            $updates['odonto_cidade']   = null;
        }

        // Legacy: plano_id, cidade, uf — prioridade saúde > odonto
        $updates['plano_id'] = ($updates['plano_saude_id'] ?? null) ?: ($updates['plano_odonto_id'] ?? null);
        $updates['cidade']   = ($updates['saude_cidade']   ?? null) ?: ($updates['odonto_cidade']   ?? null);
        $updates['uf']       = ($updates['saude_uf']       ?? null) ?: ($updates['odonto_uf']       ?? null);

        ContratoEmpresarial::where('id', $id)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true]);
    }

    public function deletarCarteirinha(Request $request)
    {
        $contratoId = $request->input('contrato_id');
        $pathRemover = $request->input('path');

        $contrato = ContratoEmpresarial::find($contratoId);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $paths = $contrato->carteirinha_paths ?? [];
        $paths = array_values(array_filter($paths, fn($p) => $p !== $pathRemover));

        ContratoEmpresarial::where('id', $contratoId)->update([
            'carteirinha_paths' => json_encode($paths),
        ]);
        Cache::forget('listarContratoEmpresaPendentes');

        $arquivoFisico = public_path($pathRemover);
        if (is_file($arquivoFisico)) {
            @unlink($arquivoFisico);
        }

        return response()->json(['success' => true, 'paths' => $paths]);
    }

    public function uploadCarteirinha(Request $request)
    {
        $contratoId = $request->input('contrato_id');

        if (!$request->hasFile('carteirinhas')) {
            return response()->json(['error' => 'Nenhum arquivo enviado.'], 422);
        }

        $contrato = ContratoEmpresarial::find($contratoId);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        if ((int) $contrato->etapa_atual < 5) {
            return response()->json(['error' => 'A vigência deve ser registrada antes das carteirinhas.'], 422);
        }

        $cnpj  = preg_replace('/[^0-9]/', '', $contrato->cnpj ?? '');
        $pasta = public_path($cnpj);
        if (!is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }

        $paths = $contrato->carteirinha_paths ?? [];
        $total = 0;

        foreach ($request->file('carteirinhas') as $arquivo) {
            if (!$arquivo->isValid()) continue;
            $ext  = strtolower($arquivo->getClientOriginalExtension());
            $mime = $arquivo->getMimeType();
            if ($ext !== 'pdf' || strpos($mime, 'pdf') === false) continue;

            $nome    = 'carteirinha_' . time() . '_' . $total . '.pdf';
            $arquivo->move($pasta, $nome);
            $paths[] = $cnpj . '/' . $nome;
            $total++;
        }

        if ($total === 0) {
            return response()->json(['error' => 'Nenhum arquivo PDF válido encontrado. Verifique se todos os arquivos são .pdf.'], 422);
        }

        $updates = [
            'carteirinha_paths' => json_encode($paths),
            'data_carteirinha'  => Carbon::today()->format('Y-m-d'),
        ];
        if ((int) $contrato->etapa_atual === 5) {
            $updates['etapa_atual'] = 6;
        }

        ContratoEmpresarial::where('id', $contratoId)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json([
            'success' => true,
            'message' => $total . ' carteirinha(s) enviada(s) com sucesso!',
            'paths'   => $paths,
            'data'    => Carbon::today()->format('d/m/Y'),
        ]);
    }

    public function uploadDocumentoBoleto(Request $request)
    {
        $contratoId = $request->input('contrato_id');
        $tipo       = $request->input('tipo');

        $tiposPermitidos = [
            'boleto_saude',
            'demonstrativo_saude',
            'boleto_odonto',
            'demonstrativo_odonto',
        ];

        if (!in_array($tipo, $tiposPermitidos)) {
            return response()->json(['error' => 'Tipo de documento inválido.'], 422);
        }

        $contrato = ContratoEmpresarial::find($contratoId);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        if ((int) $contrato->etapa_atual < 6) {
            return response()->json(['error' => 'As carteirinhas devem ser enviadas antes dos documentos do 1º boleto.'], 422);
        }

        if (!$request->hasFile('arquivo') || !$request->file('arquivo')->isValid()) {
            return response()->json(['error' => 'Arquivo PDF inválido ou não enviado.'], 422);
        }

        $arquivo = $request->file('arquivo');
        if (strtolower($arquivo->getClientOriginalExtension()) !== 'pdf') {
            return response()->json(['error' => 'Apenas arquivos PDF são aceitos.'], 422);
        }

        $cnpj  = preg_replace('/[^0-9]/', '', $contrato->cnpj ?? '');
        $pasta = public_path($cnpj);
        if (!is_dir($pasta)) {
            mkdir($pasta, 0755, true);
        }

        $nomeArquivo = $tipo . '_' . time() . '.pdf';
        $arquivo->move($pasta, $nomeArquivo);
        $path = $cnpj . '/' . $nomeArquivo;

        // Salva a coluna específica
        ContratoEmpresarial::where('id', $contratoId)->update([$tipo . '_path' => $path]);

        // Recarrega para verificar se todos os 4 foram enviados
        $contrato->refresh();
        $todosEnviados = $contrato->boleto_saude_path
            && $contrato->demonstrativo_saude_path
            && $contrato->boleto_odonto_path
            && $contrato->demonstrativo_odonto_path;

        if ($todosEnviados && (int) $contrato->etapa_atual === 6) {
            ContratoEmpresarial::where('id', $contratoId)->update([
                'etapa_atual'         => 7,
                'data_primeiro_boleto' => Carbon::today()->format('Y-m-d'),
            ]);
        }

        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json([
            'success'       => true,
            'message'       => 'Documento enviado com sucesso!',
            'path'          => $path,
            'todos_enviados' => $todosEnviados,
        ]);
    }

    public function salvarPrimeiroBoleto(Request $request)
    {
        $id         = $request->input('id');
        $valorRaw   = $request->input('primeiro_boleto_valor');
        $vencimento = $request->input('primeiro_boleto_vencimento');

        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        if ((int) $contrato->etapa_atual < 6) {
            return response()->json(['error' => 'As carteirinhas devem ser enviadas antes do 1º boleto.'], 422);
        }

        if (!$valorRaw) {
            return response()->json(['error' => 'Informe o valor do boleto.'], 422);
        }
        if (!$vencimento) {
            return response()->json(['error' => 'Informe a data de vencimento.'], 422);
        }

        $valor = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9.,]/', '', $valorRaw));
        if ($valor <= 0) {
            return response()->json(['error' => 'Valor do boleto inválido.'], 422);
        }

        try {
            $vencimento = Carbon::createFromFormat('Y-m-d', $vencimento)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data de vencimento inválida.'], 422);
        }

        $updates = [
            'primeiro_boleto_valor'      => $valor,
            'primeiro_boleto_vencimento' => $vencimento,
        ];
        if ((int) $contrato->etapa_atual === 6) {
            $updates['etapa_atual'] = 7;
        }

        ContratoEmpresarial::where('id', $id)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true]);
    }

    public function salvarFinalizado(Request $request)
    {
        $id   = $request->input('id');
        $data = $request->input('data_finalizado');

        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        if ((int) $contrato->etapa_atual < 7) {
            return response()->json(['error' => 'O 1º boleto deve ser registrado antes de finalizar.'], 422);
        }

        if (!$data) {
            return response()->json(['error' => 'Informe a data de finalização.'], 422);
        }

        try {
            $data = Carbon::createFromFormat('Y-m-d', $data)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data inválida.'], 422);
        }

        $updates = ['data_baixa_finalizado' => $data];

        if ($request->hasFile('finalizado_pdf') && $request->file('finalizado_pdf')->isValid()) {
            $arquivo = $request->file('finalizado_pdf');
            $ext  = strtolower($arquivo->getClientOriginalExtension());
            $mime = $arquivo->getMimeType();
            if ($ext !== 'pdf' || strpos($mime, 'pdf') === false) {
                return response()->json(['error' => 'Apenas arquivos PDF são aceitos.'], 422);
            }
            $cnpjContrato = preg_replace('/\D/', '', $contrato->cnpj);
            $pasta = public_path($cnpjContrato);
            if (!is_dir($pasta)) {
                mkdir($pasta, 0755, true);
            }
            $arquivo->move($pasta, 'finalizado.pdf');
            $updates['finalizado_pdf_path'] = $cnpjContrato . '/finalizado.pdf';
        }

        if ((int) $contrato->etapa_atual === 7) {
            $updates['etapa_atual'] = 8;
        }

        ContratoEmpresarial::where('id', $id)->update($updates);
        Cache::forget('listarContratoEmpresaPendentes');

        return response()->json(['success' => true]);
    }

    public function listarBeneficiarios($id)
    {
        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $beneficiarios = Beneficiario::where('contrato_empresarial_id', $id)->orderBy('id')->get();

        $ageToFaixa = function (int $age): int {
            if ($age <= 18) return 0;
            if ($age <= 23) return 1;
            if ($age <= 28) return 2;
            if ($age <= 33) return 3;
            if ($age <= 38) return 4;
            if ($age <= 43) return 5;
            if ($age <= 48) return 6;
            if ($age <= 53) return 7;
            if ($age <= 58) return 8;
            return 9;
        };

        $faixasTodas    = collect();
        $copart         = $contrato->saude_coparticipacao;
        $valorOdontoUnit = 0.0;

        if ($contrato->plano_saude_id) {
            $cidadeSaude = Cidade::where('nome', $contrato->saude_cidade)
                ->where('uf', $contrato->saude_uf)
                ->first();
            if ($cidadeSaude) {
                $faixasTodas = FaixaEtariaValor::where('plano_id', $contrato->plano_saude_id)
                    ->where('cidade_id', $cidadeSaude->id)
                    ->get()
                    ->keyBy('faixa');
            }
        }

        if ($contrato->plano_odonto_id) {
            $cidadeOdonto = Cidade::where('nome', $contrato->odonto_cidade)
                ->where('uf', $contrato->odonto_uf)
                ->first();
            if ($cidadeOdonto) {
                $ov = OdontoValor::where('plano_id', $contrato->plano_odonto_id)
                    ->where('cidade_id', $cidadeOdonto->id)
                    ->first();
                if ($ov) {
                    $valorOdontoUnit = (float) $ov->valor;
                }
            }
        }

        $resultado = $beneficiarios->map(function ($b) use ($ageToFaixa, $faixasTodas, $copart, $valorOdontoUnit) {
            $faixa    = $ageToFaixa((int) ($b->idade ?? 0));
            $faixaRec = $faixasTodas->get($faixa);
            $acomoda  = strtolower(trim($b->acomodacao ?? ''));
            $isApart  = str_contains($acomoda, 'apart');

            if ($copart === 'com') {
                $colFaixa = $isApart ? 'com_copart_apart' : 'com_copart_enfer';
            } else {
                $colFaixa = $isApart ? 'sem_copart_apart' : 'sem_copart_enfer';
            }

            $valorSaude = $faixaRec ? (float) ($faixaRec->$colFaixa ?? 0) : 0.0;

            return [
                'nome_completo'   => $b->nome_completo,
                'data_nascimento' => $b->data_nascimento
                    ? Carbon::parse($b->data_nascimento)->format('d/m/Y')
                    : null,
                'idade'           => $b->idade,
                'acomodacao'      => $b->acomodacao,
                'valor_saude'     => $valorSaude,
                'valor_odonto'    => $valorOdontoUnit,
            ];
        });

        return response()->json(['data' => $resultado]);
    }

    public function resumoValor($id)
    {
        $contrato = ContratoEmpresarial::find($id);
        if (!$contrato) {
            return response()->json(['error' => 'Contrato não encontrado.'], 404);
        }

        $ageToFaixa = function (int $age): int {
            if ($age <= 18) return 0;
            if ($age <= 23) return 1;
            if ($age <= 28) return 2;
            if ($age <= 33) return 3;
            if ($age <= 38) return 4;
            if ($age <= 43) return 5;
            if ($age <= 48) return 6;
            if ($age <= 53) return 7;
            if ($age <= 58) return 8;
            return 9;
        };

        $resultado = [
            'saude'  => null,
            'odonto' => null,
            'total'  => (float) $contrato->valor_plano,
        ];

        // ── Saúde ──────────────────────────────────────────────────────────────
        if ($contrato->plano_saude_id) {
            $planoSaude  = Plano::find($contrato->plano_saude_id);
            $cidadeSaude = Cidade::where('nome', $contrato->saude_cidade)
                ->where('uf', $contrato->saude_uf)
                ->first();

            $faixasDetalhadas = [];

            if ($cidadeSaude) {
                $acomoda = $contrato->saude_acomodacao;
                $copart  = $contrato->saude_coparticipacao;

                if ($copart === 'com' && $acomoda === 'apartamento')     $col = 'com_copart_apart';
                elseif ($copart === 'com' && $acomoda === 'enfermaria')  $col = 'com_copart_enfer';
                elseif ($copart === 'sem' && $acomoda === 'apartamento') $col = 'sem_copart_apart';
                else                                                      $col = 'sem_copart_enfer';

                $faixaValores = FaixaEtariaValor::where('plano_id', $contrato->plano_saude_id)
                    ->where('cidade_id', $cidadeSaude->id)
                    ->pluck($col, 'faixa')
                    ->toArray();

                $grouped = [];
                foreach (Beneficiario::where('contrato_empresarial_id', $id)->get() as $b) {
                    $f = $ageToFaixa((int) ($b->idade ?? 0));
                    $grouped[$f] = ($grouped[$f] ?? 0) + 1;
                }
                ksort($grouped);

                foreach ($grouped as $faixa => $count) {
                    $valorUnit = (float) ($faixaValores[$faixa] ?? 0);
                    $faixasDetalhadas[] = [
                        'label'      => FaixaEtariaValor::$labels[$faixa] ?? "Faixa $faixa",
                        'count'      => $count,
                        'valor_unit' => $valorUnit,
                        'subtotal'   => round($valorUnit * $count, 2),
                    ];
                }
            }

            $resultado['saude'] = [
                'plano'          => $planoSaude ? $planoSaude->nome : '—',
                'cidade'         => $contrato->saude_cidade ?? '—',
                'uf'             => $contrato->saude_uf ?? '—',
                'acomodacao'     => $contrato->saude_acomodacao ?? '—',
                'coparticipacao' => $contrato->saude_coparticipacao ?? '—',
                'faixas'         => $faixasDetalhadas,
                'total'          => (float) $contrato->valor_plano_saude,
            ];
        }

        // ── Odonto ─────────────────────────────────────────────────────────────
        if ($contrato->plano_odonto_id) {
            $planoOdonto  = Plano::find($contrato->plano_odonto_id);
            $cidadeOdonto = Cidade::where('nome', $contrato->odonto_cidade)
                ->where('uf', $contrato->odonto_uf)
                ->first();

            $valorUnit = 0.0;
            if ($cidadeOdonto) {
                $ov = OdontoValor::where('plano_id', $contrato->plano_odonto_id)
                    ->where('cidade_id', $cidadeOdonto->id)
                    ->first();
                if ($ov) $valorUnit = (float) $ov->valor;
            }

            $resultado['odonto'] = [
                'plano'      => $planoOdonto ? $planoOdonto->nome : '—',
                'cidade'     => $contrato->odonto_cidade ?? '—',
                'uf'         => $contrato->odonto_uf ?? '—',
                'valor_unit' => $valorUnit,
                'vidas'      => (int) $contrato->quantidade_vidas,
                'total'      => (float) $contrato->valor_plano_odonto,
            ];
        }

        return response()->json($resultado);
    }

    public function atualizarStatusPagamento(Request $request)
    {
        if (!$request->id) {
            return response()->json(['error' => 'ID não fornecido'], 400);
        }
        ContratoEmpresarial::where('id', $request->id)
            ->update(['status_pagamento' => $request->status]);
        Cache::forget('listarContratoEmpresaPendentes');
        return response()->json(['success' => true]);
    }

    public function modalEmpresarial(Request $request)
    {

        $id = $request->id;
        $dados = ContratoEmpresarial
            ::where("id", $id)
            ->select("*")
            ->selectRaw("(select name from users where users.id = contrato_empresarial.user_id) as vendedor")
            ->selectRaw("(select nome from planos where planos.id = contrato_empresarial.plano_id) as plano")
            ->selectRaw("(select nome from tabela_origens where tabela_origens.id = contrato_empresarial.tabela_origens_id) as tabela_origem")
            ->first();


        $planos = Plano::all();
        $tabelas_origens = TabelaOrigem::all();

        $corretores = User::all();

        $vendedor = $request->input('vendedor');
        $plano = $request->input('plano');
        $origens = $request->input('origens');

        $razao_social = $request->input('razao_social');
        $cnpj = $request->input('cnpj');
        $vidas = $request->input('vidas');

        $celular = $request->input('celular');
        $email = $request->input('email');

        $responsavel = $request->input('responsavel');
        $cidade = $request->input('cidade');
        $uf = $request->input('uf');
        $plano_contrado = $request->input('plano_contratado');
        $valor_plano = $request->input('valor_plano');


        $codigo_corretora = $request->input('codigo_corretora');
        $codigo_saude = $request->input('codigo_saude');
        $codigo_odonto = $request->input('codigo_odonto');
        $senha_cliente = $request->input('senha_cliente');

        $valor_saude = $request->input('valor_saude');
        $valor_odonto = $request->input('valor_odonto');
        $valor_total = $request->input('valor_total');
        $taxa_adesao = $request->input('taxa_adesao');
        $data_analise = $request->input('data_analise');
        $data_cadastro = $request->input('data_cadastro');

        $valor_boleto = $request->input('valor_boleto');
        $vencimento_boleto = $request->input('vencimento_boleto');
        $data_boleto = $request->input('data_boleto');
        $codigo_externo = $request->input('codigo_externo');

        $texto_empresarial = "";
        if ($plano_contrado == 1) {
            $texto_empresarial = "C/ Copart + Odonto";
        } else if ($plano_contrado == 2) {
            $texto_empresarial = "C/ Copart Sem Odonto";
        } else if ($plano_contrado == 3) {
            $texto_empresarial = "Sem Copart + Odonto";
        } else if ($plano_contrado == 4) {
            $texto_empresarial = "Sem Copart Sem Odonto";
        } else {
            $texto_empresarial = "";
        }



        // Retorne uma view ou JSON conforme sua necessidade
        return view('financeiro.modal-empresarial', compact(
            'dados',
            'vendedor',
            'planos',
            'plano',
            'texto_empresarial',
            'origens',
            'razao_social',
            'tabelas_origens',
            'cnpj',
            'vidas',
            'corretores',
            'celular',
            'email',
            'responsavel',
            'data_cadastro',
            'cidade',
            'valor_plano',
            'codigo_externo',
            'uf',
            'plano_contrado',
            'codigo_corretora',
            'codigo_saude',
            'codigo_odonto',
            'senha_cliente',
            'valor_saude',
            'valor_odonto',
            'valor_total',
            'taxa_adesao',
            'valor_boleto',
            'vencimento_boleto',
            'data_boleto',
            'data_analise',
            'id'
        ));
    }







}
