<?php

namespace App\Http\Controllers;

use App\Models\ContratoEmpresarial;
use App\Models\FolhaMes;
use App\Models\User;
use App\Models\ValoresCorretoresLancados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerenteController extends Controller
{
    public function index()
    {
        return view('gerente.index');
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
//            $folha = new FolhaMes();
//            $folha->mes = $formattedDate;
//            $folha->save();

            $users_select = User::whereHas('contratos', function ($query) {
                $query->where('pago', 0);
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
            where pago = 0 and user_id = {$id}
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

        $usuarios = User::whereHas('contratos', function ($query) use($mes,$ano) {
            $query->where('pago', 0);
            $query->whereMonth('data_baixa_finalizado',$mes);
            $query->whereYear('data_baixa_finalizado',$ano);
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
        contrato_empresarial.pago = 0 AND contrato_empresarial.user_id = {$id}
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
            WHERE contrato_empresarial.pago = 0 AND
            contrato_empresarial.user_id = {$id}
        ");
        return $dados;
    }








}
