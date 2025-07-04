<x-app-layout>
    <input type="hidden" name="corretor_escolhido" id="corretor_escolhido">
    <input type="hidden" name="corretor_escolhido_historico" id="corretor_escolhido_historico">
    <input type="hidden" id="valores_confirmados" value="">
    <input type="hidden" id="mes_fechado" value="">
    <input type="hidden" id="mes_historico" value="">
    <input type="hidden" id="user_historico" value="">
    <input type="hidden" id="desconto_individual" value="">
    <input type="hidden" id="desconto_coletivo" value="">
    <input type="hidden" id="desconto_empresarial" value="">






    <div class="hidden" id="dataBaixaModal" tabindex="-1" role="dialog" aria-labelledby="dataBaixaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataBaixaModalLabel">Data Da Baixa?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" name="data_da_baixa" id="data_da_baixa" method="POST">
                        <input type="date" name="data_baixa" id="data_baixa" class="form-control form-control-sm">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="corretora" id="corretora">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div id="confirmationMessage" class="alert alert-success mt-3 text-center" style="display: none;">

    </div>

    <div id="loading-overlay" class="ocultar" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div class="dots-loading">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>



    <div id="myModalEmpresarial" class="fixed inset-0 z-50 flex items-start justify-center hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <!-- Conteúdo da Modal -->
        <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
            <!-- Botão Fechar no Topo -->
            <div id="modalLoaderEmpresa" class="flex justify-center items-start h-32">
                <div class="dot-flashing">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <!-- Borda Animada -->
            <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-empresarial hidden">
            </div>
        </div>
    </div>







    <section class="conteudo_abas" style="padding:5px;width:96%;margin:0 auto;">

        <ul class="list_abas" style="margin-top:1px;">
            <li data-id="aba_comissao" class="menu-inativo ativo">Comissão</li>

            <li class="ocultar" id="corretor_em_destaque"></li>
        </ul>

        <main id="aba_comissao" class="aba_comissao_container justify-between">
            <section  style="display:flex; flex-wrap:wrap; width:28%;justify-content: space-between;">
                <div style="display:flex;flex-basis:48%;flex-direction:column;">
                    <select name="mes_folha" id="mes_folha" class="form-control form-control-sm mb-1 w-full border border-gray-300 text-gray-700 text-sm rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400 tamanho_de_25">
                        <option value="" class="text-center">---</option>
                        <option value="01">Janeiro/2025</option>
                        <option value="02">Fevereiro/2025</option>
                        <option value="03">Março/2025</option>
                        <option value="04">Abril/2025</option>
                        <option value="05">Maio/2025</option>
                        <option value="06">Junho/2025</option>
                        <option value="07">Julho/2025</option>
                        <option value="08">Agosto/2025</option>
                        <option value="09">Setembro/2025</option>
                        <option value="10">Outubro/2025</option>
                        <option value="11">Novembro/2024</option>
                        <option value="12">Dezembro/2024</option>
                    </select>

                    <ul style="margin:0;padding:0;width:100%;" class="w-full flex flex-col bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Salario:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="salario" id="salario" value=""
                                           class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Comissão:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="comissao" id="comissao" value=""
                                           class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Premiação:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="premiacao" id="premiacao" value=""
                                           class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md premiacao_usuario"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Estorno:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled value="" name="estorno_geral" id="estorno_geral"
                                           class="salario_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Desconto:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled id="valor_total_desconto" value="" name="desconto" id="desconto"
                                           class="desconto_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Total:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="total_campo" value="" id="total_campo"
                                           class="desconto_usuario bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md total_campo"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>


                    </ul>

                    <div class="w-full bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded my-10 p-1" style="margin:10px 0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <p style="font-size:0.8em;color:#FFF;">Confirmado(!)</p>
                            <div id="criar_excel" style="width:10%; height:10%; padding:2px;background-color:white;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="small-svg">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <ul style="margin:0 0 0 0;padding:0;">
                            <li style="display:flex;justify-content: space-between;" data-plano="0" id="listar_empresarial_apto_total">
                                <span style="display:flex;flex-basis:50%;font-size:0.68em;margin-left:2px;">Empresarial</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_empresarial_total"></span>
                                <span style="display:flex;flex-basis:40%;justify-content:flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_empresarial_total"></span></span>
                            </li>
                        </ul>
                    </div>

                    <div id="list_user" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <p style="color:white;border-bottom:1px solid white;text-align: center;margin:0;padding: 0;font-size:0.7em;">Corretores</p>
                        <ul style="list-style:none;margin:0;padding:0;" class="w-100">
                            @php
                                $iix = 0;
                            @endphp

                        </ul>

                    </div>


                </div>

                <div style="display:flex;flex-basis:48%;flex-direction:column;">

                    <select name="escolher_vendedor" id="escolher_vendedor"
                            class="form-control form-control-sm mb-1 w-full border border-gray-300 text-gray-700 text-sm rounded-md tamanho_de_25">
                        <option value="" class="text-center">--Corretores--</option>

                        <option value="00">--Finalizar--</option>
                    </select>

                    <ul style="margin:0;padding:0;width:100%;" class="w-full flex flex-col bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Salario:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="salario_vendedor" id="salario_vendedor" value=""
                                           class="salario_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Comissão:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" disabled name="comissao_vendedor" id="comissao_vendedor" value=""

                                           class="salario_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"

                                           style="text-align:right; height:20px; font-size:0.8em; width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Premiação:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" name="premiacao_vendedor" id="premiacao_vendedor"
                                           class="premiacao_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Estorno:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" id="valor_total_estorno_vendedor" name="estorno_vendedor"
                                           class="estorno_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md estorno_usuario_vendedor"
                                           style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                                <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">
                                    Desconto:
                                </span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                    <input type="text" id="valor_total_desconto_vendedor" name="desconto_vendedor"
                                           class="desconto_usuario_vendedor bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md"
                                           style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                        </li>

                        <li class="flex justify-between">
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%; font-size:0.7em; color:#FFF;">Total:</span>
                            <span style="flex-grow: 1; flex-shrink: 1; flex-basis: 50%;">
                                <input type="text" disabled name="total_campo_vendedor" id="total_campo_vendedor"
                                       class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded-md total_campo_vendedor"
                                       style="text-align:right; height:20px; font-size:0.8em;width: 100%;">
                                </span>
                        </li>

                    </ul>


                    <div style="margin-top:2px;margin-bottom:2px;" class="w-full bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <span style="justify-content:center;display:flex;font-size:0.7em;color:white;border-bottom:1px solid white;">Confirmados(?)</span>
                        <ul style="margin:0 0 0 0;padding:0;" id="lista_apto_a_pagar_ul">

                            <li style="display:flex;justify-content: space-between;" id="listar_empresarial_apto">
                                <span style="display:flex;flex-basis:60%;font-size:0.68em;margin-left:2px;">Empresarial</span>
                                <span style="display:flex;flex-basis:10%;font-size:0.68em;" id="total_quantidade_empresarial">0</span>
                                <span style="display:flex;flex-basis:30%;justify-content: flex-end;font-size:0.68em;margin-right:2px;"><span id="valor_total_empresarial">0</span></span>
                            </li>
                        </ul>
                    </div>

                    <div style="border-radius:5px;margin:2px 0;" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                        <p class="border-bottom text-center" style="margin:0;padding: 0;color: white;font-size:0.7em">Confirmar(?)</p>
                        <ul style="margin:0 0 0 0;padding:0;list-style:none;" class="listar listar_a_receber_ul">

                            <li style="display:flex;font-size:0.68em;color:#FFF;margin-left:2px;display:flex;justify-content: space-between;align-items:center;" class="empresarial_a_receber">
                                <span>Empresarial</span>
                                <span class="valor_empresarial_a_receber valores_em_destaque">0</span>
                            </li>
                        </ul>
                    </div>






                    <div id="container_btns"></div>

                </div>


                <section id="footer_user" class="finalizar_mes_container" style="display:flex;flex-basis:100%;">

                </section>

            </section>

            <section style="display:flex;flex-basis:69%;flex-grow:1;margin-left:1%;">

                <section style="flex-basis:100%;">

                    <div style="color:#FFF;" id="listar_a_receber" class="dsnone">
                        <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                            <table id="tabela_mes_diferente" class="table table-sm listarcomissaomesdiferente" style="table-layout: fixed;">
                                <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Par.</th>
                                    <th>Valor</th>
                                    <th align="center">Venc.</th>
                                    <th>Baixa</th>
                                    <th>Porc(%)</th>
                                    <th>Pagar</th>
                                    <th style="text-align:left;">Vidas</th>
                                    <th>Desconto</th>
                                    <th>Confirmar(?)</th>
                                    <th>Ver</th>

                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>





                    <div style="color:#FFF;border-radius:5px;" id="tabela_principal">
                        <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]" style="border-radius:5px;">
                            <table id="tabela_mes_recebidas" class="table table-sm listarcomissaomesrecebidas w-100">
                                <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th align="center">Vencimento</th>
                                    <th>Baixa</th>
                                    <th>Comissão</th>
                                    <th>%</th>
                                    <th>Pagar</th>
                                    <th>Vidas</th>
                                    <th>Desconto</th>
                                    <th>Status</th>
                                    <th>Ver</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>


                </section>

            </section>
        </main>



    </section>

    <!-- Modal -->
    <div class="hidden" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Finalizar o Folha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Salario:</span>
                        <span class="flex salario_usuario_modal" style="flex-basis:70%;"></span>
                    </p>
                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Comissão:</span>
                        <span class="flex comissao_usuario_modal"></span>
                    </p>
                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Premiação:</span>
                        <span class="flex premiacao_usuario_modal"></span>
                    </p>

                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Estorno:</span>
                        <span class="flex estorno_usuario_modal"></span>
                    </p>


                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Desconto:</span>
                        <span class="flex desconto_usuario_modal"></span>
                    </p>
                    <p class="flex">
                        <span class="flex" style="flex-basis:20%;">Total:</span>
                        <span class="flex total_a_pagar_modal"></span>
                    </p>

                </div>
                <div class="modal-footer" style="display:flex;justify-content: center;">
                    <button type="button" class="btn btn-primary btn_usuario" data-dismiss="modal">Criar o PDF</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal com fundo transparente -->
    <div id="myModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" tabindex="-1" role="dialog">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl p-6">
            <!-- Modal Header -->
            <div class="modal-header flex justify-between items-center mb-4">
                <h5 class="modal-title text-xl font-semibold text-center">Resumo do Fechamento do Mês</h5>
                <button type="button" class="closeFecharMes text-gray-600 hover:text-gray-800" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div id="resultado_tabela">
                    <div class="loading-dots">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                </div>
                <div id="errorMessage" class="mt-2 text-red-600 font-bold text-center"></div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer flex justify-end">
                <button id="confirmBtn" style="background-color:green;color:#FFF;" class="focus:outline-none text-white w-full bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">Fechar Mês</button>
            </div>
        </div>
    </div>




    <!-- MODAL HISTORICO -->



    <div class="hidden" id="exampleModalTipoPlanosHistoricoCorretora" tabindex="-1" aria-labelledby="exampleModalLabelTipoPlanosHistoricoCorretora" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelTipoPlanosHistoricoCorretora">Planos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="checkbox" name="planos_tipo_individual_historico" id="planos_tipo_individual_historico" checked> Individual
                    </div>
                    <div>
                        <input type="checkbox" name="planos_tipo_coletivo_historico" id="planos_tipo_coletivo_historico" checked> Coletivo
                        <div class="w-75 ml-4">

                        </div>
                    </div>
                    <div>
                        <input type="checkbox" name="planos_tipo_empresarial_historico" id="planos_tipo_empresarial_historico" checked> Empresarial
                        <div class="w-75 ml-4">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 gerar_pdf_corretora_link_historico">Gerar PDF</button>
                </div>
            </div>
        </div>
    </div>







    <!-- FIM MODAL HISTORICO -->


    <!-- Overlay e Modal -->
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" id="exampleModalTipoPlanos" tabindex="-1" aria-labelledby="exampleModalLabelTipoPlanos" aria-hidden="true">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-semibold" id="exampleModalLabel">Planos</h5>
                <button type="button" class="text-gray-600 hover:text-gray-800" id="closeModalTipoPlanos">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div>
                    <input type="checkbox" name="planos_tipo_individual" id="planos_tipo_individual" checked> Individual
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_coletivo" id="planos_tipo_coletivo" checked> Coletivo
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_empresarial" id="planos_tipo_empresarial" checked> Empresarial
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md gerar_pdf_corretor_link hover:bg-blue-700">Gerar PDF</button>
            </div>
        </div>
    </div>






    <!-- Modal -->
    <!-- Overlay e Modal -->
    <div class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden" id="exampleModalTipoPlanosCorretora">
        <!-- Modal -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-semibold" id="exampleModalLabelTipoPlanosCorretora">Planos</h5>
                <button type="button" class="text-gray-600 hover:text-gray-800" id="closeModalCorretora">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div>
                    <input type="checkbox" name="planos_tipo_individual_corretora" id="planos_tipo_individual_corretora" checked> Individual
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_coletivo_corretora" id="planos_tipo_coletivo_corretora" checked> Coletivo
                </div>
                <div>
                    <input type="checkbox" name="planos_tipo_empresarial_corretora" id="planos_tipo_empresarial_corretora" checked> Empresarial
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md gerar_pdf_corretora_link hover:bg-blue-700">Gerar PDF</button>
            </div>
        </div>
    </div>












    @section('scripts')
        <script src="{{asset('js/jquery.mask.min.js')}}"></script>

        <script>

            $(function(){

                var url_padrao = "{{asset('data.json')}}";
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#escolher_vendedor").on('change',function() {
                    $("#loading-overlay").removeClass('ocultar');
                    let id_user_select = $(this).val();

                    let mes = $("#mes_folha option:selected").val();
                    let ano = $("#mes_folha option:selected").text().split("/")[1];


                    if (id_user_select != 00) {

                        let total = $("#total_campo_vendedor").val().trim();
                        let dados_user = $("#corretor_escolhido").val();


                        let premiacao = $("#premiacao_vendedor").val();
                        let salario = $("#salario_vendedor").val();
                        $("#list_user").find(".total_pagamento_finalizado").removeClass('.valor_total_change');
                        $("#corretor_escolhido").val(id_user_select);
                        $.ajax({
                            url: "{{route('gerente.informacoes.quantidade.corretor')}}",
                            method: "POST",
                            data:
                                "id=" + id_user_select +
                                "&mes=" + mes +
                                "&ano=" + ano +
                                "&premiacao=" + premiacao +
                                "&user_id=" + dados_user +
                                "&total=" + total +
                                "&salario=" + salario,
                            success: function (res) {
                                console.log(res);

                                $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);

                                $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                                $("#valor_total_empresarial").text(res.total_empresarial);
                                $("#comissao_vendedor").val(res.total_comissao);
                                {{--$("#valores_confirmados").val(res.id_confirmados);--}}
                                $("#salario_vendedor").val(res.total_salario);
                                {{--$("#premiacao_vendedor").val(res.total_premiacao);--}}


                                {{--$("#total_campo_vendedor").val(res.total);--}}

                                {{--$(".empresarial_a_receber").removeClass('ativo');--}}


                                {{--$(".empresarial_recebidas").removeClass('ativo');--}}

                                {{--$("#listar_empresarial_apto").removeClass('ativo');--}}


                                {{--$("#list_user").html(res.view);--}}
                                {{--$("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');--}}
                                {{--const select = $("#escolher_vendedor");--}}
                                {{--select.html('<option value="" class="text-center">--Corretores--</option>');--}}
                                {{--$.each(res.usuarios, function (index, corretor) {--}}
                                {{--    const option = $("<option>").attr("value", corretor.id).text(corretor.name);--}}
                                {{--    if (corretor.id == id_user_select) {--}}
                                {{--        option.attr("selected", "selected");--}}
                                {{--    }--}}
                                {{--    select.append(option);--}}
                                {{--});--}}
                                {{--select.append("<option value='00'>--Finalizar--</option>");--}}


                                {{--// let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)--}}
                                {{--if (parseFloat(res.total_comissao) > 0) {--}}
                                {{--    $(".total_a_pagar").text(res.total);--}}
                                {{--} else {--}}
                                {{--    $(".total_a_pagar").text(0);--}}
                                {{--}--}}


                                {{--if ($("#tabela_estorno_table").is(':visible')) {--}}
                                {{--    $("#tabela_estorno").slideUp('fast', function () {--}}
                                {{--        $("#tabela_principal").slideDown(1000, function () {--}}
                                {{--            $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");--}}
                                {{--            listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id_user_select}') }}`).load();--}}
                                {{--            $(".individual_recebidas").addClass("ativo");--}}
                                {{--        });--}}
                                {{--    });--}}
                                {{--}--}}

                                {{--if ($("#listar_a_receber").is(':visible')) {--}}
                                {{--    $("#listar_a_receber").slideUp('slow', function () {--}}
                                {{--        $("#tabela_principal").slideDown(1000, function () {--}}
                                {{--            $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");--}}
                                {{--            listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id_user_select}') }}`).load();--}}
                                {{--            $(".individual_recebidas").addClass("ativo");--}}
                                {{--        });--}}
                                {{--    })--}}
                                {{--}--}}

                                {{--if ($("#tabela_aptos_a_pagar").is(":visible")) {--}}
                                {{--    $("#tabela_aptos_a_pagar").slideUp(1000, function () {--}}
                                {{--        $("#tabela_principal").slideDown(1000, function () {--}}
                                {{--            $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");--}}
                                {{--            listarcomissaomesrecebidas.ajax.url(`{{ url('/gerente/listagem/comissao_mes_atual/${id_user_select}') }}`).load();--}}
                                {{--            $(".individual_recebidas").addClass("ativo");--}}
                                {{--        });--}}
                                {{--    });--}}
                                {{--}--}}

                                {{--total_mes_atual();--}}
                                {{--finalizarMes();--}}

                                {{--$("#container_btns").removeClass("flex").removeClass('flex-col').addClass('hidden');--}}
                                {{--$("#list_user").css("height", "235px");--}}

                            },
                            complete: function () {
                                // Esconder o overlay de loading
                                $("#loading-overlay").addClass('ocultar');
                            }
                        });
                    }
                })









                $('#mes_folha').on('change', function () {
                    const mes = $(this).val(); // Obtém o valor do mês selecionado
                    let ano = $(this).find('option:selected').text().split("/")[1];
                    let formattedDate = ano + "-" + mes+"-01";
                    $.ajax({
                        url:"{{route('gerente.cadastrar.folha_mes')}}",
                        method:"POST",
                        data:"data="+formattedDate,
                        success:function(res) {
                            if(res.resposta != "cadastrado") {
                                // $(".salario_usuario").val(res.dados.total_salario);
                                // $("#comissao").val(res.dados.total_comissao);
                                // $("#premiacao").val(res.dados.valor_premiacao);
                                // $("#valor_total_desconto").val(res.dados.valor_desconto);
                                // $("#total_campo").val(res.dados.total_mes);
                                // if ($('.list_abas #mes_existe').length > 0) {
                                //     $('.list_abas #mes_existe').hide(function(){
                                //         let selectedTextMudou = $("#mes_folha option:selected").text();
                                //         $('.list_abas #mes_existe').remove();
                                //         $(".list_abas").append(`<li id='mes_existe' style='width:740px;margin-left:5px;background-color:#B22222;display:flex;justify-content: space-between;'><span>O Mês ${selectedTextMudou} já esta fechado</span><button class="btn_valores_mes flex justify-content-center" style="border:none;font-size:0.8em;background-color:#FF6347;color:#FFF;border-radius:5px;border:1px solid #FFF;" data-mes="${mes}">Criar PDF</button></li>`);
                                //     });
                                // } else {
                                //     let selectedTextMudou = $("#mes_folha option:selected").text();
                                //     $(".list_abas").append(`<li id='mes_existe' style='width:740px;margin-left:5px;background-color:#B22222;display:flex;justify-content: space-between;'><span>O Mês ${selectedTextMudou} já esta fechado</span><button class="btn_valores_mes flex justify-content-center" style="border:none;font-size:0.8em;background-color:#FF6347;color:#FFF;border-radius:5px;border:1px solid #FFF;" data-mes="${mes}">Criar PDF</button></li>`);
                                // }
                                // $("#list_user").css({"height":"325px","max-height":"325px","overflow":"auto"}).html(res.view);
                                // $(".salario_usuario_vendedor").prop("disabled",true);
                                // $(".premiacao_usuario_vendedor").prop("disabled",true);
                                // $("#escolher_vendedor option:not(:first-child)").remove();
                                // $(this).prop("disabled",false);
                                // let column = listaraptosapagar.column(11);
                                // column.visible(false);
                                // $(".menu_aba_comissao").height("500px");
                                // $("#mes_fechado").val(mes);
                                // $("#finalizar_folha").prop('disabled', true);
                                // $(".individual_recebidas").off('click');
                                // $(".finalizar_mes").fadeOut('fast');
                                // $(".individual_estorno_receber").off('click');
                                // $(".coletivo_estorno_receber").off('click');
                                // $(".empresarial_estorno_receber").off('click');
                                // $(".coletivo_recebidas").off('click');
                                // $(".empresarial_recebidas").off('click');
                                // $(".individual_a_receber").off('click');
                                // $(".coletivo_a_receber").off('click');
                                // $(".empresarial_a_receber").off('click');
                                // $("#salario_vendedor").val(0);
                                // $("#premiacao_vendedor").val(0);
                                // $("#comissao_vendedor").val(0);
                                // $("#desconto_vendedor").val(0);
                                // $("#finalizar_folha").text("Finalizar");
                                // $("#total_quantidade_individual").text(0);
                                // $("#valor_total_individual").text(0);
                                // $("#total_quantidade_coletivo").text(0);
                                // $("#valor_total_coletivo").text(0);
                                // $("#total_quantidade_empresarial").text(0);
                                // $("#valor_total_empresarial").text(0);
                            } else {
                                $("#mes_folha").prop("disabled",true);
                                $("#mes_existe").hide();
                                // $(".individual_recebidas").on('click',individual_recebidas);
                                // $(".coletivo_recebidas").on('click',coletivo_recebidas);
                                // $(".empresarial_recebidas").on('click');
                                // $(".individual_a_receber").on('click',individual_a_receber);
                                // $(".coletivo_a_receber").on('click',coletivo_a_receber);
                                // $(".empresarial_a_receber").on('click');
                                $("#finalizar_folha").prop('disabled', false);
                                $("#list_user").html("");
                                $("#footer_user").html("");
                                $(".salario_usuario").val(0);
                                $("#comissao").val(0);
                                $("#premiacao").val(0);
                                $("#valor_total_desconto").val(0);
                                $("#total_campo").val(0);
                                let select = $('#escolher_vendedor');
                                select.html('');
                                select.append($('<option>', {
                                    value: '',
                                    text: '--Corretores--'
                                }).css('text-align', 'center'));

                                $.each(res.users_select, function(index, user) {
                                    let option = $('<option>', {
                                        value: user.id,
                                        text: user.name
                                    });
                                    select.append(option);
                                });
                                select.append("<option>--Finalizar--</option>");
                                //$("#footer_user").html('<button class="btn btn-info btn-block mx-auto finalizar_mes">Finalizar</button>');
                            }
                        }
                    });
                });



                $(".empresarial_a_receber").on('click',function(){
                    // $(".estilizar_search input[type='search']").val('');
                    // listarcomissaomesdfirente.search('').draw();
                    // listarcomissaomesrecebidas.search('').draw();
                    // listaraptosapagar.search('').draw();

                    let id = $("#corretor_escolhido").val();
                    $(this).addClass('ativo');

                    if(id) {
                        console.log(id);
                        if($("#listar_a_receber").is(":visible")) {
                            $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                            listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                        } else {

                            if($("#tabela_principal").is(":visible")) {
                                $("#tabela_principal").slideUp(1000,function(){
                                    $("#listar_a_receber").slideDown('slow',function(){
                                        $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                    });
                                });
                            }
                            if($("#tabela_aptos_a_pagar").is(":visible")) {
                                $("#tabela_aptos_a_pagar").slideUp(1000,function(){
                                    $("#listar_a_receber").slideDown('slow',function(){
                                        $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                    });
                                });
                            }

                            if($("#listar_cadastrados").is(":visible")) {
                                $("#listar_cadastrados").slideUp(1000,function(){
                                    $("#listar_a_receber").slideDown('slow',function(){
                                        $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                    });
                                });
                            }

                            if($("#tabela_estorno_table").is(':visible')) {
                                $("#tabela_estorno").slideUp('fast',function(){
                                    $("#listar_a_receber").slideDown('slow',function(){
                                        $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                    });
                                });
                            }

                            if($("#tabela_estorno_table_back").is(":visible")) {
                                $("#tabela_estorno_back").slideUp(1000,function(){
                                    $("#listar_a_receber").slideDown('slow',function(){
                                        $("#title_comissao_diferente").html("<h4>A Receber Empresarial</h4>")
                                        listarcomissaomesdfirente.ajax.url(`{{ url('/gerente/empresarial/listar/${id}') }}`).load();
                                    });
                                });
                            }






                        }
                    } else {
                        $("#listar_coletivo_apto").removeClass("ativo");
                        $(".listar li").removeClass("ativo");
                        $("#listar_individual_apto").removeClass("ativo");
                        toastr["error"]("Escolha um Corretor")
                        toastr.options = {
                            'time-out': 3000,
                            'close-button':true,
                            'position-class':'toast-top-full-width',
                            'class' : 'fullwidth',
                            'fixed': false
                        }
                    }





                });

                var listarcomissaomesdfirente = $(".listarcomissaomesdiferente").DataTable({
                    dom: '<"flex justify-between"<"#title_comissao_diferente"><"estilizar_search"f>><tr><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                    language: {
                        "search": "Pesquisar",
                        "paginate": {
                            "next": "Próx.",
                            "previous": "Ant.",
                            "first": "Primeiro",
                            "last": "Último"
                        },
                        "emptyTable": "Nenhum registro encontrado",
                        "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "infoFiltered": "(Filtrados de _MAX_ registros)",
                        "infoThousands": ".",
                        "loadingRecords": "Carregando...",
                        "processing": "Processando...",
                        "lengthMenu": "Exibir _MENU_ por página"
                    },

                    "lengthMenu": [1000,2000,3000,4000],
                    "ordering": false,
                    "paging": true,
                    "searching": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": false,
                    processing: true,
                    ajax: {
                        url:url_padrao,
                        dataSrc:""
                    },
                    columns: [
                        {data:"administradora",name:"administradora",width:"5%"},
                        {data:"data_criacao",name:"data_criacao",
                            "createdCell": function(td, cellData, rowData, row, col) {
                                let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                                $(td).html(datas);
                            },width:"7%"
                        },
                        {data:"orcamento",name:"orcamento",width:"5%"},
                        {data:"cliente",name:"cliente",width:"20%"},
                        {data:"parcela",name:"parcela",className: 'dt-center',width:"3%"},
                        {data:"valor_plano_contratado",name:"valor_plano_contratado",width:"5%",
                            render: $.fn.dataTable.render.number('.',',',2,'')
                        },
                        {data:"data",name:"data",className: 'dt-center',width:"7%"},
                        {data:"data_criacao",name:"data_criacao",
                            "createdCell": function(td, cellData, rowData,row, col) {
                                let datas = cellData.split(" ")[0].split("-").reverse().join("/");
                                $(td).html(datas);
                            },width:"7%"
                        },

                        {data:"porcentagem_parcela_corretor",
                            name:"porcentagem_parcela_corretor",
                            width:"10%",
                            "createdCell":function(td, cellData, rowData, row, col) {
                                $(td).html('<input type="text" data-valor-plano='+rowData.valor_plano_contratado+'  data-id='+rowData.id+' value='+cellData+' name="comissao_paga_change" class="comissao_paga_change" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
                            }
                        },
                        {data:"valor",name:"comissao_pagando",render: $.fn.dataTable.render.number('.',',',2,'R$ '),width:"10%",
                            "createdCell":function(td, cellData, rowData, row, col) {
                                let valor_comisao = parseFloat(rowData.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                $(td).html('<input type="text" value='+valor_comisao+' data-id='+cellData+' readonly name="comissao_pagando" class="comissao_pagando" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
                            }
                        },
                        {data:"quantidade_vidas",name:"quantidade_vidas",width:"3%",className: 'dt-center'},
                        {data:"desconto",name:"desconto",width:"8%",
                            "createdCell":function(td, cellData, rowData, row, col) {
                                let descondo_calc = parseFloat(cellData).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                $(td).html('<input type="text" value='+descondo_calc+' data-id='+rowData.id+' name="porcentagem_change" class="porcentagem_change" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
                            }
                        },
                        {
                            data:"id",name:"id",width:"8%",className: 'dt-center',
                            "createdCell": function (td, cellData, rowData, row, col) {
                                $(td).html(`
                                <svg id="${cellData}" data-plano="${rowData.plano}" class="w-6 h-6 text-white pagar_comissao_up" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"/>
                                </svg>

                            `)
                            }
                        },
                        {
                            data:"contrato_id",name:"contrato_id",width:"3%",
                            "createdCell": function (td, cellData, rowData, row, col) {
                                let contrato_id = cellData;
                                if(rowData.plano == 3) {
                                    $(td).html(`<div data-id="${contrato_id}" class='text-center text-white ver_coletivo'>
                                        <a href="#" target="_blank" class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                `);
                                } else if(rowData.plano == 1) {
                                    $(td).html(`<div data-id="${contrato_id}" class='text-center text-white ver_individual'>
                                        <a href="#" class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                `);
                                } else {
                                    $(td).html(`<div data-id="${contrato_id}" class='text-center text-white ver_empresarial'>
                                        <a href="#" target="_blank" class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                `);
                                }




                            },
                        }
                    ],

                    "initComplete": function( settings, json ) {
                        $('#title_comissao_diferente').html("<h4>A Receber Individual</h4>");
                    }
                });







                var listarcomissaomesrecebidas = $(".listarcomissaomesrecebidas").DataTable({
                    dom: '<"flex justify-between"<"#title_recebidas"><"estilizar_search"f>><t><"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                    language: {
                        "search": "Pesquisar",
                        "paginate": {
                            "next": "Próx.",
                            "previous": "Ant.",
                            "first": "Primeiro",
                            "last": "Último"
                        },
                        "emptyTable": "Nenhum registro encontrado",
                        "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "infoFiltered": "(Filtrados de _MAX_ registros)",
                        "infoThousands": ".",
                        "loadingRecords": "Carregando...",
                        "processing": "Processando...",
                        "lengthMenu": "Exibir _MENU_ por página"
                    },
                    ajax: {
                        url:'',
                        dataSrc:""
                    },
                    "lengthMenu": [50,100,150,200,300,500],
                    "ordering": false,
                    "paging": true,
                    "searching": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    columns: [

                    ],

                    "initComplete": function( settings, json ) {



                    },
                    footerCallback: function (row, data, start, end, display) {

                    }
                });














            });
        </script>
    @stop

    @section('css')
        <style>
            #criar_excel:hover {
                cursor:pointer;
            }

            #criar_excel_historico:hover {
                cursor:pointer;
            }




            input[type="search"] {
                color:black !important;
            }
            .loading-dots {display: flex;justify-content: center;align-items: center;}
            .loading-dots div {width: 12px;height: 12px;margin: 10px 4px;border-radius: 50%;background-color: #333;animation: loading-dots 1.2s infinite ease-in-out;}
            .loading-dots div:nth-child(1) {animation-delay: 0s;}
            .loading-dots div:nth-child(2) {animation-delay: 0.2s;}
            .loading-dots div:nth-child(3) {animation-delay: 0.4s;}
            @keyframes loading-dots {
                0%, 80%, 100% {transform: scale(0);}
                40% {transform: scale(1);}
            }
            .pagar_comissao_up {border:1px solid white;padding:3px;cursor:pointer;}
            .pagar_comissao_up:hover,
            .pagar_comissao_up:focus {border:1px solid orange;background-color:orange;color:black;}
            #listar_individual_apto,#listar_coletivo_apto,#listar_empresarial_apto,#listar_individual_apto_total,#listar_coletivo_apto_total,#listar_empresarial_apto_total span {color:#FFF;}
            .valores_em_destaque {color:black;background-color:white;padding:3px;border-radius:50%;font-size:0.9em;margin:2px;width:20px;display:flex;justify-content: center;font-weight:bold;}
            .btn_concluido {background-color:#123449;border-radius:5px;display:flex;justify-content:center;align-items:center;align-content:center;padding:10px;}
            .btn_concluido:hover {cursor:pointer;}
            .client-cell {max-width: 40%;overflow: hidden;text-overflow: ellipsis;}
            #corretor_em_destaque {margin-left:1%;background-color:#123449;width:600px;}
            .tamanho_de_25 {height: 40px;}
            .dsnone {display:none;}
            .ocultar {display:none !important;}
            .aba_comissao_container,.aba_historico_container {display:flex;position:relative;justify-content: space-between;flex-basis:100%;}
            .dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody table.dataTable {padding: 0;}
            #list_user,#list_user_historico {height:160px;overflow:auto;border-radius:5px;}
            #list_user::-webkit-scrollbar,#list_user_historico::-webkit-scrollbar {width: 2px;height: 2px !important;background-color: white;}
            #list_user::-webkit-scrollbar-thumb,#list_user_historico::-webkit-scrollbar-thumb {background-color: #1a88ff;}
            .user_destaque_impar {background-color:rgba(0,0,0,0.5) !important;}
            .user_destaque_hover {background-color:red;}
            #tabela_coletivo td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
            .dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody td,.dataTables_wrapper .dataTables_wrapper .dataTables_scrollBody th {padding: 0;}
            .menu_aba_comissao {margin-right: 1%;display:flex;flex-direction:column;}
            .list_administradoras {display:flex;flex-direction: column;color:#fff;justify-content: center;}
            .total_mes_comissao {color:#FFF;text-align: center;}
            #container_mostrar_comissao {width:439px;height:555px;background-color: #123449;position: absolute;right:5px;border-radius: 5px;}
            .container_edit {display:flex;justify-content:end;}
            .ativo {background-color:red !important;color:orange !important;}
            .ocultar {display: none;}
            .list_abas {list-style: none;display: flex;border-bottom: 1px solid white;margin: 0;padding: 0;}
            .list_abas li {color: #fff;width: 150px;padding: 8px 5px;text-align:center;border-radius: 5px 5px 0 0;background-color:#123449;}
            .list_abas li:hover {cursor: pointer;}
            .list_abas li:nth-of-type(2) {margin: 0 1%;}
            .textoforte {background-color:rgba(255,255,255,0.5) !important;color:black;}
            .textoforte-list {background-color:rgba(255,255,255,0.5);color:white;}
            .destaque {background-color:rgba(255,255,255,0.5) !important;color:black;border:1px solid black;}
            .ativo {background-color:#FFF !important;color: #000 !important;}
            .botao:hover {background-color: rgba(0,0,0,0.5) !important;color:#FFF !important;}
            .valores-acomodacao {background-color:#123449;color:#FFF;width:32%;box-shadow:rgba(0,0,0,0.8) 0.6em 0.7em 5px;}
            .valores-acomodacao:hover {cursor:pointer;box-shadow: none;}
            .table thead tr {color: white;}
            .destaque {border:4px solid rgba(36,125,157);}
            #coluna_direita {flex-basis:10%;background-color:#123449;border-radius: 5px;}
            #coluna_direita ul {list-style: none;margin: 0;padding: 0;}
            #coluna_direita li {color:#FFF;}
            .coluna-right {flex-basis:30%;flex-wrap: wrap;border-radius:5px;height:720px;}
            .container_div_info {background-color:rgba(0,0,0,1);position:absolute;width:500px;right:0px;top:57px;min-height: 700px;display: none;z-index: 1;color: #FFF;}
            #padrao {width:50px;background-color:#FFF;color:#000;}
            .buttons {display: flex;}
            .button_individual {display:flex;}
            .button_empresarial {display: flex;}
            .menu-inativo {color:#FFF;}
            .btn_recebido {background-color:green;color:#FFF;border:none;}
            th { font-size: 0.78em !important; }
            td { font-size: 0.65em !important; }
            #tabelaResultados th {font-size: 1em !important;}
            #tabelaResultados td {font-size: 0.8em !important;}
            .dt-right {text-align: right !important;}
            .dt-center {text-align: center !important;}
            .estilizar_pagination .pagination {font-size: 0.8em !important;color:#FFF;}
            .estilizar_pagination .pagination li {height:10px;color:#FFF;}
            .por_pagina {font-size: 12px !important;color:#FFF;}
            #tabela_mes_diferente {table-layout: fixed;}
            .por_pagina #tabela_mes_atual_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
            .por_pagina #tabela_mes_diferente_length {display: flex;align-items: center;align-self: center;margin-top: 8px;}
            .por_pagina select {color:#FFF !important;}
            #tabela_individual_previous {color:#FFF !important;background-color: red !important;}
            #tabela_individual_next {color:#FFF !important;}
            #tabela_coletivo_previous {color:#FFF !important;}
            #tabela_coletivo_next {color:#FFF !important;}
            .estilizar_search input[type='search'] {background-color: #FFF !important;width:500px;}
            .tabela_individual_paginate {color:#FFF !important;}
            .link_coletivo_um:hover {background-color: rgb(245,50,16) !important;}
            .link_coletivo_dois:hover {background-color: rgb(245,50,16) !important;}
            .link_coletivo_tres:hover {background-color: rgb(245,50,16) !important;}
            .link_coletivo_quatro:hover {background-color: rgb(245,50,16) !important;}
            .link_coletivo_cinco:hover {background-color: rgb(245,50,16) !important;}
            .link_coletivo_seis:hover {background-color: rgb(245,50,16) !important;}
            .link_empresarial_um:hover {background-color: rgb(254,200,109) !important;}
            .link_empresarial_dois:hover {background-color: rgb(254,200,109) !important;}
            .link_empresarial_tres:hover {background-color: rgb(254,200,109) !important;}
            .link_empresarial_quatro:hover {background-color: rgb(254,200,109) !important;}
            .link_empresarial_cinco:hover {background-color: rgb(254,200,109) !important;}
            .link_empresarial_seis:hover {background-color: rgb(254,200,109) !important;}
            .user_nome {font-size: 0.7em;flex: 1;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
            .user_total {font-size: 0.6em;flex-shrink: 0;margin-left: 5px;}
            .user_destaque_ativo {background-color:#FFF !important;color:black !important;}
            #tabela_mes_diferente_filter input[type="search"],
            #tabela_mes_recebidas_filter input[type="search"],
            #tabela_cadastrados_filter input[type="search"] {margin:5px 5px 0px 9px;}
            #title_comissao_diferente,#title_recebidas,#title_cadastrados {margin:5px 0 0 9px;}
            #tabela_aptos_a_pagar_table td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
            #tabela_mes_diferente td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
            #tabelaResultados td {white-space: nowrap;overflow: hidden;text-overflow: clip;}
            .small-svg
            {
                width: 16px;
                height: 16px;
            }
        </style>
    @stop
</x-app-layout>

