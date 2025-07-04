<?php

namespace App\Http\Controllers;

use App\Models\Comissao;
use App\Models\ContratoEmpresarial;
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
        return view('financeiro.index');
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
        ContratoEmpresarial::query()->create($dados);
        return redirect('/financeiro');
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

                        'contrato_empresarial.senha_cliente as senha_cliente',

                        'contrato_empresarial.pago as status',
                        DB::raw("DATE_FORMAT(contrato_empresarial.vencimento_boleto,'%d/%m/%Y') as vencimento"),

                        'contrato_empresarial.data_boleto as data_boleto',
                        'tabela_origens.nome as tabela_origens',
                        'contrato_empresarial.responsavel as responsavel',
                        'contrato_empresarial.plano_contrado as plano_contrado'
                    )

                    ->join('users', 'users.id', '=', 'contrato_empresarial.user_id')

                    ->join('planos', 'planos.id', '=', 'contrato_empresarial.plano_id')
                    ->join('tabela_origens', 'tabela_origens.id', '=', 'contrato_empresarial.tabela_origens_id');







                return $query->get();
            });

            return response()->json(['data' => $resultado]);
        }
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

        $codigo_corretora = $request->input('codigo_corretora');
        $codigo_saude = $request->input('codigo_saude');
        $codigo_odonto = $request->input('codigo_odonto');
        $senha_cliente = $request->input('senha_cliente');

        $valor_saude = $request->input('valor_saude');
        $valor_odonto = $request->input('valor_odonto');
        $valor_total = $request->input('valor_total');
        $taxa_adesao = $request->input('taxa_adesao');
        $data_analise = $request->input('data_analise');


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
            'cidade',
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
