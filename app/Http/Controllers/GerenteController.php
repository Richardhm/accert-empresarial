<?php

namespace App\Http\Controllers;

use App\Models\ContratoEmpresarial;
use App\Models\FolhaMes;
use App\Models\FolhaPagamento;
use App\Models\Plano;
use App\Models\User;
use App\Models\Vale;
use App\Models\ValoresCorretoresLancados;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDFFile;
use Illuminate\Support\Str;

class GerenteController extends Controller
{

    /***************Começo INDEX****************************/
    public function index()
    {
        // Dados do mês aberto
        $folha_aberto = $this->getFolhaAberto();
        $mes_aberto = $folha_aberto->first()->mes ?? null;
        $mes = $mes_aberto ? date('m', strtotime($mes_aberto)) : 0;
        $ano = $mes_aberto ? date('Y', strtotime($mes_aberto)) : 0;

        // Dados totais do mês
        $dados_totais = $this->getDadosTotais($mes, $ano);



        // Totais de comissões por tipo (empresarial, individual, coletivo)
        $totais_comissoes = $this->getTotaisComissoes($mes, $ano);


//        dd([$dados_totais,$totais_comissoes]);



        // Dados de usuários aptos a pagar
        $users_apto_apagar = $this->getUsersAptoApagar($mes, $ano);

        // Dados de contratos (geral, individual, coletivo, empresarial)
        $dados_contratos = $this->getDadosContratos($mes, $ano);

        // Dados de comissões a receber e recebidas
        $dados_comissoes = $this->getDadosComissoes();

        // Dados de administradoras
        $administradoras_mes = $this->getAdministradorasMes($mes,$ano);


        // Dados adicionais
        $quantidade_geral = $this->getQuantidadeGeral();
        $total_valor_geral = $this->getTotalValorGeral();
        $quantidade_vidas_geral = $this->getQuantidadeVidasGeral();

        //dd([$dados_totais,$totais_comissoes,$users_apto_apagar,$dados_contratos,$dados_comissoes,$administradoras_mes,$quantidade_geral,$total_valor_geral,$quantidade_vidas_geral]);

        return view('gerente.index', [

            "planos_empresarial" => Plano::all(),
            "status_disabled" => is_null($mes_aberto),
            "quat_comissao_a_receber" => $dados_comissoes->a_receber->quantidade,
            "quat_comissao_recebido" => $dados_comissoes->recebidas->quantidade,
            "valor_quat_comissao_a_receber" => $dados_comissoes->a_receber->valor,
            "valor_quat_comissao_recebido" => $dados_comissoes->recebidas->valor,
            //"datas_select" => $this->getDatasSelect(),
            "total_mes_comissao" => $dados_totais->total_mes,
            "administradoras_mes" => $administradoras_mes,
            "administradoras" => '',
            "users" => $this->getUsers($mes,$ano),
            "users_apto_apagar" => $users_apto_apagar,
            "mes" => $mes,
            "ano" => $ano,

            "quantidade_geral" => $quantidade_geral,
            "total_valor_geral" => $total_valor_geral,
            "quantidade_vidas_geral" => $quantidade_vidas_geral,

            "total_quantidade_recebidos" => $dados_contratos->geral->quantidade_recebidos + 0,
            "total_valor_recebidos" => $dados_contratos->geral->valor_recebidos + 0,
            "quantidade_vidas_recebidas" => $dados_contratos->geral->quantidade_vidas_recebidas + 0,

            "total_quantidade_a_receber" => $dados_contratos->geral->quantidade_a_receber,
            "total_valor_a_receber" => $dados_contratos->geral->valor_a_receber,
            "quantidade_vidas_a_receber" => $dados_contratos->geral->quantidade_vidas_a_receber + 0,

            "qtd_atrasado" => $dados_contratos->geral->qtd_atrasado + 0,
            "qtd_atrasado_valor" => $dados_contratos->geral->qtd_atrasado_valor + 0,
            "qtd_atrasado_quantidade_vidas" => $dados_contratos->geral->qtd_atrasado_quantidade_vidas + 0,

            "qtd_finalizado" => $dados_contratos->geral->qtd_finalizado + 0,
            "quantidade_valor_finalizado" => $dados_contratos->geral->quantidade_valor_finalizado + 0,
            "qtd_finalizado_quantidade_vidas" => $dados_contratos->geral->qtd_finalizado_quantidade_vidas + 0,

            "qtd_cancelado" => $dados_contratos->geral->qtd_cancelado + 0,
            "quantidade_valor_cancelado" => $dados_contratos->geral->quantidade_valor_cancelado + 0,
            "qtd_cancelado_quantidade_vidas" => $dados_contratos->geral->qtd_cancelado_quantidade_vidas + 0,

            'total_empresarial_quantidade' => $totais_comissoes->total_empresarial_quantidade,
            'total_empresarial' => number_format($totais_comissoes->total_empresarial, 2, ",", "."),
            'total_estorno' => 0,
            'total_comissao' => number_format($totais_comissoes->total_empresarial, 2, ",", "."),
            'total_salario' => $dados_totais->total_salario,
            'total_premiacao' => $dados_totais->valor_premiacao,
            'total_desconto' => $dados_totais->valor_desconto,
            'estorno_geral' => $dados_totais->valor_estorno,
            'total_mes' => $dados_totais->total_mes,



            /***************** Empresarial ***********************/
            "quantidade_empresarial_geral" => $dados_contratos->empresarial->quantidade_geral ?? 0,
            "total_valor_geral_empresarial" => $dados_contratos->empresarial->total_valor_geral ?? 0,
            "quantidade_vidas_geral_empresarial" => $dados_contratos->empresarial->quantidade_vidas_geral ?? 0,
            "total_quantidade_recebidos_empresarial" => $dados_contratos->empresarial->quantidade_recebidos ?? 0,
            "total_valor_recebidos_empresarial" => $dados_contratos->empresarial->valor_recebidos ?? 0,
            "quantidade_vidas_recebidas_empresarial" => $dados_contratos->empresarial->quantidade_vidas_recebidas ?? 0,
            "total_quantidade_a_receber_empresarial" => $dados_contratos->empresarial->quantidade_a_receber ?? 0,
            "total_valor_a_receber_empresarial" => $dados_contratos->empresarial->valor_a_receber ?? 0,
            "quantidade_vidas_a_receber_empresarial" => $dados_contratos->empresarial->quantidade_vidas_a_receber ?? 0,
            'qtd_atrasado_empresarial' => $dados_contratos->empresarial->qtd_atrasado ?? 0,
            "qtd_atrasado_valor_empresarial" => $dados_contratos->empresarial->qtd_atrasado_valor ?? 0,
            "qtd_atrasado_quantidade_vidas_empresarial" => $dados_contratos->empresarial->qtd_atrasado_quantidade_vidas ?? 0,
            "qtd_finalizado_empresarial" => $dados_contratos->empresarial->qtd_finalizado ?? 0,
            "quantidade_valor_finalizado_empresarial" => $dados_contratos->empresarial->quantidade_valor_finalizado ?? 0,
            "qtd_finalizado_quantidade_vidas_empresarial" => $dados_contratos->empresarial->qtd_finalizado_quantidade_vidas ?? 0,
            "qtd_cancelado_empresarial" => $dados_contratos->empresarial->qtd_cancelado ?? 0,
            "quantidade_valor_cancelado_empresarial" => $dados_contratos->empresarial->quantidade_valor_cancelado ?? 0,
            "qtd_cancelado_quantidade_vidas_empresarial" => $dados_contratos->empresarial->qtd_cancelado_quantidade_vidas ?? 0,
        ]);





        return view('gerente.index');
    }

    private function getFolhaAberto()
    {
        return DB
            ::table('folha_mes')
            ->where("status", 0)
            ->get();
    }

    private function getDadosTotais($mes, $ano)
    {
        return DB
            ::table('valores_corretores_lancadas')
            ->selectRaw("SUM(valor_comissao) as total_comissao")
            ->selectRaw("SUM(valor_salario) as total_salario")
            ->selectRaw("SUM(valor_premiacao) as valor_premiacao")
            ->selectRaw("SUM(valor_desconto) as valor_desconto")
            ->selectRaw("SUM(valor_estorno) as valor_estorno")
            ->selectRaw("SUM(valor_total) as total_mes")
            ->whereMonth("data", $mes)
            ->whereYear("data", $ano)
            ->first();
    }

    private function getTotaisComissoes($mes, $ano)
    {
        return (object) [
            'total_empresarial_quantidade' => $this->getTotalComissoesPorTipo($mes, $ano)->quantidade,
            'total_sindimaco_quantidade' => $this->getTotalComissoesPorTipo($mes, $ano, 1)->quantidade,
            'total_siaeg_quantidade' => $this->getTotalComissoesPorTipo($mes, $ano, 2)->quantidade,
            'total_sescheg_quantidade' => $this->getTotalComissoesPorTipo($mes, $ano, 3)->quantidade,
            'total_sindipao_quantidade' => $this->getTotalComissoesPorTipo($mes, $ano, 4)->quantidade,
            'total_sincofarma_quantidade' => $this->getTotalComissoesPorTipo($mes, $ano, 5)->quantidade,

            'total_empresarial' => $this->getTotalComissoesPorTipo($mes, $ano)->total,
            'total_sindimaco' => $this->getTotalComissoesPorTipo($mes, $ano, 1)->total,
            'total_siaeg' => $this->getTotalComissoesPorTipo($mes, $ano, 2)->total,
            'total_sescheg' => $this->getTotalComissoesPorTipo($mes, $ano, 3)->total,
            'total_sindipao' => $this->getTotalComissoesPorTipo($mes, $ano, 4)->total,
            'total_sincofarma' => $this->getTotalComissoesPorTipo($mes, $ano, 5)->total,
        ];
    }

    private function getTotalComissoesPorTipo($mes, $ano, $plano_id=null)
    {
        $dados = DB::table('contrato_empresarial as ccl')
            ->selectRaw('SUM(ccl.valor_plano) as total, COUNT(ccl.id) as quantidade')
            ->where('ccl.pago', 1)
            ->whereMonth('ccl.data_baixa_finalizado', $mes)
            ->whereYear('ccl.data_baixa_finalizado', $ano);

        if($plano_id) {
            $dados->where('ccl.plano_id', $plano_id);
        }

        return $dados->first();
    }

    private function getUsersAptoApagar($mes, $ano)
    {
        return DB::table('users')
            ->whereIn('id', function ($query) use ($mes, $ano) {
                $query->select('user_id')
                    ->from('valores_corretores_lancadas')
                    ->whereMonth('data', $mes)
                    ->whereYear('data', $ano);
            })

            ->selectRaw("id as user_id, name as user")
            ->selectRaw("
        (select valor_total
         from  valores_corretores_lancadas
         where valores_corretores_lancadas.user_id = users.id
         and month(data) = ?
         and year(data) = ?
        ) as total
    ", [$mes, $ano]) // Passando os valores de forma segura

            ->orderBy('name')
            ->get();
    }

    private function getDadosContratos($mes, $ano)
    {
        return (object) [
            'geral' => $this->getDadosContratosPorTipo($mes, $ano),

            'empresarial' => $this->getDadosContratosEmpresarial($mes, $ano),
        ];
    }

    private function getDadosContratosPorTipo($mes, $ano, $plano_id = null)
    {
        $query = DB::table('contrato_empresarial');




        if ($plano_id) {
            $query->where('contrato_empresarial.plano_id', $plano_id);
        }


        return (object) [
            'quantidade_geral' => $query->count(),
            'total_valor_geral' => $query->sum('contrato_empresarial.valor_plano'),
            'quantidade_vidas_geral' => $query->sum('contrato_empresarial.quantidade_vidas'),
            'quantidade_recebidos' => $query->where('contrato_empresarial.pago', 1)->count(),
            'valor_recebidos' => $query->where('contrato_empresarial.pago', 1)->sum('contrato_empresarial.valor_plano'),
            'quantidade_vidas_recebidas' => $query->where('contrato_empresarial.pago', 1)->sum('contrato_empresarial.quantidade_vidas'),

            'quantidade_a_receber' => $query->where('contrato_empresarial.pago', 0)->count(),
            'valor_a_receber' => $query->where('contrato_empresarial.pago', 0)->sum('contrato_empresarial.valor_plano'),
            'quantidade_vidas_a_receber' => $query->where('contrato_empresarial.pago', 0)->sum('contrato_empresarial.quantidade_vidas'),
            'qtd_atrasado' => 0,
            'qtd_atrasado_valor' => 0,
            'qtd_atrasado_quantidade_vidas' => 0,
            'qtd_finalizado' => $query->where('contrato_empresarial.pago', 1)->count(),
            'quantidade_valor_finalizado' => $query->where('contrato_empresarial.pago', 1)->sum('contrato_empresarial.valor_plano'),
            'qtd_finalizado_quantidade_vidas' => $query->where('contrato_empresarial.pago', 1)->sum('contrato_empresarial.quantidade_vidas'),
            'qtd_cancelado' => 0,
            'quantidade_valor_cancelado' => 0,
            'qtd_cancelado_quantidade_vidas' => 0,
        ];
    }

    private function getDadosContratosEmpresarial($mes, $ano)
    {

        $dados = DB::table('contrato_empresarial')
            ->where('contrato_empresarial.pago', 1)
            ->whereMonth('data_baixa_finalizado', $mes)
            ->whereYear('data_baixa_finalizado', $ano)

            ->selectRaw('
                COUNT(*) as quantidade_geral,
                if(SUM(valor_plano)>=1,SUM(valor_plano),0) as total_geral,
                if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as quantidade_vidas_geral'
            )->first();
        return $dados;
    }

    private function getDadosComissoes()
    {
        return (object) [
            'a_receber' => DB::table('contrato_empresarial')
                ->where('contrato_empresarial.pago', 0)
                ->where('contrato_empresarial.etapa_atual', '>=', 3)
                ->selectRaw('COUNT(*) as quantidade, SUM(contrato_empresarial.valor_plano) as valor')
                ->first(),
            'recebidas' => DB::table('contrato_empresarial')
                ->where('contrato_empresarial.pago', 1)
                ->selectRaw('COUNT(*) as quantidade, SUM(contrato_empresarial.valor_plano) as valor')
                ->first(),
        ];
    }

    private function getAdministradorasMes($mes, $ano)
    {


        $inicio = Carbon::createFromDate($ano, $mes, 1)->startOfMonth()->toDateString(); // '2025-07-01'
        $fim = Carbon::createFromDate($ano, $mes, 1)->endOfMonth()->toDateString();     // '2025-07-31'

        return DB::table('contrato_empresarial as cc')
            ->selectRaw("SUM(cc.valor_plano) AS total, 'Hapvida' AS administradora")
            ->where('cc.pago', 1)
            ->whereBetween('cc.data_baixa_finalizado', [$inicio, $fim])
            ->get();
    }

    private function getQuantidadeGeral()
    {
        return DB::
            table('contrato_empresarial')

            ->count();
    }

    private function getTotalValorGeral()
    {
        return DB
            ::table('contrato_empresarial')
            ->sum('contrato_empresarial.valor_plano');
    }

    private function getQuantidadeVidasGeral()
    {
        return DB::
            table('contrato_empresarial')
            ->sum('quantidade_vidas');
    }

    private function getDatasSelect()
    {
        return DB::
            table('contrato_empresarial')
            ->where('pago', 1)

            ->groupBy(DB::raw('MONTH(data_baixa_finalizado)'))
            ->pluck('data_baixa_finalizado');
    }

    private function getUsers($mes,$ano)
    {
        return DB::select("
            SELECT users.id AS id, users.name AS name
            FROM contrato_empresarial

            INNER JOIN users ON users.id = contrato_empresarial.user_id
            WHERE pago != 1
            AND contrato_empresarial.etapa_atual >= 3
            AND users.id NOT IN
            (SELECT user_id FROM valores_corretores_lancadas WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
            GROUP BY users.id, users.name
            ORDER BY users.name
        ");
    }

    /*****************FIM INDEX***************************/

    public function comissaoListagemConfirmadasMesEspecifico(Request $request)
    {

        $mes = $request->mes;
        $ano = $request->ano;
        $id = $request->id;
        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->whereYear('data',$ano)->where("user_id",$id);
        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;




        $total_empresarial_quantidade = ContratoEmpresarial
            ::where("pago",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->where("user_id",$id)
            ->count();

        $total_empresarial = ContratoEmpresarial
            ::where("pago",1)
            ->where("user_id",$id)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->selectRaw("if(sum(valor_plano)>0,sum(valor_plano),0) as total_coletivo")->first()->total_coletivo;

        if($valores->count() != 0) {
            $dados = $valores->first();
            $total = number_format($dados->valor_total,2,",",".");
            $salario = number_format($dados->valor_salario,2,",",".");
            $premiacao = number_format($dados->valor_premiacao,2,",",".");
            $comissao = number_format($dados->valor_comissao,2,",",".");
            $desconto = number_format($dados->valor_desconto,2,",",".");
            $estorno = number_format($dados->valor_estorno,2,",",".");
        }


        $ids_confirmados = ContratoEmpresarial
            ::where("pago",1)
            ->where("user_id",$id)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)

            ->selectRaw("GROUP_CONCAT(id) as ids")
            ->first()
            ->ids;





        $valor_empresarial_a_receber = DB::select("
            SELECT
            COUNT(*) AS total
            FROM contrato_empresarial
            WHERE contrato_empresarial.pago = 0
            AND contrato_empresarial.etapa_atual >= 3
            AND contrato_empresarial.user_id = {$id}
        ")[0]->total;



        return [

            "valor_empresarial_a_receber" => $valor_empresarial_a_receber,

            "total_empresarial_quantidade" => $total_empresarial_quantidade,

            "total_empresarial" => number_format($total_empresarial,2,",","."),

            "id_confirmados" => $ids_confirmados,
            "salario" => $salario,
            "comissao" => $comissao,
            "premiacao" => $premiacao,
            "desconto" => $desconto,
            "total" => $total,
            "estorno" => $estorno
        ];
    }



    public function criarPDFUser(Request $request)
    {
        $coletivo_valores = 0;
        $empresar_valores = isset($request->empresarial_valores) && count($request->empresarial_valores) > 0 ? implode(",",$request->empresarial_valores) : 'null';
        $mes = $request->mes;
        $id = $request->user_id;
        $ano = $request->ano;
        $meses = [
            '01'=>"Janeiro",
            '02'=>"Fevereiro",
            '03'=>"Março",
            '04'=>"Abril",
            '05'=>"Maio",
            '06'=>"Junho",
            '07'=>"Julho",
            '08'=>"Agosto",
            '09'=>"Setembro",
            '10'=>"Outubro",
            '11'=>"Novembro",
            '12'=>"Dezembro"
        ];


        $mes_folha = $meses[$mes];
        $user = User::where("id",$request->user_id)->first()->name;
        $dados    = ValoresCorretoresLancados::whereMonth("data",$mes)->whereYear("data",$ano)->where("user_id",$request->user_id)->first();
        $comissao = $dados ? $dados->valor_comissao  : 0;
        $salario  = $dados ? $dados->valor_salario   : 0;
        $premiacao= $dados ? $dados->valor_premiacao : 0;
        $total    = $dados ? $dados->valor_total     : 0;
        $desconto = $dados ? $dados->valor_desconto  : 0;



        $logo = "";



        $empresarial = DB::select("
        SELECT
            contrato_empresarial.razao_social as cliente,
            contrato_empresarial.codigo_externo as codigo_externo,
            DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') AS data,
            '0' as desconto,
            contrato_empresarial.valor_plano as valor_plano_contratado,
            contrato_empresarial.valor_pagar AS comissao,
            '1' as parcela
            FROM contrato_empresarial

            WHERE
            contrato_empresarial.pago = 1 AND
            contrato_empresarial.user_id = {$id} AND month(contrato_empresarial.data_baixa_finalizado) = {$mes} AND YEAR(contrato_empresarial.data_baixa_finalizado) = {$ano} ORDER BY contrato_empresarial.id
        ");








        $primeiroDia = date('d/m/Y', strtotime($ano.'-' . $mes . '-01'));
        $ultimoDia = date('t/m/Y', strtotime($ano.'-' . $mes . '-01'));


        $boolean_empresarial = 1;

        $estorno = 0;



        $total_empresarial = DB::select("SELECT	SUM((contrato_empresarial.valor_pagar)) AS total FROM contrato_empresarial
        WHERE contrato_empresarial.pago = 1 AND user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano}
        ")[0]->total;

        $total    = floatval($total_empresarial);
        $comissao = floatval($total_empresarial);
        $desconto = 0;

        // Vales do vendedor no mês
        $total_vale = Vale::where('user_id', $id)
            ->whereMonth('mes', $mes)
            ->whereYear('mes', $ano)
            ->sum('valor');

        $vales = Vale::where('user_id', $id)
            ->whereMonth('mes', $mes)
            ->whereYear('mes', $ano)
            ->get();

        $total_liquido = max(0, $total - $total_vale);

        $pdf = PDFFile::loadView('gerente.pdf-folha',[

            "ano" => $ano,

            "empresarial" => $empresarial,
            "meses" => $mes_folha,
            "salario" => $salario,
            "premiacao" => $premiacao,
            "comissao" => $comissao,
            "total" => $total,
            "total_vale" => $total_vale,
            "total_liquido" => $total_liquido,
            "vales" => $vales,
            "logo" => $logo,
            "primeiro_dia" => $primeiroDia,
            "ultimo_dia" => $ultimoDia,
            "user" => $user,
            "desconto" => $desconto,
            "estorno" => $estorno,

            "tipo" => $request->tipo,

            "boolean_empresarial" => $boolean_empresarial


        ]);

        $nome = Str::slug($user,"_");
        $mes_folha_nome = Str::slug($mes_folha);


        $nome_pdf = "folha_" . mb_convert_case($nome, MB_CASE_LOWER, "UTF-8") . "_" . $mes_folha_nome . "_" . date('d') . "_" . date('m') . "_" . date('s') . ".pdf";
        $response = $pdf->stream($nome_pdf, ['Attachment' => false]);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $nome_pdf . '"');
        return $response;

    }

    public function exportarExcel(Request $request)
    {
        $mes = intval($request->mes);
        $ano = intval($request->ano);

        $dados = DB::select("
            SELECT
                ce.razao_social,
                ce.cidade,
                ce.valor_plano    AS valor,
                u.name            AS vendedor,
                ce.valor_pagar    AS comissao_vendedor
            FROM contrato_empresarial ce
            INNER JOIN users u ON u.id = ce.user_id
            WHERE
                ce.pago = 1 AND
                MONTH(ce.data_baixa_finalizado) = {$mes} AND
                YEAR(ce.data_baixa_finalizado)  = {$ano}
            ORDER BY u.name, ce.razao_social
        ");

        $meses    = ['01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril',
                     '05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto',
                     '09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro'];
        $mes_nome = $meses[str_pad($mes, 2, '0', STR_PAD_LEFT)] ?? $mes;
        $esc      = fn(string $v): string => htmlspecialchars($v, ENT_XML1, 'UTF-8');

        // ── Shared strings ───────────────────────────────────────────────────
        $strings  = [];
        $si       = 0;
        $addStr   = function (string $v) use (&$strings, &$si): int {
            if (!array_key_exists($v, $strings)) { $strings[$v] = $si++; }
            return $strings[$v];
        };

        // ── Linhas da planilha ────────────────────────────────────────────────
        $cols    = ['A','B','C','D','E'];
        $headers = ['Razão Social','Cidade','Valor (R$)','Vendedor','Comissão Vendedor (R$)'];

        $sheetRows = '';
        $r = 1;

        // cabeçalho (estilo 1 = header azul)
        $sheetRows .= '<row r="' . $r . '">';
        foreach ($headers as $i => $h) {
            $sheetRows .= '<c r="' . $cols[$i] . $r . '" s="1" t="s"><v>' . $addStr($h) . '</v></c>';
        }
        $sheetRows .= '</row>';
        $r++;

        // dados
        foreach ($dados as $row) {
            $sheetRows .= '<row r="' . $r . '">';
            $sheetRows .= '<c r="A' . $r . '" t="s"><v>' . $addStr($row->razao_social ?? '')    . '</v></c>';
            $sheetRows .= '<c r="B' . $r . '" t="s"><v>' . $addStr($row->cidade ?? '')          . '</v></c>';
            $sheetRows .= '<c r="C' . $r . '" s="2"><v>' . number_format((float)$row->valor, 2, '.', '')             . '</v></c>';
            $sheetRows .= '<c r="D' . $r . '" t="s"><v>' . $addStr($row->vendedor ?? '')        . '</v></c>';
            $sheetRows .= '<c r="E' . $r . '" s="2"><v>' . number_format((float)$row->comissao_vendedor, 2, '.', '') . '</v></c>';
            $sheetRows .= '</row>';
            $r++;
        }

        // ── Shared strings XML ────────────────────────────────────────────────
        $sortedSI = array_flip($strings); ksort($sortedSI);
        $ssXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' count="' . $si . '" uniqueCount="' . $si . '">';
        foreach ($sortedSI as $val) {
            $ssXml .= '<si><t xml:space="preserve">' . $esc($val) . '</t></si>';
        }
        $ssXml .= '</sst>';

        // ── Partes fixas do pacote ────────────────────────────────────────────
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
            . '</Types>';

        $relsRoot = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';

        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . $esc('Empresas ' . $mes_nome . ' ' . $ano) . '" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';

        $workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="#,##0.00"/></numFmts>'
            . '<fonts count="2">'
            .   '<font><sz val="11"/><name val="Calibri"/></font>'
            .   '<font><sz val="11"/><b/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            .   '<fill><patternFill patternType="none"/></fill>'
            .   '<fill><patternFill patternType="gray125"/></fill>'
            .   '<fill><patternFill patternType="solid"><fgColor rgb="FF4472C4"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="3">'
            .   '<xf numFmtId="0"   fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .   '<xf numFmtId="0"   fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>'
            .   '<xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';

        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetData>' . $sheetRows . '</sheetData>'
            . '</worksheet>';

        // ── Montar ZIP (ZipArchive nativo do PHP) ─────────────────────────────
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml',        $contentTypes);
        $zip->addFromString('_rels/.rels',                $relsRoot);
        $zip->addFromString('xl/workbook.xml',            $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRels);
        $zip->addFromString('xl/styles.xml',              $styles);
        $zip->addFromString('xl/sharedStrings.xml',       $ssXml);
        $zip->addFromString('xl/worksheets/sheet1.xml',   $sheet);
        $zip->close();

        $content  = file_get_contents($tmp);
        unlink($tmp);

        $filename = 'empresas_' . Str::slug($mes_nome) . '_' . $ano . '.xlsx';

        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($content))
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
    }

    public function previsualizarFolha(Request $request)
    {
        $id  = intval($request->user_id);
        $mes = intval($request->mes);
        $ano = intval($request->ano);

        $empresarial = DB::select("
            SELECT
                contrato_empresarial.razao_social      AS cliente,
                contrato_empresarial.codigo_externo    AS codigo_externo,
                DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') AS data,
                contrato_empresarial.valor_plano       AS valor_plano_contratado,
                contrato_empresarial.valor_pagar       AS comissao
            FROM contrato_empresarial
            WHERE
                contrato_empresarial.pago = 1 AND
                contrato_empresarial.user_id = {$id} AND
                MONTH(contrato_empresarial.data_baixa_finalizado) = {$mes} AND
                YEAR(contrato_empresarial.data_baixa_finalizado)  = {$ano}
            ORDER BY contrato_empresarial.id
        ");

        $total_comissao = array_sum(array_column(array_map(fn($e) => (array)$e, $empresarial), 'comissao'));

        $total_vale = Vale::where('user_id', $id)
            ->whereMonth('mes', $mes)
            ->whereYear('mes', $ano)
            ->sum('valor');

        $total_liquido = max(0, $total_comissao - $total_vale);

        $empresas = array_map(function ($e) {
            return [
                'cliente'        => mb_convert_case($e->cliente, MB_CASE_UPPER, 'UTF-8'),
                'codigo_externo' => $e->codigo_externo,
                'data'           => $e->data,
                'valor_plano'    => number_format($e->valor_plano_contratado, 2, ',', '.'),
                'comissao'       => number_format($e->comissao, 2, ',', '.'),
            ];
        }, $empresarial);

        return response()->json([
            'empresas'       => $empresas,
            'total_comissao' => number_format($total_comissao, 2, ',', '.'),
            'total_vale'     => number_format($total_vale,     2, ',', '.'),
            'total_liquido'  => number_format($total_liquido,  2, ',', '.'),
        ]);
    }

    public function mudarComissaoCorretor(Request $request)
    {
        $valor_plano = floatval($request->valor_plano);
        $porcentagem = floatval($request->valor);
        $resultado = ($valor_plano * $porcentagem) / 100;
        $id = $request->id;

        $alt = ContratoEmpresarial::where("id",$request->default_corretor)->first();
        $alt->valor_pagar = $resultado;
        if($alt->save()) {
           return [
                "valor" => number_format($resultado,2,",","."),
                "porcentagem" => $request->valor
            ];
        } else {
            return "error";
        }




    }











    public function mudarStatusParaNaoPago(Request $request)
    {

        $ca = ContratoEmpresarial::where("id", $request->id)->first();
        $ca->pago = 0;
        $ca->data_baixa_finalizado = null;
        $ca->save();

        $valoresCorretores = ValoresCorretoresLancados
            ::where("user_id", $request->user_id)
            ->whereMonth("data", $request->mes)
            ->whereYear("data", $request->ano)

            ->first();

        if ($valoresCorretores) {
            $converter = fn($valor) => (float) $valor;
            $valoresCorretores->valor_comissao -= $converter($request->comissao);
            //$valoresCorretores->valor_salario -= $converter($request->salario);
            //$valoresCorretores->valor_premiacao -= $converter($request->premiacao);
            $valoresCorretores->valor_desconto -= $converter($request->desconto);
            //$valoresCorretores->valor_estorno -= $converter($request->estorno);

            $valoresCorretores->valor_total =
                ($valoresCorretores->valor_comissao +
                    $valoresCorretores->valor_salario);

            // Remove registro se todos valores forem zerados
            if ($valoresCorretores->valor_total == 0 &&
                $valoresCorretores->valor_comissao == 0 &&
                $valoresCorretores->valor_salario == 0 &&
                $valoresCorretores->valor_premiacao == 0) {
                $valoresCorretores->delete();
            } else {
                $valoresCorretores->save();
            }


        }

        return $this->infoCorretorUp($request->user_id,$request->mes,$request->ano);
    }






    public function cadastrarFolhaMes(Request $request)
    {

        $date = \DateTime::createFromFormat('Y-m-d', $request->data);
        $formattedDate = $date->format('Y-m-d');

        $mes = date("m",strtotime($formattedDate));
        $ano = date("Y",strtotime($formattedDate));

        $folha = FolhaMes::whereMonth("mes",$mes)->whereYear("mes",$ano)->count();
        if($folha == 0) {
//            return $formattedDate;
            $folha = new FolhaMes();
            $folha->mes = $formattedDate;
            $folha->status = 0;
            $folha->save();

            $users_select = User::whereHas('contratos', function ($query) {
                $query->where('pago', 0)->where('etapa_atual', '>=', 3);
            })->get(['id', 'name']);

            return [
                "resposta" => "cadastrado",
                "users_select" => $users_select
            ];

        }
    }

    public function infoCorretor(Request $request)
    {
        $premiacao_cad = str_replace([".",","],["","."], $request->premiacao);
        $salario_cad = str_replace([".",","],["","."], $request->salario);
        $total_cad   = str_replace([".",","],["","."], $request->total);



        ValoresCorretoresLancados
            ::where("user_id",$request->user_id)
            ->whereMonth("data",$request->mes)
            ->whereYear("data",$request->ano)
            ->update(["valor_premiacao"=>$premiacao_cad,"valor_total"=>$total_cad,"valor_salario"=>$salario_cad]);

        $id = $request->id;
        $mes = $request->mes;
        $ano = $request->ano;
        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;



        $valor_empresarial_a_receber = DB::select("
            SELECT
            COUNT(*) AS total
            FROM contrato_empresarial
            WHERE pago = 0 AND etapa_atual >= 3 AND user_id = {$id}
        ")[0]->total;



        $total_empresarial_quantidade = ContratoEmpresarial
            ::where("pago",1)
            ->where("user_id",$id)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->count();


        $total_empresarial = DB::select("
            SELECT SUM(valor_pagar) as total_empresarial_valor from contrato_empresarial
            WHERE pago = 1 and month(data_baixa_finalizado) = {$mes}
                AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id}

        ")[0]->total_empresarial_valor;



//        $usuarios = DB::select("
//            SELECT users.id AS id, users.name AS name
//            FROM comissoes_corretores_lancadas
//                     INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
//                     INNER JOIN users ON users.id = comissoes.user_id
//            WHERE (status_financeiro = 1 or status_gerente = 1)
//              and finalizado != 1 and valor != 0 and users.id NOT IN (SELECT user_id FROM valores_corretores_lancadas
//              WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
//            GROUP BY users.id, users.name
//            ORDER BY users.name;
//         ");

        $usuarios = User::whereHas('contratos', function ($query) {
            $query->where('pago', 0)->where('etapa_atual', '>=', 3);
        })->get(['id', 'name']);


        return [
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_empresarial" => number_format($total_empresarial,2,",","."),
            "total_comissao" =>  number_format($comissao,2,",","."),
            "total_salario" =>  number_format($salario,2,",","."),
            "total_premiacao" =>  number_format($premiacao,2,",","."),
            "total" =>  number_format($total,2,",","."),
//            "view" => view('gerente.list-users-pdf',[
//                "users" => $users
//            ])->render(),
            "usuarios" => $usuarios,
            "valor_empresarial_a_receber" => $valor_empresarial_a_receber
        ];


    }

    public function comissaoMesAtual(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
        SELECT
        'Hapvida' AS administradora,
        contrato_empresarial.created_at AS data_criacao,
        contrato_empresarial.codigo_externo AS orcamento,
        contrato_empresarial.quantidade_vidas AS quantidade_vidas,
        (SELECT plano_id FROM planos WHERE contrato_empresarial.plano_id = planos.id) AS plano,
        contrato_empresarial.created_at as data_antecipacao,
        contrato_empresarial.responsavel as cliente,
        DATE_FORMAT(contrato_empresarial.vencimento_boleto,'%d/%m/%Y') AS data,
        DATE_FORMAT(contrato_empresarial.data_boleto,'%d/%m/%Y') AS data_baixa_gerente,
        valor_plano AS valor_plano_contratado,
       '0' AS desconto,
       '30' AS comissao_esperada,
       contrato_empresarial.valor_pagar AS comissao_recebida,
       contrato_empresarial.id,
       contrato_empresarial.id,
       '1' as parcela,
       '30' AS porcentagem_parcela_corretor,
        '1' AS id_porcentagem_parcela_corretor,
        '30' AS porcentagem_paga,
        '1' AS contrato_id
        FROM contrato_empresarial
        WHERE
        contrato_empresarial.pago = 0
        AND contrato_empresarial.etapa_atual >= 3
        AND contrato_empresarial.user_id = {$id}
        ");

        return $dados;
    }

    public function empresarialAReceber(Request $request)
    {

        $id = $request->id;
        $dados = DB::select("
        SELECT
            contrato_empresarial.id,
            '1' as parcela,
            contrato_empresarial.created_at as data_criacao,
            contrato_empresarial.created_at as data_baixa,
            contrato_empresarial.codigo_externo AS orcamento,
            contrato_empresarial.valor_plano AS valor_plano_contratado,
            '0' as desconto,
            DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') AS data,
            contrato_empresarial.valor_pagar as valor,
            contrato_empresarial.quantidade_vidas AS quantidade_vidas,
            contrato_empresarial.plano_id AS plano,
            '30' AS  porcentagem_parcela_corretor,
            contrato_empresarial.id as contrato_id,
            contrato_empresarial.razao_social AS cliente,
            'Hapvida' AS administradora
            FROM contrato_empresarial
            WHERE contrato_empresarial.pago = 0
            AND contrato_empresarial.etapa_atual >= 3
            AND contrato_empresarial.user_id = {$id}
        ");
        return $dados;
    }


    public function aptarPagamento(Request $request)
    {
        $id_comissao = $request->id;
        $user_id = $request->user_id;
        $mes = $request->mes;
        $ano = $request->ano;
        $data_comissao = date($ano."-".$mes."-01");
        // Atualiza a comissão do corretor

        $co = ContratoEmpresarial::where("id", $request->id)->first();
        $co->pago = 1;

        $co->data_baixa_finalizado = $data_comissao;
        if(!$co->save()) return "error";

        $va = ValoresCorretoresLancados
            ::where("user_id", $request->user_id)
            ->whereMonth('data', $request->mes)
            ->whereYear('data', $request->ano)

            ->first();

        if (!$va) {

            $converter = fn($valor) => (float) str_replace(['.', ','], ['', '.'], $valor);
            $va = new ValoresCorretoresLancados();
            $va->user_id = $user_id;

            $va->valor_comissao = $request->comissao;
            $va->valor_salario = $converter($request->salario);
            $va->valor_premiacao = 0;
            $va->valor_desconto = 0;
            $va->valor_estorno = 0;
            $va->data = $data_comissao;
            $va->valor_total =
                ($va->valor_comissao +
                    $va->valor_salario +
                    $va->valor_premiacao) -
                ($va->valor_desconto +
                    $va->valor_estorno);
            $va->save();
            $id_folha_mes = FolhaMes::whereMonth("mes",$mes)->whereYear("mes",$ano)->first()->id;
            // Cria registro na folha de pagamento
            $folha = new FolhaPagamento();
            $folha->folha_mes_id = $id_folha_mes; // Substitua pelo id correto
            $folha->valores_corretores_lancados_id = $va->id;
            $folha->save();
        } else {

            $alt = ValoresCorretoresLancados
                ::where("user_id", $request->user_id)
                ->whereMonth('data', $request->mes)
                ->whereYear('data', $request->ano)
                ->first();
            $converter = fn($valor) => (float) str_replace(['.', ','], ['', '.'], $valor);
            $alt->valor_comissao += $request->comissao;
            $alt->valor_salario += $converter($request->salario);
            $alt->valor_premiacao += 0;
            $alt->valor_desconto += 0;
            //$alt->valor_estorno += $converter($request->estorno);

            $alt->valor_total =
                ($alt->valor_comissao +
                    $alt->valor_salario +
                    $alt->valor_premiacao);

            $alt->save();


        }

        return $this->infoCorretorUp($user_id,$mes,$ano);
    }

    public function infoCorretorUp($id,$mes,$ano)
    {

        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;

        /******Geral ********************************/
        $total_empresarial_quantidade_geral = ContratoEmpresarial
            ::where("pago",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->count();

        $total_empresarial_geral = DB::select("
            SELECT sum(valor_pagar) AS total_empresarial_valor from contrato_empresarial WHERE month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
        ")[0]->total_empresarial_valor;

        $valores_geral = DB::select("
            SELECT
	            SUM(valor_comissao) AS comissao,
	            SUM(valor_salario) AS salario,
	            SUM(valor_premiacao) AS premiacao,
	            SUM(valor_estorno) AS estorno,
	            SUM(valor_desconto) AS desconto,
	            SUM(valor_total) AS total
                FROM valores_corretores_lancadas WHERE MONTH(DATA) = {$mes} AND YEAR(DATA) = {$ano}
        ")[0];

        /*******Fim Geral***************************/







        $valor_empresarial_a_receber = DB::select("
            SELECT
            COUNT(*) AS total
            FROM contrato_empresarial
            WHERE contrato_empresarial.pago = 0
            AND contrato_empresarial.etapa_atual >= 3
            AND contrato_empresarial.user_id = {$id}
        ")[0]->total;

        $total_empresarial_quantidade = ContratoEmpresarial
            ::where("pago",1)
            ->where("user_id",$id)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereYear('data_baixa_finalizado',$ano)
            ->count();

        $total_empresarial = DB::select("
            SELECT sum(valor_pagar) AS total_empresarial_valor from contrato_empresarial WHERE month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id} AND pago = 1
        ")[0]->total_empresarial_valor;








            $comissao = $total_empresarial;


        $ids_confirmados = ContratoEmpresarial
            ::where("pago",1)
            ->where("user_id",$id)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->selectRaw("GROUP_CONCAT(id) as ids")
            ->first()
            ->ids;





        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->whereYear("data",$ano)->where("user_id",$id);


        if($valores->count() == 1) {
            $va = $valores->first();
            $salario = $va->valor_salario;
            $premiacao = $va->valor_premiacao;
            $comissao = $va->valor_comissao;
            $desconto = $va->valor_desconto;
            $total = $va->valor_total;
            $estorno = $va->valor_estorno;
        } else {


            $total = $comissao;
        }



        $users = DB::select("
            select name as user,users.id as user_id,valor_total as total from
            valores_corretores_lancadas
            inner join users on users.id = valores_corretores_lancadas.user_id
            where MONTH(data) = {$mes} AND YEAR(data) = {$ano} order by users.name
        ");

        $usuarios = User::whereHas('contratos', function ($query) {
            $query->where('pago', 0)->where('etapa_atual', '>=', 3);
        })->get(['id', 'name']);



        return [

            "total_empresarial_quantidade" => $total_empresarial_quantidade,

            "total_empresarial" => number_format($total_empresarial,2,",","."),
            "total_comissao" =>  number_format($comissao,2,",","."),
            "total_salario" =>  number_format($salario,2,",","."),
            "total_premiacao" =>  number_format($premiacao,2,",","."),
            "id_confirmados" => $ids_confirmados,
            "desconto" =>  number_format($desconto,2,",","."),
            "total" =>  number_format($total,2,",","."),
            "estorno" => number_format($estorno,2,",","."),
            "view" => view('gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
            "usuarios" => $usuarios,
            "valores_geral" => $valores_geral,
            "valor_empresarial_a_receber" => $valor_empresarial_a_receber,
            "total_empresarial_quantidade_geral" => $total_empresarial_quantidade_geral,
            "total_empresarial_geral" => $total_empresarial_geral
        ];


    }


    // ─── Vale ──────────────────────────────────────────────────────────
    public function salvarVale(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'valor'   => 'required|numeric|min:0.01',
        ]);

        $folha = FolhaMes::where('status', 0)->first();
        if (!$folha) {
            return response()->json(['error' => 'Nenhuma folha aberta.'], 422);
        }

        $mes = $folha->mes; // ex: 2025-07-01

        Vale::create([
            'user_id' => $request->user_id,
            'valor'   => $request->valor,
            'mes'     => $mes,
        ]);

        return response()->json(['success' => true]);
    }

    public function listarValesMes(Request $request)
    {
        $folha = FolhaMes::where('status', 0)->first();
        if (!$folha) {
            return response()->json([]);
        }
        $mes = $folha->mes;

        $vales = Vale::with('user:id,name')
            ->whereMonth('mes', date('m', strtotime($mes)))
            ->whereYear('mes',  date('Y', strtotime($mes)))
            ->get()
            ->map(fn($v) => [
                'id'         => $v->id,
                'user_id'    => $v->user_id,
                'nome'       => $v->user->name ?? '-',
                'valor'      => $v->valor,
                'valor_fmt'  => number_format($v->valor, 2, ',', '.'),
            ]);

        return response()->json($vales);
    }

    public function excluirVale(Request $request)
    {
        Vale::findOrFail($request->id)->delete();
        return response()->json(['success' => true]);
    }

    // ─── Fechar Mês ────────────────────────────────────────────────────
    public function resumoFechamento()
    {
        $folha = FolhaMes::where('status', 0)->first();
        if (!$folha) {
            return response()->json(['error' => 'Nenhuma folha aberta.'], 422);
        }

        $mes = date('m', strtotime($folha->mes));
        $ano = date('Y', strtotime($folha->mes));

        $vendedores = DB::table('valores_corretores_lancadas as vcl')
            ->join('users', 'users.id', '=', 'vcl.user_id')
            ->selectRaw('users.id as user_id, users.name as nome,
                         vcl.valor_comissao as comissao,
                         vcl.valor_salario as salario,
                         vcl.valor_premiacao as premiacao,
                         vcl.valor_desconto as desconto,
                         vcl.valor_total as total')
            ->whereMonth('vcl.data', $mes)
            ->whereYear('vcl.data', $ano)
            ->orderBy('users.name')
            ->get();

        // Soma os vales do mês para cada vendedor
        $valesPorUser = Vale::whereMonth('mes', $mes)
            ->whereYear('mes', $ano)
            ->selectRaw('user_id, SUM(valor) as total_vale')
            ->groupBy('user_id')
            ->pluck('total_vale', 'user_id');

        $resumo = $vendedores->map(function ($v) use ($valesPorUser) {
            $vale = $valesPorUser[$v->user_id] ?? 0;
            return [
                'nome'      => $v->nome,
                'comissao'  => number_format($v->comissao, 2, ',', '.'),
                'salario'   => number_format($v->salario,  2, ',', '.'),
                'premiacao' => number_format($v->premiacao,2, ',', '.'),
                'desconto'  => number_format($v->desconto, 2, ',', '.'),
                'vale'      => number_format($vale,         2, ',', '.'),
                'total'     => number_format(max(0, $v->total - $vale), 2, ',', '.'),
            ];
        });

        $mesNomes = ['01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril',
                     '05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto',
                     '09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro'];

        return response()->json([
            'resumo'    => $resumo,
            'mes_label' => ($mesNomes[str_pad($mes,2,'0',STR_PAD_LEFT)] ?? $mes) . '/' . $ano,
        ]);
    }

    public function fecharMes()
    {
        $folha = FolhaMes::where('status', 0)->first();
        if (!$folha) {
            return response()->json(['error' => 'Nenhuma folha aberta.'], 422);
        }
        $folha->status = 1;
        $folha->save();
        return response()->json(['success' => true]);
    }

    public function comissaoListagemConfirmadasEmpresarial(Request $request)
    {
        $id = $request->id;
        if($request->mes) {
            $mes = $request->mes;
            $ano = $request->ano;
            $dados = DB::select("
            SELECT
            'Hapvida' AS administradora,
            DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') AS created_at,
            contrato_empresarial.codigo_externo AS codigo,
            contrato_empresarial.codigo_externo AS codigo_externo,
            contrato_empresarial.razao_social AS cliente,
            (SELECT nome FROM planos WHERE planos.id = contrato_empresarial.plano_id) AS plano_nome,
            (SELECT name FROM users WHERE users.id = contrato_empresarial.user_id) AS corretor,
            '1' AS parcela,
            contrato_empresarial.valor_plano AS valor_plano,
            DATE_FORMAT(contrato_empresarial.vencimento_boleto,'%d/%m/%Y') AS vencimento,
            DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') AS data_baixa,
            COALESCE((SELECT valor FROM comissao WHERE comissao.user_id = contrato_empresarial.user_id LIMIT 1), 0) AS porcentagem,
            contrato_empresarial.valor_pagar AS valor,
            contrato_empresarial.plano_id AS plano,
            contrato_empresarial.quantidade_vidas AS quantidade_vidas,
            '0' AS desconto,
            contrato_empresarial.id,
            contrato_empresarial.id AS contrato_id
        FROM contrato_empresarial
        WHERE
            contrato_empresarial.user_id = {$id}
            AND MONTH(data_baixa_finalizado) = {$mes}
            AND YEAR(data_baixa_finalizado)  = {$ano}
            AND contrato_empresarial.pago = 1
        ORDER BY contrato_empresarial.id
        ");
        } else {
            $dados = DB::connection('tenant')->select("
            select
            (SELECT nome FROM grupoamerica.administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at,
    contrato_empresarial.codigo_externo as codigo,
    (contrato_empresarial.razao_social) as cliente,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.corretora_id = {$corretora_id} AND
                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,
    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,
    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    CASE
        WHEN contratos.desconto_corretor IS NOT NULL THEN contratos.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
        END AS desconto,
    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id
        ");
        }
        return $dados;
    }










}
