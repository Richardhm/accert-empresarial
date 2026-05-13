<html>
    <head>
        <title></title>
        <style>
            * {margin:0;padding:0;font-size:1em;}
            td {font-size: 0.7em;}
        </style>
    </head>
    <body>
       <div style="width:95%;margin:0 auto;padding:5px 0;">
            <p style="font-size:0.75em;">Accert Empresarial</p>
       </div>

       <div style="border-top:1px solid black;display:block;width:95%;left:20px;position:absolute;height:70px;padding:10px 0;">
            <div style="width:90%;position:relative;left:0;float:left;">
                <p>COMPOSIÇÃO SALARIAL</p>
                <p>Vendedor: {{$user}}</p>
                <p>Referência: {{$meses}} / {{$ano}}</p>
            </div>

            @if($logo && $logo != '')

                <div style="width:10%;position:relative;right:0;top:0;margin-bottom:5px;float:right;background-color:#A9A9A9;padding:2px;border-radius:5px;">
                    <img src="{{$logo}}" alt="Logo" id="Logo" style="width:100%;height:100%;" />
                </div>

            @endif

       </div>


       <div style="clear: both;"></div>


       <div style="display:block;height:80px;width:95%;left:20px;position:relative;border-top:1px solid black;padding:2px;">
            <div>
                <span style="width:89%;left:0;float:left;">1 Salário Mês</span>
                <span style="width:11%;right:0;top:0;float:right;text-align:right;">
                    <div style="width:75%;float:right;text-align:right;">{{number_format($salario,2,",",".")}}</div>
                </span>
            </div>
            <div style="clear: both;"></div>
            <div>
                <span style="width:89%;left:0;float:left;">1 Comissão</span>
                <div style="width:11%;right:0;top:0;float:right;">

                    <div style="width:75%;float:right;text-align:right;">{{number_format($comissao,2,",",".")}}</div>
                </div>
            </div>
            <div style="clear: both;"></div>
            <div>
                <span style="width:89%;left:0;float:left;">1 Premiação</span>
                <div style="width:11%;right:0;top:0;float:right;">
                    <div style="width:75%;float:right;text-align:right;">{{number_format($premiacao,2,",",".")}}</div>
                </div>
            </div>
            <div style="clear: both;"></div>

           <div style="clear: both;"></div>
           @if(!empty($vales) && count($vales) > 0)
           @foreach($vales as $vale)
           <div>
               <span style="width:89%;left:0;float:left;">Vale / Desconto</span>
               <div style="width:11%;right:0;top:0;float:right;">
                   <div style="width:75%;float:right;text-align:right;">({{number_format($vale->valor,2,",",".")}})</div>
               </div>
           </div>
           <div style="clear: both;"></div>
           @endforeach
           @endif
           <div>
                <span style="width:50%;left:0;float:left;">Total Geral</span>
                <span style="width:40%;right:0;top:0;float:right;text-align:right;font-weight:bold;">{{number_format($total_liquido ?? $total,2,",",".")}}</span>
           </div>
        </div>

        <div style="clear: both;"></div>

        <div style="border-top:1px solid black;width:95%;margin:0 auto;border-bottom:1px solid black;padding:5px 0;">
            <p style="font-size:0.875em;">ACOMPANHAMENTO DE VENDAS</p>
            <p style="font-size:0.875em;">Período de {{$primeiro_dia}} até {{$ultimo_dia}}</p>
            <p style="font-size:0.875em;">Status: Somente Pago</p>
        </div>

        <div style="clear: both;"></div>

        @php
            $total_plano_individual = 0;
            $total_comissao_individual = 0;
            $total_desconto_individual = 0;
            $total_valor_individual = 0;
            $total_plano_coletivo = 0;
            $total_comissao_coletivo = 0;
            $total_plano_empresarial = 0;
            $total_comissao_empresarial = 0;
            $total_desconto_empresarial = 0;
            $total_estorno_calculado = 0;
            $total_odonto_calculado = 0;

            $total_empresarial = 0;
            $i_individual = 0;
            $i_coletivo = 0;
            $i_empresarial = 0;
            $i_estorno = 0;
            $i_odonto = 0;
        @endphp




        @if(count($empresarial) >= 1 && $boolean_empresarial)
        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Empresarial</div>
        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Admin</td>
                    <td>Contrato</td>
                    <td align="center">Data</td>
                    <td>Cliente</td>
                    <td align="center">Parcela</td>
                    <td >Valor</td>
                    @if($tipo == "corretora")

                    <td align="center">Comissão</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($empresarial as $e)
                    @php
                       ++$i_empresarial;

                       $total_plano_empresarial += $e->valor_plano_contratado;
                       $total_comissao_empresarial += $e->comissao;
                       $total_desconto_empresarial += $e->desconto;
                    @endphp
                    <tr>
                        <td style="width:3%;">{{$i_empresarial}}</td>
                        <td style="width:8%;">HAPVIDA</td>
                        <td style="width:6%;">{{$e->codigo_externo}}</td>
                        <td style="width:8%;" align="center">{{$e->data}}</td>
                        <td style="width:30%;">{{mb_convert_case($e->cliente,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td style="width:8%;"  align="center">Parcela {{$e->parcela}}</td>
                        <td style="width:8%;">{{number_format($e->valor_plano_contratado,2,",",".")}}</td>
                        @if($tipo == "corretora")

                        <td style="width:8%;" align="center">{{number_format($e->comissao,2,",",".")}}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="border-top:1px solid black;">
                <tr>
                    <td colspan="7"></td>


                    @if($tipo == "corretora")


                    <td align="center">
                        @php
                            echo number_format($total_comissao_empresarial,2,",",".") ?? '';
                        @endphp
                    </td>
                    @endif

                </tr>
            </tfoot>
        </table>
        @endif







    </body>
</html>
