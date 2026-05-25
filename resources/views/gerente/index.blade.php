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







    {{-- ── Page ── --}}
    <div class="ger-page">
        <div class="ger-inner">

            {{-- Header --}}
            <div class="ger-header">
                <div>
                    <h1 class="ger-title">Gerente</h1>
                    <p class="ger-sub">Gestão de comissões e fechamento mensal</p>
                </div>
                <div class="ger-header-actions">
                    @php
                        $nomes_meses = ['01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril','05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto','09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro'];
                        $ano_folha   = $ano ?: date('Y');
                        $folha_aberta = !empty($mes) && $mes != 0;
                        $nome_mes_aberto = $folha_aberta ? ($nomes_meses[$mes] ?? '') : '';
                        $opcoes_meses = [];
                        foreach (range($ano_folha - 1, $ano_folha + 1) as $y) {
                            foreach ($nomes_meses as $num => $nome) {
                                $opcoes_meses[] = ['valor' => $num, 'label' => $nome.'/'.$y, 'ano' => $y];
                            }
                        }
                    @endphp
                    <select name="mes_folha" id="mes_folha"
                        class="ger-select tamanho_de_25 {{ $folha_aberta ? 'ger-select-aberta' : '' }}"
                        {{ $folha_aberta ? 'disabled' : '' }}>
                        <option value="" class="text-center">---</option>
                        @foreach($opcoes_meses as $op)
                            <option value="{{ $op['valor'] }}" data-ano="{{ $op['ano'] }}"
                                {{ ($mes == $op['valor'] && $ano_folha == $op['ano']) ? 'selected' : '' }}>{{ $op['label'] }}</option>
                        @endforeach
                    </select>
                    @if($folha_aberta)
                        <p class="ger-folha-aberta-label">&#x25CF; Folha de {{ $nome_mes_aberto }} de {{ $ano_folha }} aberta</p>
                    @endif
                    <button id="btn_vale" class="ger-btn ger-btn-orange">Vale</button>
                    <button id="btn_finalizar_mes" class="ger-btn ger-btn-red">Finalizar Mês</button>
                </div>
            </div>

            {{-- Body --}}
            <main id="aba_comissao" class="aba_comissao_container ger-body">

                {{-- Sidebar Esquerda: Resumo Geral --}}
                <div class="ger-card ger-sidebar">
                    <div class="ger-stat-block">
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Salário</span>
                            <input type="text" disabled name="salario" id="salario"
                                   value="{{number_format($total_salario,2,',','.')}}"
                                   class="ger-stat-input salario_usuario">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Comissão</span>
                            <input type="text" disabled name="comissao" id="comissao"
                                   value="{{number_format($total_mes,2,',','.')}}"
                                   class="ger-stat-input salario_usuario">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Premiação</span>
                            <input type="text" disabled name="premiacao" id="premiacao"
                                   value="{{number_format($total_premiacao,2,',','.')}}"
                                   class="ger-stat-input salario_usuario premiacao_usuario">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Estorno</span>
                            <input type="text" disabled value="{{number_format($estorno_geral,2,',','.')}}"
                                   name="estorno_geral" id="estorno_geral"
                                   class="ger-stat-input salario_usuario estorno_usuario">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Desconto</span>
                            <input type="text" disabled id="valor_total_desconto"
                                   value="{{number_format($total_desconto,2,',','.')}}"
                                   class="ger-stat-input desconto_usuario estorno_usuario">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Total</span>
                            <input type="text" disabled name="total_campo"
                                   value="{{number_format($total_mes,2,',','.')}}" id="total_campo"
                                   class="ger-stat-input ger-stat-input-total desconto_usuario total_campo">
                        </div>
                    </div>

                    {{-- Confirmado (!): totais gerais + botão Excel --}}
                    <div class="ger-confirmed-box">
                        <div class="ger-confirmed-header">
                            <span>Confirmado(!)</span>
                            <button id="criar_excel" class="ger-excel-btn" title="Exportar .xlsx">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                .xlsx
                            </button>
                        </div>
                        <ul class="ger-confirmed-list">
                            <li id="listar_empresarial_apto_total" data-plano="0">
                                <span class="ger-confirmed-lbl">Empresarial</span>
                                <span class="ger-confirmed-qty" id="total_quantidade_empresarial_total">{{$total_empresarial_quantidade}}</span>
                                <span class="ger-confirmed-val"><span id="valor_total_empresarial_total">{{number_format($total_mes,2,',','.')}}</span></span>
                            </li>
                        </ul>
                    </div>

                    {{-- Lista de Corretores --}}
                    <div id="list_user" class="ger-user-list">
                        <p class="ger-user-list-title">Corretores</p>
                        <ul>
                            @php $iix = 0; @endphp
                            @foreach($users_apto_apagar as $uu)
                                @php $iix++; @endphp
                                <li class="{{ $iix % 2 == 0 ? 'user_destaque_impar' : '' }}">
                                    <span class="user_nome user_destaque" data-id="{{ $uu->user_id }}">{{ Illuminate\Support\Str::limit($uu->user,20,'') }}</span>
                                    <span class="user_total total_pagamento_finalizado user_destaque" data-id="{{ $uu->user_id }}">{{ number_format($uu->total, 2, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>{{-- /ger-sidebar esquerda --}}

                {{-- Sidebar Direita: Vendedor --}}
                <div class="ger-card ger-sidebar">

                    <select name="escolher_vendedor" id="escolher_vendedor" class="ger-select tamanho_de_25">
                        <option value="" class="text-center">-- Corretores --</option>
                        @foreach($users as $u)
                            <option value="{{$u->id}}" data-name="{{$u->name}}">{{$u->name}}</option>
                        @endforeach
                        <option value="00">-- Finalizar --</option>
                    </select>

                    <div class="ger-stat-block" style="margin-top:8px;">
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Salário</span>
                            <input type="text" disabled name="salario_vendedor" id="salario_vendedor" value=""
                                   class="ger-stat-input salario_usuario_vendedor">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Comissão</span>
                            <input type="text" disabled name="comissao_vendedor" id="comissao_vendedor" value=""
                                   class="ger-stat-input salario_usuario_vendedor">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Premiação</span>
                            <input type="text" name="premiacao_vendedor" id="premiacao_vendedor"
                                   class="ger-stat-input premiacao_usuario_vendedor">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Estorno</span>
                            <input type="text" id="valor_total_estorno_vendedor" name="estorno_vendedor"
                                   class="ger-stat-input estorno_usuario_vendedor">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Desconto</span>
                            <input type="text" id="valor_total_desconto_vendedor" name="desconto_vendedor"
                                   class="ger-stat-input desconto_usuario_vendedor">
                        </div>
                        <div class="ger-stat-divider"></div>
                        <div class="ger-stat-row">
                            <span class="ger-stat-lbl">Total</span>
                            <input type="text" disabled name="total_campo_vendedor" id="total_campo_vendedor"
                                   class="ger-stat-input ger-stat-input-total total_campo_vendedor">
                        </div>
                    </div>

                    {{-- Confirmados (?) --}}
                    <div class="ger-confirmed-box">
                        <p class="ger-confirmed-section-title">Confirmados(?)</p>
                        <ul class="ger-confirmed-list" id="lista_apto_a_pagar_ul">
                            <li id="listar_empresarial_apto">
                                <span class="ger-confirmed-lbl">Empresarial</span>
                                <span class="ger-confirmed-qty" id="total_quantidade_empresarial">0</span>
                                <span class="ger-confirmed-val"><span id="valor_total_empresarial">0</span></span>
                            </li>
                        </ul>
                    </div>

                    {{-- Confirmar (?) --}}
                    <div class="ger-confirmed-box" style="margin-top:8px;">
                        <p class="ger-confirmed-section-title">Confirmar(?)</p>
                        <ul class="ger-confirmed-list listar listar_a_receber_ul">
                            <li class="empresarial_a_receber">
                                <span class="ger-confirmed-lbl">Empresarial</span>
                                <span class="valor_empresarial_a_receber valores_em_destaque">0</span>
                            </li>
                        </ul>
                    </div>



                    <div id="container_btns" style="margin-top:8px;"></div>
                    <section id="footer_user" class="finalizar_mes_container" style="display:none;"></section>

                </div>{{-- /ger-sidebar direita --}}

                {{-- Área das Tabelas --}}
                <div class="ger-tables-area">

                    <div id="listar_a_receber" class="ger-table-panel dsnone">
                        <table id="tabela_mes_diferente" class="table table-sm listarcomissaomesdiferente" style="table-layout:fixed;width:100%;">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Par.</th>
                                    <th>Valor</th>
                                    <th>Venc.</th>
                                    <th>Baixa</th>
                                    <th>Porc(%)</th>
                                    <th>Pagar</th>
                                    <th>Vidas</th>
                                    <th>Desconto</th>
                                    <th>Confirmar(?)</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="tabela_aptos_a_pagar" class="ger-table-panel dsnone">
                        <table id="tabela_aptos_a_pagar_table" class="table table-sm listaraptosapagar w-100" style="table-layout:fixed;">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Baixa</th>
                                    <th>Pagar</th>
                                    <th>Desc.</th>
                                    <th>Remover(?)</th>
                                    <th>Ver</th>
                                    <th>Plano</th>
                                    <th>Corretor</th>
                                    <th>Vidas</th>
                                    <th>Parcela</th>
                                    <th>Cod.Externo</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="tabela_principal" class="ger-table-panel">
                        <table id="tabela_mes_recebidas" class="table table-sm listarcomissaomesrecebidas w-100">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Data</th>
                                    <th>Cod.</th>
                                    <th>Cliente</th>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
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

                </div>{{-- /ger-tables-area --}}

            </main>

        </div>
    </div>

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











    <!-- FIM MODAL HISTORICO -->


    <!-- Overlay e Modal -->
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" id="exampleModalTipoPlanos" tabindex="-1" aria-labelledby="exampleModalLabelTipoPlanos" aria-hidden="true">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-semibold" id="exampleModalLabel">Planos</h5>
                <button type="button" class="text-gray-600 hover:text-gray-800" id="closeModalTipoPlanos">&times;</button>
            </div>



            <!-- Modal Footer -->
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-blue-500 w-full text-white px-4 py-2 rounded-md gerar_pdf_corretor_link hover:bg-blue-700">Gerar PDF</button>
            </div>
        </div>
    </div>






    <!-- Modal -->
    <!-- Overlay e Modal -->

    <div class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden z-50" id="exampleModalTipoPlanosCorretora">
        <div class="bg-white rounded-lg shadow-lg w-full p-6" style="max-width:680px;">

            <!-- Header -->
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-semibold text-gray-800">Resumo da Folha</h5>
                <button type="button" class="text-gray-500 hover:text-gray-800 text-2xl leading-none" id="closeModalCorretora">&times;</button>
            </div>

            <!-- Pills: comissão / vale / total -->
            <div class="flex gap-3 mb-4" id="resumo_folha_pills">
                <div class="flex-1 rounded-lg p-3 text-center" style="background:#e8f4fd;">
                    <p class="text-xs text-gray-500 mb-1">Comissão</p>
                    <p class="font-bold text-blue-700" id="pill_comissao">–</p>
                </div>
                <div class="flex-1 rounded-lg p-3 text-center" style="background:#fff3e0;">
                    <p class="text-xs text-gray-500 mb-1">Vale</p>
                    <p class="font-bold text-orange-600" id="pill_vale">–</p>
                </div>
                <div class="flex-1 rounded-lg p-3 text-center" style="background:#e8f5e9;">
                    <p class="text-xs text-gray-500 mb-1">Total Líquido</p>
                    <p class="font-bold text-green-700" id="pill_total">–</p>
                </div>
            </div>

            <!-- Tabela de empresas -->
            <div style="max-height:320px;overflow-y:auto;">
                <table class="w-full text-sm">
                    <thead style="position:sticky;top:0;background:#fff;">
                        <tr style="border-bottom:2px solid #e5e7eb;color:#6b7280;text-align:left;">
                            <th class="py-2 pr-2 font-medium" style="width:30px;">#</th>
                            <th class="py-2 pr-2 font-medium">Empresa</th>
                            <th class="py-2 pr-2 font-medium">Contrato</th>
                            <th class="py-2 pr-2 font-medium">Data</th>
                            <th class="py-2 font-medium text-right">Comissão</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_preview_folha">
                        <tr><td colspan="5" class="text-center py-6 text-gray-400">Carregando...</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="mt-4">
                <button type="button" class="bg-blue-600 w-full text-white px-4 py-2 rounded-md gerar_pdf_corretora_link hover:bg-blue-700 font-medium">
                    Gerar PDF da Folha
                </button>
            </div>
        </div>
    </div>












<!-- ══════════════ MODAL DETALHE CONTRATO GERENTE ══════════════ -->
<div id="modalDetalheGerente" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 hidden z-50">
    <div class="rounded-xl shadow-2xl p-6" style="background:#151e30;color:#e2e8f0;width:520px;max-width:95%;border:1px solid rgba(255,255,255,.1);">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h5 class="text-base font-bold" id="detalheGerTitulo" style="color:#fff;margin:0;"></h5>
                <p class="text-xs mt-1" id="detalheGerSub" style="color:rgba(255,255,255,.4);margin:0;"></p>
            </div>
            <button id="closeModalDetalheGerente" style="color:rgba(255,255,255,.5);font-size:1.4rem;line-height:1;background:none;border:none;cursor:pointer;">&times;</button>
        </div>

        <!-- chips de valores -->
        <div class="flex gap-3 mb-4" id="detalheGerChips">
            <div class="flex-1 rounded-lg p-3 text-center" style="background:rgba(79,142,247,.12);border:1px solid rgba(79,142,247,.25);">
                <p class="text-xs mb-1" style="color:rgba(255,255,255,.4);">Valor Plano</p>
                <p class="font-bold text-sm" id="dg_valor_plano" style="color:#93c5fd;">–</p>
            </div>
            <div class="flex-1 rounded-lg p-3 text-center" style="background:rgba(52,211,153,.12);border:1px solid rgba(52,211,153,.25);">
                <p class="text-xs mb-1" style="color:rgba(255,255,255,.4);">Comissão</p>
                <p class="font-bold text-sm" id="dg_comissao" style="color:#34d399;">–</p>
            </div>
            <div class="flex-1 rounded-lg p-3 text-center" style="background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.25);">
                <p class="text-xs mb-1" style="color:rgba(255,255,255,.4);">Vidas</p>
                <p class="font-bold text-sm" id="dg_vidas" style="color:#fcd34d;">–</p>
            </div>
        </div>

        <!-- linhas de info -->
        <table class="w-full text-sm" id="detalheGerTabela">
            <tbody>
                <tr style="border-bottom:1px solid rgba(255,255,255,.06);">
                    <td class="py-2 pr-3" style="color:rgba(255,255,255,.4);width:40%;font-size:.73rem;">Código</td>
                    <td class="py-2 font-medium" id="dg_codigo" style="font-size:.73rem;"></td>
                </tr>
                <tr style="border-bottom:1px solid rgba(255,255,255,.06);">
                    <td class="py-2 pr-3" style="color:rgba(255,255,255,.4);font-size:.73rem;">Administradora</td>
                    <td class="py-2 font-medium" id="dg_admin" style="font-size:.73rem;"></td>
                </tr>
                <tr style="border-bottom:1px solid rgba(255,255,255,.06);">
                    <td class="py-2 pr-3" style="color:rgba(255,255,255,.4);font-size:.73rem;">Comissão %</td>
                    <td class="py-2 font-medium" id="dg_pct" style="font-size:.73rem;"></td>
                </tr>
                <tr style="border-bottom:1px solid rgba(255,255,255,.06);">
                    <td class="py-2 pr-3" style="color:rgba(255,255,255,.4);font-size:.73rem;">Data Cadastro</td>
                    <td class="py-2 font-medium" id="dg_criacao" style="font-size:.73rem;"></td>
                </tr>
                <tr>
                    <td class="py-2 pr-3" style="color:rgba(255,255,255,.4);font-size:.73rem;">Vencimento Boleto</td>
                    <td class="py-2 font-medium" id="dg_vencimento" style="font-size:.73rem;"></td>
                </tr>
                <tr id="dg_plano_row" style="border-top:1px solid rgba(255,255,255,.06);">
                    <td class="py-2 pr-3" style="color:rgba(255,255,255,.4);font-size:.73rem;">Plano</td>
                    <td class="py-2 font-medium" id="dg_plano" style="font-size:.73rem;"></td>
                </tr>
                <tr id="dg_corretor_row" style="border-top:1px solid rgba(255,255,255,.06);">
                    <td class="py-2 pr-3" style="color:rgba(255,255,255,.4);font-size:.73rem;">Corretor</td>
                    <td class="py-2 font-medium" id="dg_corretor" style="font-size:.73rem;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════ MODAL VALE ══════════════ -->
<div id="modalVale" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="rounded-lg shadow-lg p-6" style="background:#1e2a3a;color:#fff;width:480px;max-width:95%;">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold">Lançar Vale</h5>
            <button id="closeModalVale" class="text-gray-300 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <!-- Formulário -->
        <div class="mb-4">
            <label class="block text-sm mb-1">Vendedor</label>
            <select id="vale_user_id" class="w-full rounded px-2 py-1 text-black">
                <option value="">-- Selecionar --</option>
                @foreach($users_apto_apagar as $uu)
                    <option value="{{ $uu->user_id }}">{{ $uu->user }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Valor do Vale (R$)</label>
            <input type="text" id="vale_valor" class="w-full rounded px-2 py-1 text-black" placeholder="0,00">
        </div>
        <button id="btn_salvar_vale" class="w-full rounded py-2 font-bold text-white" style="background:rgba(76,175,80,0.85);">
            Salvar Vale
        </button>

        <!-- Listagem dos vales do mês -->
        <div class="mt-4">
            <p class="text-sm font-semibold mb-2" style="border-bottom:1px solid rgba(255,255,255,0.2);padding-bottom:4px;">Vales do Mês</p>
            <table class="w-full text-sm" id="tabela_vales_mes">
                <thead>
                    <tr style="opacity:0.7;">
                        <th class="text-left py-1">Vendedor</th>
                        <th class="text-right py-1">Valor</th>
                        <th class="py-1"></th>
                    </tr>
                </thead>
                <tbody id="tbody_vales">
                    <tr><td colspan="3" class="text-center py-2 opacity-50">Carregando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ══════════════ MODAL FINALIZAR MÊS ══════════════ -->
<div id="modalFinalizarMes" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="rounded-lg shadow-lg p-6" style="background:#1e2a3a;color:#fff;width:640px;max-width:95%;">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold" id="titulo_fechamento">Resumo do Fechamento</h5>
            <button id="closeModalFinalizar" class="text-gray-300 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <div id="resumo_fechamento_body">
            <div class="text-center py-6 opacity-50">Carregando...</div>
        </div>

        <div class="mt-4 flex gap-2">
            <button id="closeModalFinalizar2" class="flex-1 rounded py-2 text-sm font-bold text-white" style="background:rgba(100,100,100,0.8);">
                Cancelar
            </button>
            <button id="btn_confirmar_fechar_mes" class="flex-1 rounded py-2 text-sm font-bold text-white" style="background:rgba(211,47,47,0.85);">
                Fechar Mês
            </button>
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
                    let ano = $("#mes_folha option:selected").data("ano");


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


                                $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);

                                $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                                $("#valor_total_empresarial").text(res.total_empresarial);
                                $("#comissao_vendedor").val(res.total_comissao);
                                {{--$("#valores_confirmados").val(res.id_confirmados);--}}
                                $("#salario_vendedor").val(res.total_salario);
                                $("#premiacao_vendedor").val(res.total_premiacao);
                                $("#valor_total_estorno_vendedor").val("0,00");
                                $("#valor_total_desconto_vendedor").val("0,00");

                                $("#total_campo_vendedor").val(res.total);

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
                });

                $("#closeModalTipoPlanos").on('click', function() {
                    $("#exampleModalTipoPlanos").removeClass('flex').addClass('hidden');
                });

                $("body").on('click','.gerar_pdf_corretora_link',function(){

                    let dados = {
                        "individual": 0,
                        "coletivo": 0,
                        "empresarial": 1,
                        "user_id": $("#corretor_escolhido").val(),
                        "mes": $("#mes_folha option:selected").val(),
                        "ano": $("#mes_folha option:selected").data("ano"),
                        "tipo":"corretora"
                    };

                    let queryString = $.param(dados);

                    let url = "{{ route('gerente.finalizar.criarpdf') }}";
                    url += "?" + queryString;
                    window.open(url, '_blank');


                });





                $("body").on('change',".comissao_paga_change",function(){




                    let id = $("#escolher_vendedor").val();
                    let valor = $(this).val();
                    let valor_plano = $(this).attr('data-valor-plano');
                    let default_corretor = $(this).attr('data-id');
                    let self = $(this);

                    if(valor == 0) {
                        self.closest('tr').find('.porcentagem_change').val(0);
                    }


                    $.ajax({
                        url:"{{route('gerente.mudar.valor.corretor')}}",
                        method:"POST",
                        data:"id="+id+"&valor="+valor+"&valor_plano="+valor_plano+"&default_corretor="+default_corretor+"&acao=porcentagem",
                        success:function(res) {
                            //console.log(res);
                            // console.log("valor ",res.valor);
                            // console.log("porcentagem ",res.porcentagem);
                            self.closest('tr').find('.comissao_pagando').val(res.valor)
                            //self.closest('tr').find('.comissao_paga_change').val(res.porcentagem);
                            listarcomissaomesrecebidas.ajax.reload();
                        }
                    });
                });





                $("body").on('click', '.gerar_pdf_corretor_link', function () {
                    let dados = {
                        "individual": 0,
                        "coletivo": 0,
                        "empresarial": 1,
                        "user_id": $("#corretor_escolhido").val(),
                        "mes": $("#mes_folha option:selected").val(),
                        "ano": $("#mes_folha option:selected").data("ano"),
                        "tipo":"corretor"
                    };



                    let empresarialSelecionado = [];

                        empresarialSelecionado.push($(this).data("planos"));


                    if (empresarialSelecionado.length > 0) {
                        dados["empresarial_valores"] = empresarialSelecionado;
                    }

                    let queryString = $.param(dados);
                    let url = "{{ route('gerente.finalizar.criarpdf') }}";
                    url += "?" + queryString;
                    window.open(url, '_blank');
                    $("#exampleModalTipoPlanos").modal('hide');
                });


                $("body").on('click','.user_destaque',function(){
                    $("#loading-overlay").removeClass('ocultar');
                    let id = $(this).attr("data-id");
                    let nome_corretor = $(this).text();
                    $("#escolher_vendedor").find("option:eq(0)").prop("selected", true);
                    $(this).closest("ul").find('.total_pagamento_finalizado').removeClass('valor_total_change');
                    $(this).closest("li").find('.total_pagamento_finalizado').addClass('valor_total_change');
                    $("#corretor_escolhido").val(id);
                    $("#list_user ul li").removeClass('user_destaque_ativo');
                    $(this).closest("li").addClass('user_destaque_ativo');
                    $("#container_btns").removeClass('hidden').addClass("flex flex-col");
                    $("#container_btns").html(`

                    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 criar_pdf_corretor" data-id="${id}" style="font-size:0.8em;"  target="_blank">PDF Corretor</button>
                    <button class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 criar_pdf" data-id="${id}" style="font-size:0.8em;"  target="_blank">PDF Corretora</button>
                `).addClass('flex')
                    $(".listar_estorno_ul li").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#lista_apto_a_pagar_ul li").removeClass("ativo");
                    if($("#tabela_estorno_table").is(':visible')) {
                        $("#tabela_estorno").slideUp('fast',function(){
                            $("#tabela_principal").slideDown(1000,function(){
                                $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                listarcomissaomesrecebidas.ajax.url(`{{ url('gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                $(".individual_recebidas").addClass("ativo");
                            });
                        });
                    }

                    if($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('slow',function(){
                            $("#tabela_principal").slideDown(1000,function(){
                                $('#title_recebidas').html("<h4>Recebidas - Individual</h4>");
                                listarcomissaomesrecebidas.ajax.url(`{{ url('gerente/listagem/comissao_mes_atual/${id}') }}`).load();
                                $(".individual_recebidas").addClass("ativo");
                            });
                        })
                    }

                    if($("#tabela_aptos_a_pagar").is(":visible")) {
                        $("#tabela_aptos_a_pagar").slideUp(400);
                    }


                    $("#list_user").css({"height":"293px","max-height":"293px","overflow":"auto"})

                    //if($("#mes_folha").val() != "") {

                    let mes = $("#mes_folha").val();
                    let ano = $("#mes_folha option:selected").data("ano");
                    $.ajax({
                        url:"{{route('gerente.listagem.confirmadas.especifica')}}",
                        data:"mes="+mes+"&id="+id+"&ano="+ano,
                        method:"POST",
                        success:function(res) {
                            $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);

                            $("#salario_vendedor").val(res.salario);
                            $("#comissao_vendedor").val(res.comissao);
                            $("#premiacao_vendedor").val(res.premiacao);

                            $("#total_campo_vendedor").val(res.total);

                            $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                            $("#valor_total_empresarial").text(res.comissao);

                            $("#valores_confirmados").val(res.id_confirmados);

                            $(".total_a_pagar").text(res.total);


                            listaraptosapagar.ajax.reload(function() {
                                listaraptosapagar.clear().draw();
                            });
                        },
                        complete: function () {
                            // Esconder o overlay de loading
                            $("#loading-overlay").addClass('ocultar');
                        }
                    });
                    //}
                });



                $("body").on('click','.criar_pdf_corretor',function(){
                    $("#exampleModalTipoPlanos").removeClass('hidden').addClass('flex');
                });





                $('#mes_folha').on('change', function () {
                    const mes = $(this).val(); // Obtém o valor do mês selecionado
                    let ano = $(this).find('option:selected').data("ano");
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


                $("#listar_empresarial_apto").on('click', function() {
                    let id  = $("#corretor_escolhido").val();
                    let mes = $("#mes_folha option:selected").val();
                    let ano = $("#mes_folha option:selected").data("ano");

                    if (!id) {
                        toastr["error"]("Escolha um Corretor");
                        toastr.options = { 'time-out': 3000, 'close-button': true, 'position-class': 'toast-top-full-width' };
                        return;
                    }

                    $("#listar_coletivo_apto").removeClass("ativo");
                    $(".listar li").removeClass("ativo");
                    $("#listar_individual_apto").removeClass("ativo");
                    $(".listar_estorno_ul li").removeClass("ativo");
                    $("#listar_empresarial_apto").addClass("ativo");

                    let url = mes
                        ? `{{ url('/gerente/comissao/empresarial/confirmadas') }}/${id}/${mes}/${ano}`
                        : `{{ url('/gerente/comissao/empresarial/confirmadas') }}/${id}`;

                    function carregarEmpresarial() {
                        $("#title_individual_confirmados").html("<h4>Recebidas - Empresarial</h4>");
                        listaraptosapagar.ajax.url(url).load();
                        if (!$("#tabela_aptos_a_pagar").is(':visible')) {
                            $("#tabela_aptos_a_pagar").slideDown('slow');
                        }
                    }

                    if ($("#tabela_principal").is(':visible')) {
                        $("#tabela_principal").slideUp('fast', carregarEmpresarial);
                    } else if ($("#listar_a_receber").is(':visible')) {
                        $("#listar_a_receber").slideUp('fast', carregarEmpresarial);
                    } else if ($("#tabela_estorno_table").is(':visible')) {
                        $("#tabela_estorno").slideUp('fast', carregarEmpresarial);
                    } else if ($("#tabela_estorno_table_back").is(':visible')) {
                        $("#tabela_estorno_back").slideUp(400, carregarEmpresarial);
                    } else {
                        carregarEmpresarial();
                    }
                });

                var gerenteRowMap = {};

                var listaraptosapagar = $(".listaraptosapagar").DataTable({
                    dom: '<"flex justify-between items-center"<"#title_individual_confirmados"><"btns"B><"estilizar_search"f>>' +
                        'tr' +
                        '<"flex justify-between items-center"<"por_pagina"l><"estilizar_pagination"p>>',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Exportar para Excel',
                            className: 'bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 hidden',
                            exportOptions: {
                                columns: [0, 1, 3, 5, 9, 13, 14, 15, 16, 17]
                            }
                        },
                        {
                            text: 'Exportar Tudo para Excel',
                            className: 'bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 hidden',
                            action: function () {
                                exportAllToExcel();
                            }
                        }
                    ],
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
                        url:url_padrao,
                        dataSrc:""
                    },
                    "lengthMenu": [1000,2000,3000,4000,5000],
                    "ordering": false,
                    "paging": true,
                    "searching": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    processing: true,
                    columns: [
                        {data:"administradora",name:"administradora",width:"4%"},
                        {data:"created_at",name:"created_at",width:"4%"},
                        {data:"codigo",name:"codigo",width:"3%"},
                        {data:"cliente",name:"cliente",width:"18%"},
                        {data:"parcela",name:"parcela",width:"3%",className: 'dt-center'},
                        {data:"valor_plano",name:"valor_plano",width:"5%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'dt-center'},
                        {data:"vencimento",name:"vencimento",width:"5%",className: 'dt-center'},
                        {data:"data_baixa",name:"data_baixa",width:"5%"},

                        {data:"valor",name:"valor",width:"3%",render: $.fn.dataTable.render.number('.',',',2,'R$ '),className: 'comissao_receber'},
                        {data:"desconto",name:"desconto",className:"desconto_atual",render: $.fn.dataTable.render.number('.',',',2,'R$ '),width:"3%"},
                        {data:"id",name:"id",width:"5%",className: 'dt-center',
                            "createdCell": function (td, cellData, rowData, row, col) {

                                $(td).html(`





                                <svg id="${cellData}" data-plano="${rowData.plano}" class="w-6 h-6 text-white removeButton" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 14-4-4m4 4 4-4"/>
                                </svg>

                                `)
                            }
                        },
                        {
                            data: "contrato_id", name: "contrato_id", width: "1%",
                            render: function (data, type, row) {
                                if (type !== 'display') return data;
                                gerenteRowMap[data] = row;
                                return '<span class="ver-emp-btn" data-id="' + data + '" style="cursor:pointer;color:#93c5fd;display:inline-flex;align-items:center;justify-content:center;">'
                                    + '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:17px;height:17px;">'
                                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />'
                                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />'
                                    + '</svg></span>';
                            }
                        },
                        {data:"plano_nome",name:"plano_nome",visible:false},
                        {data:"corretor",name:"corretor",visible:false},
                        {data:"quantidade_vidas",name:"quantidade",visible:false},
                        {data:"parcela",name:"parcela",visible:false},
                        {data:"codigo_externo",name:"codigo_externo",visible:false},

                    ],
                    "drawCallback":function(settings) {
                        if(settings.aoData.length >= 1) {

                            let title = $(settings.nTableWrapper.childNodes[0].childNodes[0].childNodes[0]).text();

                            let soma = 0;
                            let columnIndex = 10;
                            $(settings.nTable).find('tbody tr').each(function() {
                                let valor = parseFloat($(this).find('td:eq(' + columnIndex + ')').text().replace(',', '.').replace("R$ ","")) || 0;
                                soma += valor;
                            });

                            if (/Individual/.test(title)) {
                                $("#desconto_individual").val(soma.toFixed(2));
                            } else if (/Coletivo/.test(title)) {
                                $("#desconto_coletivo").val(soma.toFixed(2));
                            } else if (/Empresarial/.test(title)) {
                                $("#desconto_empresarial").val(soma.toFixed(2));
                            }


                        }



                    },
                    "initComplete": function( settings, json ) {
                        $('#title_individual_confirmados').html("<h4>Recebidas - Individual</h4>");
                    },
                    footerCallback: function (row, data, start, end, display) {
                        var api = this.api();
                        var intVal = function (i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };
                        conditionalTotal = 0;
                        conditionalRecebida = 0;
                        api.rows( { search: "applied" } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                            var d = this.data();
                            conditionalTotal += intVal(d['comissao_esperada']);
                            conditionalRecebida += intVal(d['comissao_recebida']);
                            //qtdLinha = rowLoop + 1;
                        });
                        $("#previsao_de_comissao").html("Previsão da Comissão: "+conditionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                    }
                });




                $("body").on('click', '.criar_pdf', function(){
                    var user_id = $("#corretor_escolhido").val();
                    var mes     = $("#mes_folha option:selected").val();
                    var ano     = $("#mes_folha option:selected").data("ano");

                    // Reset
                    $('#tbody_preview_folha').html('<tr><td colspan="5" class="text-center py-6 text-gray-400">Carregando...</td></tr>');
                    $('#pill_comissao').text('–');
                    $('#pill_vale').text('–');
                    $('#pill_total').text('–');

                    $("#exampleModalTipoPlanosCorretora").removeClass('hidden').addClass('flex');

                    $.get('{{ route("gerente.folha.preview") }}', { user_id: user_id, mes: mes, ano: ano }, function(data) {
                        // Pills
                        var valeNum = parseFloat(data.total_vale.replace(/\./g,'').replace(',','.'));
                        $('#pill_comissao').text('R$ ' + data.total_comissao);
                        $('#pill_vale').text(valeNum > 0 ? '(R$ ' + data.total_vale + ')' : '–');
                        $('#pill_total').text('R$ ' + data.total_liquido);

                        // Tabela
                        var tbody = '';
                        if (!data.empresas.length) {
                            tbody = '<tr><td colspan="5" class="text-center py-6 text-gray-400">Nenhuma empresa encontrada</td></tr>';
                        } else {
                            $.each(data.empresas, function(i, e) {
                                tbody += '<tr style="border-bottom:1px solid #f3f4f6;">' +
                                    '<td class="py-2 pr-2 text-gray-400">' + (i+1) + '</td>' +
                                    '<td class="py-2 pr-2">' + e.cliente + '</td>' +
                                    '<td class="py-2 pr-2 text-gray-500">' + e.codigo_externo + '</td>' +
                                    '<td class="py-2 pr-2 text-gray-500">' + e.data + '</td>' +
                                    '<td class="py-2 text-right font-medium">R$ ' + e.comissao + '</td>' +
                                    '</tr>';
                            });
                        }
                        $('#tbody_preview_folha').html(tbody);
                    }).fail(function() {
                        $('#tbody_preview_folha').html('<tr><td colspan="5" class="text-center py-6 text-red-400">Erro ao carregar dados.</td></tr>');
                    });
                });

                $('#closeModalCorretora').on('click', function(){
                    $("#exampleModalTipoPlanosCorretora").addClass('hidden').removeClass('flex');
                });






                $('body').on('click', '.removeButton', function () {

                    $("#loading-overlay").removeClass('ocultar');
                    let row = listaraptosapagar.row($(this).parents('tr'));
                    let desconto = listaraptosapagar.row($(this).parents('tr')).data().desconto;
                    let comissao = listaraptosapagar.row($(this).parents('tr')).data().valor;

                    let id = $(this).attr('id');


                    // Adicionar requisição na fila
                    let requestData = {
                        id: id,
                        mes: $("#mes_folha").val(),
                        ano: $("#mes_folha option:selected").data("ano"),
                        user_id: $("#corretor_escolhido").val(),
                        salario: $("#salario_vendedor").val(),
                        premiacao: $("#premiacao_vendedor").val(),
                        comissao,
                        desconto,
                        total: $("#total_campo_vendedor").val(),
                    };

                    $.ajax({
                        url: "{{ route('gerente.mudar.para_a_nao_pago') }}",
                        method: "POST",
                        data: requestData,
                        success: function (res) {
                            $("#comissao").val(res.valores_geral.comissao);
                            $("#salario").val(res.valores_geral.salario);
                            $("#premiacao").val(res.valores_geral.premiacao);
                            $("#estorno_geral").val(res.valores_geral.estorno);
                            $("#valor_total_desconto").val(res.valores_geral.desconto);

                            $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);
                            $("#total_quantidade_individual").text(res.total_individual_quantidade);

                            $("#valor_total_individual").text(res.total_individual);

                            $(".quantidade_estorno_empresarial").text(res.total_empresarial_quantidade_estorno);

                            $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                            $("#valor_total_empresarial").text(res.total_empresarial);
                            $("#comissao_vendedor").val(res.total_comissao);
                            $("#valores_confirmados").val(res.id_confirmados);
                            $("#salario_vendedor").val(res.total_salario);
                            $("#premiacao_vendedor").val(res.total_premiacao);

                            $("#total_campo_vendedor").val(res.total);

                            $(".empresarial_a_receber").removeClass('ativo');

                            $(".empresarial_recebidas").removeClass('ativo');

                            $("#listar_empresarial_apto").removeClass('ativo');


                            $("#list_user").html(res.view);
                            $("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');
                            const select = $("#escolher_vendedor");
                            select.html('<option value="" class="text-center">--Corretores--</option>');
                            $.each(res.usuarios, function(index, corretor) {
                                const option = $("<option>").attr("value", corretor.id).text(corretor.name);
                                if (corretor.id == $("#corretor_escolhido").val()) {
                                    option.attr("selected", "selected");
                                }
                                select.append(option);
                            });
                            select.append("<option value='00'>--Finalizar--</option>");


                            // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                            if(parseFloat(res.total_comissao) > 0) {
                                $(".total_a_pagar").text(res.total);
                            } else {
                                $(".total_a_pagar").text(0);
                            }

                            listaraptosapagar.ajax.reload();
                        },
                        complete: function () {
                            $("#loading-overlay").addClass('ocultar');
                        },
                    });







                });










                $("body").on('click', '.pagar_comissao_up', function () {
                    // $("#loading-overlay").removeClass('ocultar');

                    // Coleta de dados
                    const elemento = $(this);
                    const mes = $("#mes_folha option:selected").val();
                    const ano = $("#mes_folha option:selected").data("ano");
                    const id = elemento.attr('id');
                    const plano = elemento.data('plano');
                    const linha = elemento.closest('tr');
                    const desconto = parseFloat(linha.find("input[name='porcentagem_change']").val().replace(/\./g, '').replace(',', '.'));
                    const comissao_pagando = linha.find('.comissao_pagando').val();
                    const comissao_recebida = comissao_pagando
                        ? parseFloat(comissao_pagando.replace(/\./g, '').replace(',', '.'))
                        : parseFloat(linha.find('.comissao_recebida').text().replace("R$", "").replace(/\./g, '').replace(',', '.'));




                    // Dados para enviar ao backend
                    const dadosRequest = {
                        id: id,
                        mes: mes,
                        ano: ano,
                        desconto: desconto,
                        salario: $("#salario_vendedor").val(),
                        comissao: comissao_recebida, // Ajuste conforme necessário
                        premiacao: $("#premiacao_vendedor").val(),
                        estorno: $("#valor_total_estorno_vendedor").val(),
                        user_id: $("#corretor_escolhido").val(),
                        total: $("#total_campo_vendedor").val()
                    };



                    // Requisição AJAX
                    $.ajax({
                        url: "{{route('gerente.aptar.pagamento')}}",
                        method: "POST",
                        data: dadosRequest,
                        success: function (res) {


                            if (res != "error") {

                                $(".valor_empresarial_a_receber").text(res.valor_empresarial_a_receber);
                                $("#total_quantidade_empresarial").text(res.total_empresarial_quantidade);
                                $("#valor_total_empresarial").text(res.total_empresarial);
                                $("#comissao_vendedor").val(res.total_comissao);
                                $("#valores_confirmados").val(res.id_confirmados);
                                $("#salario_vendedor").val(res.total_salario);
                                $("#premiacao_vendedor").val(res.total_premiacao);
                                $("#total_campo_vendedor").val(res.total);
                                $(".empresarial_a_receber").removeClass('ativo');
                                $(".empresarial_recebidas").removeClass('ativo');
                                $("#listar_empresarial_apto").removeClass('ativo');
                                $("#total_quantidade_empresarial_total").text(res.total_empresarial_quantidade_geral);
                                $("#valor_total_empresarial_total").text(res.total_empresarial_geral);
                                $("#salario").val(res.valores_geral.salario);
                                $("#comissao").val(res.valores_geral.comissao);
                                $("#premiacao").val(res.valores_geral.premiacao);
                                $("#estorno_geral").val(res.valores_geral.estorno);
                                $("#valor_total_desconto").val(res.valores_geral.desconto);
                                $("#total_campo").val(res.valores_geral.total)
                            //
                            //
                                $("#list_user").html(res.view);
                                $("#btn_fechar_mes").html('<button id="confirmBtn" >Fechar Mês</button>');
                                const select = $("#escolher_vendedor");
                                select.html('<option value="" class="text-center">--Corretores--</option>');
                                $.each(res.usuarios, function (index, corretor) {
                                    const option = $("<option>").attr("value", corretor.id).text(corretor.name);
                                    if (corretor.id == $("#corretor_escolhido").val()) {
                                        option.attr("selected", "selected");
                                    }
                                    select.append(option);
                                });
                                select.append("<option value='00'>--Finalizar--</option>");
                            //
                            //
                            //     // let total_a_pagar = parseFloat(res.total_comissao) - parseFloat(res.desconto)
                            //     if (parseFloat(res.total_comissao) > 0) {
                            //         $(".total_a_pagar").text(res.total);
                            //     } else {
                            //         $(".total_a_pagar").text(0);
                            //     }
                            }

                            listarcomissaomesdfirente.row(linha).remove().draw();
                        // },
                        // error: function (xhr) {
                        //     alert("Erro ao processar pagamento: " + xhr.responseText);
                        // },
                        // complete: function () {
                        //     $("#loading-overlay").addClass('ocultar');
                          }

                    });
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
                                $(td).html('<input type="text" value='+valor_comisao+' data-id='+cellData+' disabled name="comissao_pagando" class="comissao_pagando" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
                            }
                        },
                        {data:"quantidade_vidas",name:"quantidade_vidas",width:"3%",className: 'dt-center'},
                        {data:"desconto",name:"desconto",width:"8%",
                            "createdCell":function(td, cellData, rowData, row, col) {
                                let descondo_calc = parseFloat(cellData).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                $(td).html('<input disabled type="text" value='+descondo_calc+' data-id='+rowData.id+' name="porcentagem_change" class="porcentagem_change" style="width:80%; padding: 4px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 0.9rem; color: #000; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);" />')
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
                            render: function (data, type, row) {
                                if (type !== 'display') return data;
                                gerenteRowMap[data] = row;
                                return '<span class="ver-emp-btn" data-id="' + data + '" style="cursor:pointer;color:#93c5fd;display:inline-flex;align-items:center;justify-content:center;">'
                                    + '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:17px;height:17px;">'
                                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />'
                                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />'
                                    + '</svg></span>';
                            }
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
                        url:url_padrao,
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
                        {data:"administradora", name:"administradora", width:"5%"},
                        {data:"data_criacao", name:"data_criacao", width:"7%",
                            "createdCell": function(td, cellData) {
                                if (cellData) {
                                    $(td).html(cellData.split(" ")[0].split("-").reverse().join("/"));
                                }
                            }
                        },
                        {data:"orcamento", name:"orcamento", width:"5%"},
                        {data:"cliente", name:"cliente", width:"18%"},
                        {data:"parcela", name:"parcela", className:'dt-center', width:"3%"},
                        {data:"valor_plano_contratado", name:"valor_plano_contratado", width:"6%",
                            render: $.fn.dataTable.render.number('.',',',2,'R$ ')
                        },
                        {data:"data", name:"data", className:'dt-center', width:"6%"},
                        {data:"data_baixa_gerente", name:"data_baixa_gerente", width:"6%"},
                        {data:"comissao_recebida", name:"comissao_recebida", width:"7%",
                            render: $.fn.dataTable.render.number('.',',',2,'R$ ')
                        },
                        {data:"porcentagem_parcela_corretor", name:"porcentagem_parcela_corretor", width:"4%"},
                        {data:"id", name:"id", width:"5%", className:'dt-center',
                            "createdCell": function (td, cellData, rowData) {
                                $(td).html('<svg id="' + cellData + '" class="w-6 h-6 text-white pagar_comissao_up" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">'
                                    + '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"/>'
                                    + '</svg>');
                            }
                        },
                        {data:"quantidade_vidas", name:"quantidade_vidas", width:"3%", className:'dt-center'},
                        {data:"desconto", name:"desconto", width:"5%",
                            render: $.fn.dataTable.render.number('.',',',2,'R$ ')
                        },
                        {data:"plano", name:"plano", width:"4%"},
                        {data:"contrato_id", name:"contrato_id", width:"3%",
                            render: function (data, type, row) {
                                if (type !== 'display') return data;
                                gerenteRowMap[data] = row;
                                return '<span class="ver-emp-btn" data-id="' + data + '" style="cursor:pointer;color:#93c5fd;display:inline-flex;align-items:center;justify-content:center;">'
                                    + '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:17px;height:17px;">'
                                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />'
                                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />'
                                    + '</svg></span>';
                            }
                        },
                    ],

                    "initComplete": function( settings, json ) {},
                    footerCallback: function () {}
                });

                // ── Detalhe contrato (olhinho) ───────────────────────────────
                function fmtBrl(v) {
                    var n = parseFloat(v) || 0;
                    return 'R$ ' + n.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                function fmtDataGer(v) {
                    if (!v) return '–';
                    var s = String(v).split(' ')[0];
                    if (s.indexOf('-') > 0) return s.split('-').reverse().join('/');
                    return s;
                }



                $('body').on('click', '.ver-emp-btn', function (e) {
                    console.log("Olaaaaaaaaaaaaaaaaaaaaa");
                    e.preventDefault();
                    e.stopPropagation();
                    var id = $(this).attr('data-id');
                    var d = gerenteRowMap[id];
                    if (!d) return;

                    var cliente  = d.cliente   || d.razao_social || '–';
                    var codigo   = d.orcamento || d.codigo_externo || d.codigo || '–';
                    var admin    = d.administradora || 'Hapvida';
                    var valPlano = d.valor_plano_contratado || d.valor_plano || 0;
                    var comissao = d.valor     || d.comissao || 0;
                    var vidas    = d.quantidade_vidas || '–';
                    var pct      = d.porcentagem_parcela_corretor || d.porcentagem || '–';
                    var criacao  = fmtDataGer(d.data_criacao || d.created_at);
                    var vencto   = d.data      || d.vencimento || '–';
                    var planoNome = d.plano_nome || null;
                    var corretor  = d.corretor  || null;

                    $('#detalheGerTitulo').text(String(cliente).toUpperCase());
                    $('#detalheGerSub').text('Resumo do contrato');
                    $('#dg_valor_plano').text(fmtBrl(valPlano));
                    $('#dg_comissao').text(fmtBrl(comissao));
                    $('#dg_vidas').text(vidas || '–');
                    $('#dg_codigo').text(codigo);
                    $('#dg_admin').text(admin);
                    $('#dg_pct').text(pct && pct !== '–' ? pct + '%' : '–');
                    $('#dg_criacao').text(criacao);
                    $('#dg_vencimento').text(vencto);

                    if (planoNome) { $('#dg_plano').text(planoNome); $('#dg_plano_row').show(); }
                    else           { $('#dg_plano_row').hide(); }
                    if (corretor)  { $('#dg_corretor').text(corretor); $('#dg_corretor_row').show(); }
                    else           { $('#dg_corretor_row').hide(); }

                    $('#modalDetalheGerente').removeClass('hidden');
                });

                $('#closeModalDetalheGerente').on('click', function () {
                    $('#modalDetalheGerente').addClass('hidden');
                });
                $('#modalDetalheGerente').on('click', function (e) {
                    if ($(e.target).is('#modalDetalheGerente')) { $(this).addClass('hidden'); }
                });

            });

    // ══════════════ VALE ══════════════
    $('#vale_valor').mask('#.##0,00', {reverse: true});

    function carregarValesMes() {
        $.get('{{ route("gerente.vale.listar") }}', function(data) {
            var tbody = $('#tbody_vales');
            tbody.empty();
            if (!data.length) {
                tbody.append('<tr><td colspan="3" class="text-center py-2 opacity-50">Nenhum vale lançado</td></tr>');
                return;
            }
            $.each(data, function(i, v) {
                tbody.append(
                    '<tr>' +
                    '<td class="py-1">' + v.nome + '</td>' +
                    '<td class="text-right py-1">R$ ' + v.valor_fmt + '</td>' +
                    '<td class="text-right py-1">' +
                    '<button class="btn-excluir-vale text-red-400 hover:text-red-600 text-lg leading-none" data-id="' + v.id + '">&times;</button>' +
                    '</td>' +
                    '</tr>'
                );
            });
        });
    }

    $('#btn_vale').on('click', function() {
        carregarValesMes();
        $('#modalVale').removeClass('hidden');
    });

    $('#closeModalVale').on('click', function() {
        $('#modalVale').addClass('hidden');
    });

    $('#btn_salvar_vale').on('click', function() {
        var user_id = $('#vale_user_id').val();
        var valor_str = $('#vale_valor').val();
        var valor = parseFloat(valor_str.replace(/\./g,'').replace(',','.'));

        if (!user_id) { alert('Selecione o vendedor.'); return; }
        if (!valor || valor <= 0) { alert('Informe um valor válido.'); return; }

        $.ajax({
            url: '{{ route("gerente.vale.salvar") }}',
            method: 'POST',
            data: { user_id: user_id, valor: valor },
            success: function() {
                $('#vale_user_id').val('');
                $('#vale_valor').val('');
                carregarValesMes();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error ?? 'Erro ao salvar vale.');
            }
        });
    });

    $(document).on('click', '.btn-excluir-vale', function() {
        var id = $(this).data('id');
        if (!confirm('Excluir este vale?')) return;
        $.post('{{ route("gerente.vale.excluir") }}', { id: id }, function() {
            carregarValesMes();
        });
    });

    // ══════════════ FINALIZAR MÊS ══════════════
    $('#btn_finalizar_mes').on('click', function() {
        $('#resumo_fechamento_body').html('<div class="text-center py-6 opacity-50">Carregando...</div>');
        $('#modalFinalizarMes').removeClass('hidden');

        $.get('{{ route("gerente.fechamento.resumo") }}', function(data) {
            if (data.error) {
                $('#resumo_fechamento_body').html('<p class="text-red-400 text-center">' + data.error + '</p>');
                return;
            }

            $('#titulo_fechamento').text('Resumo – ' + data.mes_label);

            var html = '<table class="w-full text-sm">' +
                '<thead><tr style="opacity:0.7;border-bottom:1px solid rgba(255,255,255,0.2);">' +
                '<th class="text-left py-1">Vendedor</th>' +
                '<th class="text-right py-1">Comissão</th>' +
                '<th class="text-right py-1">Vale</th>' +
                '<th class="text-right py-1 font-bold">Total</th>' +
                '</tr></thead><tbody>';

            $.each(data.resumo, function(i, v) {
                html += '<tr style="border-bottom:1px solid rgba(255,255,255,0.08);">' +
                    '<td class="py-2">' + v.nome + '</td>' +
                    '<td class="text-right py-2">R$ ' + v.comissao + '</td>' +
                    '<td class="text-right py-2 text-orange-300">' + (parseFloat(v.vale.replace(',','.')) > 0 ? '(R$ ' + v.vale + ')' : '–') + '</td>' +
                    '<td class="text-right py-2 font-bold">R$ ' + v.total + '</td>' +
                    '</tr>';
            });

            html += '</tbody></table>';
            $('#resumo_fechamento_body').html(html);
        });
    });

    $('#closeModalFinalizar, #closeModalFinalizar2').on('click', function() {
        $('#modalFinalizarMes').addClass('hidden');
    });

    $('#btn_confirmar_fechar_mes').on('click', function() {
        Swal.fire({
            title: 'Fechar o mês?',
            text: 'Esta ação não pode ser desfeita. O mês será encerrado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#555',
            confirmButtonText: 'Sim, fechar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post('{{ route("gerente.fechamento.fechar") }}', {}, function() {
                    Swal.fire('Fechado!', 'A folha do mês foi encerrada.', 'success')
                        .then(function() { location.reload(); });
                }).fail(function(xhr) {
                    Swal.fire('Erro', xhr.responseJSON?.error ?? 'Erro ao fechar.', 'error');
                });
            }
        });
    });

    // ══════════════ EXPORTAR EXCEL ══════════════
    $('#criar_excel').on('click', function () {
        var mes = $("#mes_folha option:selected").val();
        var ano = $("#mes_folha option:selected").data("ano");

        if (!mes || !ano) {
            alert('Selecione o mês/ano antes de exportar.');
            return;
        }

        $("#loading-overlay").removeClass('ocultar');

        var url = '{{ route("gerente.exportar.excel") }}?mes=' + mes + '&ano=' + ano;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Erro ' + res.status);
            // Pega o nome do arquivo do header, se disponível
            var disposition = res.headers.get('Content-Disposition') || '';
            var match = disposition.match(/filename="?([^"]+)"?/);
            var filename = match ? match[1] : ('empresas_' + mes + '_' + ano + '.xlsx');
            return res.blob().then(function (blob) {
                return { blob: blob, filename: filename };
            });
        })
        .then(function (result) {
            var objectUrl = URL.createObjectURL(result.blob);
            var a = document.createElement('a');
            a.href     = objectUrl;
            a.download = result.filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(objectUrl);
        })
        .catch(function (err) {
            alert('Não foi possível gerar o arquivo: ' + err.message);
        })
        .finally(function () {
            $("#loading-overlay").addClass('ocultar');
        });
    });

        </script>
    @stop

    @section('css')
        <style>
            /* ═══════════════════════════════════════════
               GERENTE — DARK THEME
               ═══════════════════════════════════════════ */

            /* Page wrapper */
            .ger-page  { background: #0f1623; min-height: 100vh; padding: 28px 20px; }
            .ger-inner { max-width: 1800px; margin: 0 auto; }

            /* Header */
            .ger-header {
                display: flex; justify-content: space-between; align-items: center;
                margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
            }
            .ger-title { font-size: 1.45rem; font-weight: 800; color: #fff; margin: 0; }
            .ger-sub   { font-size: .78rem; color: rgba(255,255,255,.4); margin: 4px 0 0; }
            .ger-header-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

            /* Body layout */
            .ger-body {
                display: flex !important;
                gap: 12px;
                align-items: flex-start;
            }
            .ger-sidebar {
                flex: 0 0 195px;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            /* Cards */
            .ger-card {
                background: #151e30;
                border: 1px solid rgba(255,255,255,.07);
                border-radius: 14px;
                padding: 14px;
            }

            /* Tables area */
            .ger-tables-area { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 10px; }
            .ger-table-panel {
                background: #151e30;
                border: 1px solid rgba(255,255,255,.07);
                border-radius: 14px;
                padding: 14px;
                overflow-x: auto;
            }

            /* Select */
            .ger-select {
                width: 100%;
                background: #1a2540;
                color: #e2e8f0;
                border: 1px solid rgba(255,255,255,.12);
                border-radius: 8px;
                padding: 7px 10px;
                font-size: .78rem;
                outline: none;
                cursor: pointer;
            }
            .ger-select:focus { border-color: rgba(79,142,247,.6); }
            .ger-select option { background: #1a2540; color: #e2e8f0; }
            .ger-select:disabled { opacity: .7; cursor: not-allowed; }
            .ger-select-aberta {
                border: 2px solid #34d399 !important;
                box-shadow: 0 0 0 3px rgba(52,211,153,.18) !important;
            }
            .ger-folha-aberta-label {
                font-size: .68rem; font-weight: 700;
                color: #34d399;
                margin: 4px 0 0;
                letter-spacing: .03em;
            }

            /* Buttons */
            .ger-btn {
                display: inline-flex; align-items: center; justify-content: center;
                padding: 7px 14px; border-radius: 8px;
                font-size: .78rem; font-weight: 700; border: none; cursor: pointer;
                color: #fff; transition: opacity .2s, transform .15s;
            }
            .ger-btn:hover { opacity: .85; transform: translateY(-1px); }
            .ger-btn-orange { background: rgba(255,152,0,.9); }
            .ger-btn-red    { background: rgba(211,47,47,.9); }

            /* Excel button */
            .ger-excel-btn {
                display: inline-flex; align-items: center; gap: 4px;
                background: #34d399; color: #0f1623;
                border: none; border-radius: 7px;
                padding: 4px 10px; font-size: .72rem; font-weight: 700; cursor: pointer;
                transition: background .2s;
            }
            .ger-excel-btn:hover { background: #10b981; }

            /* Stat block */
            .ger-stat-block { display: flex; flex-direction: column; gap: 0; }
            .ger-stat-row   { display: flex; align-items: center; justify-content: space-between; padding: 4px 0; }
            .ger-stat-lbl   { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.38); }
            .ger-stat-input {
                background: #1a2540 !important;
                color: #e2e8f0 !important;
                border: 1px solid rgba(255,255,255,.1) !important;
                border-radius: 6px;
                padding: 3px 7px !important;
                font-size: .75rem;
                text-align: right;
                width: 105px;
            }
            .ger-stat-input-total { color: #34d399 !important; font-weight: 700; }
            .ger-stat-divider { height: 1px; background: rgba(255,255,255,.06); margin: 1px 0; }

            /* Confirmed box */
            .ger-confirmed-box { background: rgba(255,255,255,.04); border-radius: 10px; padding: 8px; }
            .ger-confirmed-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
            .ger-confirmed-header > span { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.4); }
            .ger-confirmed-section-title { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.4); margin: 0 0 5px; text-align: center; }
            .ger-confirmed-list { list-style: none; margin: 0; padding: 0; }
            .ger-confirmed-list li { display: flex; align-items: center; justify-content: space-between; padding: 2px 0; }
            .ger-confirmed-lbl { font-size: .65rem; color: rgba(255,255,255,.6); flex: 1; }
            .ger-confirmed-qty { font-size: .65rem; color: rgba(255,255,255,.5); width: 22px; text-align: center; }
            .ger-confirmed-val { font-size: .68rem; color: #34d399; font-weight: 700; }

            .ger-confirmed-list li.ativo {
                background: rgba(52,211,153,.18) !important;
                border-radius: 6px !important;
                border: 1px solid rgba(52,211,153,.5) !important;
                padding: 3px 5px !important;
            }
            .ger-confirmed-list li.ativo .ger-confirmed-lbl { color: #34d399 !important; }
            .ger-confirmed-list li.ativo .ger-confirmed-qty { color: #34d399 !important; }
            .ger-confirmed-list li.ativo .ger-confirmed-val { color: #fff !important; }

            /* User list */
            .ger-user-list { background: rgba(255,255,255,.04); border-radius: 10px; padding: 8px; }
            .ger-user-list-title {
                font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
                color: rgba(255,255,255,.38); margin: 0 0 6px;
                border-bottom: 1px solid rgba(255,255,255,.08); padding-bottom: 4px; text-align: center;
            }
            .ger-user-list ul { list-style: none; margin: 0; padding: 0; height: 160px; overflow-y: auto; }
            .ger-user-list ul::-webkit-scrollbar { width: 2px; }
            .ger-user-list ul::-webkit-scrollbar-thumb { background: #4f8ef7; }
            .ger-user-list li { display: flex; justify-content: space-between; align-items: center; padding: 3px 2px; border-radius: 4px; }
            .ger-user-list li:hover { background: rgba(79,142,247,.12); cursor: pointer; }

            /* ═══════════════════════════════════════════
               DATATABLES — DARK OVERRIDE
               ═══════════════════════════════════════════ */
            .ger-table-panel table.dataTable,
            .ger-table-panel table.dataTable tbody td,
            .ger-table-panel table.dataTable thead th {
                color: #cbd5e1 !important;
                background: transparent !important;
                border-color: rgba(255,255,255,.06) !important;
            }
            .ger-table-panel table.dataTable tbody td { font-size: 0.73em !important; padding: 6px 4px !important; }
            .ger-table-panel table.dataTable thead th {
                font-size: 0.72em !important; padding: 8px 4px !important;
                border-bottom: 2px solid rgba(255,255,255,.12) !important;
                color: rgba(255,255,255,.45) !important;
                text-transform: uppercase; letter-spacing: .05em;
            }
            .ger-table-panel .dataTables_wrapper .dataTable tbody tr:hover {
                background: rgba(79,142,247,.1) !important; color: #fff !important;
            }
            .ger-table-panel .dataTables_wrapper .dataTable tbody tr:hover td { color: #fff !important; }
            .ger-table-panel table.dataTable tbody tr:nth-child(even) { background: rgba(255,255,255,.02) !important; }

            /* Search input inside ger-table-panel */
            .ger-table-panel .dataTables_filter label { color: rgba(255,255,255,.5); font-size: .78rem; }
            .ger-table-panel .dataTables_filter input[type="search"] {
                background: #1a2540 !important; color: #fff !important;
                border: 1px solid rgba(255,255,255,.15) !important;
                border-radius: 7px !important; padding: 5px 10px !important; outline: none;
            }
            .ger-table-panel .dataTables_length label { color: rgba(255,255,255,.5); font-size: .78rem; }
            .ger-table-panel .dataTables_length select {
                background: #1a2540 !important; color: #fff !important;
                border: 1px solid rgba(255,255,255,.15) !important;
                border-radius: 6px; padding: 3px 6px; margin: 0 4px;
            }
            .ger-table-panel .dataTables_info { color: rgba(255,255,255,.35) !important; font-size: .75rem; }
            .ger-table-panel .dataTables_paginate .paginate_button { color: rgba(255,255,255,.55) !important; border-radius: 6px !important; }
            .ger-table-panel .dataTables_paginate .paginate_button:hover { background: rgba(79,142,247,.2) !important; color: #fff !important; border-color: transparent !important; }
            .ger-table-panel .dataTables_paginate .paginate_button.current,
            .ger-table-panel .dataTables_paginate .paginate_button.current:hover {
                background: #4f8ef7 !important; color: #fff !important; border-color: transparent !important;
            }
            .ger-table-panel .dataTables_paginate .paginate_button.disabled,
            .ger-table-panel .dataTables_paginate .paginate_button.disabled:hover { color: rgba(255,255,255,.2) !important; }

            /* ═══════════════════════════════════════════
               PRESERVED UTILITIES
               ═══════════════════════════════════════════ */
            .loading-dots {display: flex;justify-content: center;align-items: center;}
            .loading-dots div {width: 12px;height: 12px;margin: 10px 4px;border-radius: 50%;background-color: #333;animation: loading-dots 1.2s infinite ease-in-out;}
            .loading-dots div:nth-child(1) {animation-delay: 0s;}
            .loading-dots div:nth-child(2) {animation-delay: 0.2s;}
            .loading-dots div:nth-child(3) {animation-delay: 0.4s;}
            @keyframes loading-dots {
                0%, 80%, 100% {transform: scale(0);}
                40% {transform: scale(1);}
            }
            .pagar_comissao_up {border:1px solid rgba(255,255,255,.3);padding:3px;cursor:pointer;border-radius:4px;}
            .pagar_comissao_up:hover,.pagar_comissao_up:focus {border:1px solid orange;background-color:orange;color:black;}
            .valores_em_destaque {color:#0f1623;background-color:#34d399;padding:3px;border-radius:50%;font-size:0.9em;margin:2px;width:22px;height:22px;display:flex;justify-content:center;align-items:center;font-weight:bold;}
            .tamanho_de_25 {height: 36px;}
            .dsnone {display:none;}
            .ocultar {display:none !important;}
            .aba_comissao_container { display:flex !important; position:relative; flex-basis:100%; }
            .textoforte {background-color:rgba(255,255,255,.12) !important;color:#fff;}
            .textoforte td {color:#fff !important;}
            .ativo {background-color:#fff !important;color:#000 !important;}
            .user_nome {font-size: 0.7em;flex: 1;white-space: nowrap;overflow: hidden;text-overflow: ellipsis; color: #e2e8f0;}
            .user_total {font-size: 0.6em;flex-shrink: 0;margin-left: 5px; color: #34d399;}
            .user_destaque_impar {background-color:rgba(255,255,255,.04) !important;}
            .user_destaque_ativo {background-color:rgba(79,142,247,.25) !important;color:#fff !important;}
            .user_destaque_ativo .user_nome, .user_destaque_ativo .user_total { color: #fff !important; }
            .dt-right {text-align: right !important;}
            .dt-center {text-align: center !important;}
            .por_pagina {font-size: 12px !important;color:#e2e8f0;}
            .estilizar_pagination .pagination {font-size: 0.8em !important;}
            .estilizar_search input[type='search'] {
                background: #1a2540 !important; color: #fff !important;
                border: 1px solid rgba(255,255,255,.15) !important;
                border-radius: 7px !important; padding: 5px 10px !important;
            }
            #tabela_aptos_a_pagar_table td,
            #tabela_mes_diferente td { white-space: nowrap; overflow: hidden; text-overflow: clip; }
            #title_comissao_diferente,#title_recebidas,#title_cadastrados { margin:5px 0 0 9px; color: rgba(255,255,255,.7); font-size: .85rem; font-weight: 700; }
            /* PDF/corretor buttons generated dynamically */
            .criar_pdf_corretor {
                background: #4f8ef7 !important; color: #fff !important;
                border: none !important; border-radius: 7px !important;
                padding: 5px 10px !important; font-size: .75rem !important; cursor: pointer;
            }
            .criar_pdf {
                background: #1a2540 !important; color: #e2e8f0 !important;
                border: 1px solid rgba(255,255,255,.2) !important; border-radius: 7px !important;
                padding: 5px 10px !important; font-size: .75rem !important; cursor: pointer;
            }
            .criar_pdf:hover { background: rgba(79,142,247,.2) !important; }
        </style>

    @stop
</x-app-layout>

