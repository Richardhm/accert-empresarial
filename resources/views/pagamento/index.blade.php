<x-app-layout>
    @section('css')
        <link rel="stylesheet" href="{{ asset('css/estilo-financeiro.css') }}"/>
    @endsection

    <script>
        var urlListarPagamento      = "{{ route('pagamento.listar') }}";
        var urlUploadPlanilha       = "{{ route('pagamento.upload_planilha') }}";
        var urlDetalhePagamento     = "{{ url('/pagamento/detalhe') }}";
        var urlNaoVinculados        = "{{ route('pagamento.nao_vinculados') }}";
        var urlBuscarContratos      = "{{ route('pagamento.buscar_contratos') }}";
        var urlVincularBase         = "{{ url('/pagamento/vincular') }}";
        var appAssetUrl             = "{{ asset('') }}";
        var csrfToken               = "{{ csrf_token() }}";
    </script>

    <div id="pagamento-page" class="fin-page">
        <div class="fin-inner">

            {{-- ── Cabeçalho ── --}}
            <div class="fin-header">
                <div>
                    <h1 class="fin-title">Pagamento</h1>
                    <p class="fin-sub">Contratos finalizados — todas as etapas concluídas</p>
                </div>
                <a href="{{ route('financeiro.index') }}" class="fin-btn-new" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;margin-right:6px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Financeiro
                </a>
            </div>

            <div class="fin-panel">

                {{-- ── Linha 1: stats + corretor/export ── --}}
                <div class="fin-toolbar">
                    <div class="fin-stats-row">
                        <div class="fin-stat-chip chip-purple">
                            <span class="chip-lbl">Contratos</span>
                            <span class="chip-val pag-total-contratos">0</span>
                        </div>
                        <div class="fin-stat-chip chip-blue">
                            <span class="chip-lbl">Vidas</span>
                            <span class="chip-val pag-total-vidas">0</span>
                        </div>
                        <div class="fin-stat-chip chip-green">
                            <span class="chip-lbl">Total</span>
                            <span class="chip-val pag-total-valor" style="font-size:.9rem;">R$ 0,00</span>
                        </div>
                    </div>

                    <div class="fin-selects-row">
                        <div class="fin-select-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(255,255,255,.4)"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            <select id="mudar_user_pagamento" class="fin-select-inline">
                                <option value="">— Todos os Corretores —</option>
                            </select>
                        </div>
                        <div class="fin-select-wrap" id="filtro-mes-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(255,255,255,.4)"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                            <select id="filtro_mes_pagamento" class="fin-select-inline" style="display:none;">
                                <option value="">— Todos os Meses —</option>
                            </select>
                        </div>
                        <button id="btn-toggle-upload"
                            style="display:inline-flex;align-items:center;gap:6px;background:rgba(79,142,247,.12);border:1px solid rgba(79,142,247,.3);color:#93c5fd;padding:6px 14px;border-radius:9px;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;white-space:nowrap;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width:13px;height:13px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                            </svg>
                            Atualizar
                        </button>
                        <button id="btn-nao-vinculados"
                            style="display:none;align-items:center;gap:6px;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.35);color:#fca5a5;padding:6px 14px;border-radius:9px;font-size:.78rem;font-weight:700;cursor:pointer;transition:all .2s;white-space:nowrap;">
                            ⚠ <span id="nao-vinc-count">0</span> não vinculados
                        </button>
                        <div id="dt-export-btn-wrap-pag"></div>
                    </div>
                </div>

                {{-- ── Linha 2: todos os filtros numa linha só ── --}}
                <div class="fin-filters-row" style="flex-wrap:nowrap;gap:6px;overflow-x:auto;padding-bottom:2px;">

                    {{-- Planos (gerado pelo JS) --}}
                    <fieldset class="fin-filter-fieldset" id="plano-filter-fieldset-pag" style="border-color:rgba(59,130,246,.35);">
                        <legend class="fin-filter-legend" style="color:rgba(59,130,246,.7);">Planos</legend>
                        <div id="plano-filter-btns-pag" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
                    </fieldset>

                    {{-- Saúde e Odonto --}}
                    <fieldset class="fin-filter-fieldset" style="border-color:rgba(52,211,153,.35);">
                        <legend class="fin-filter-legend" style="color:rgba(52,211,153,.7);">Saúde e Odonto</legend>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            <button class="tipo-tag-btn tipo-tag-ativo" data-tipo=""
                                style="background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.18);color:rgba(255,255,255,.65);">
                                <span class="plano-tag-dot" style="background:rgba(255,255,255,.4);"></span>Todos
                            </button>
                            <button class="tipo-tag-btn" data-tipo="ambos"
                                data-bg="rgba(139,92,246,.1)" data-border="rgba(139,92,246,.4)" data-text="#c4b5fd" data-active="#8b5cf6" data-dot="#8b5cf6"
                                style="background:rgba(139,92,246,.1);border-color:rgba(139,92,246,.4);color:#c4b5fd;">
                                <span class="plano-tag-dot" style="background:#8b5cf6;"></span>Saúde + Odonto
                            </button>
                            <button class="tipo-tag-btn" data-tipo="saude"
                                data-bg="rgba(34,197,94,.1)" data-border="rgba(34,197,94,.4)" data-text="#86efac" data-active="#22c55e" data-dot="#22c55e"
                                style="background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.4);color:#86efac;">
                                <span class="plano-tag-dot" style="background:#22c55e;"></span>Saúde
                            </button>
                            <button class="tipo-tag-btn" data-tipo="odonto"
                                data-bg="rgba(59,130,246,.1)" data-border="rgba(59,130,246,.4)" data-text="#93c5fd" data-active="#3b82f6" data-dot="#3b82f6"
                                style="background:rgba(59,130,246,.1);border-color:rgba(59,130,246,.4);color:#93c5fd;">
                                <span class="plano-tag-dot" style="background:#3b82f6;"></span>Odonto
                            </button>
                        </div>
                    </fieldset>

                    {{-- Status de Recebimento --}}
                    <fieldset class="fin-filter-fieldset" style="flex:1;min-width:0;border-color:rgba(251,191,36,.35);">
                        <legend class="fin-filter-legend" style="color:rgba(251,191,36,.7);">Status de Recebimento</legend>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">

                            <button class="status-tag-btn status-tag-ativo" data-status=""
                                style="background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.35);color:#fff;">
                                <span class="plano-tag-dot" style="background:rgba(255,255,255,.6);"></span>Todos
                                <span class="status-tag-count">0</span>
                            </button>

                            <button class="status-tag-btn" data-status="sem_pagamento"
                                data-bg="rgba(248,113,113,.1)" data-border="rgba(248,113,113,.4)" data-text="#fca5a5" data-active="#ef4444"
                                style="background:rgba(248,113,113,.1);border-color:rgba(248,113,113,.4);color:#fca5a5;">
                                <span class="plano-tag-dot" style="background:#f87171;"></span>Sem Pagamento
                                <span class="status-tag-count">0</span>
                            </button>

                            <button class="status-tag-btn" data-status="saude_so_agenciamento"
                                data-bg="rgba(34,197,94,.1)" data-border="rgba(34,197,94,.4)" data-text="#86efac" data-active="#22c55e"
                                style="background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.4);color:#86efac;">
                                <span class="plano-tag-dot" style="background:#22c55e;"></span>Saúde · Agenciamento
                                <span class="status-tag-count">0</span>
                            </button>

                            <button class="status-tag-btn" data-status="saude_so_recorrencia"
                                data-bg="rgba(52,211,153,.1)" data-border="rgba(52,211,153,.4)" data-text="#6ee7b7" data-active="#10b981"
                                style="background:rgba(52,211,153,.1);border-color:rgba(52,211,153,.4);color:#6ee7b7;">
                                <span class="plano-tag-dot" style="background:#10b981;"></span>Saúde · Recorrência
                                <span class="status-tag-count">0</span>
                            </button>

                            <button class="status-tag-btn" data-status="odonto_so_agenciamento"
                                data-bg="rgba(59,130,246,.1)" data-border="rgba(59,130,246,.4)" data-text="#93c5fd" data-active="#3b82f6"
                                style="background:rgba(59,130,246,.1);border-color:rgba(59,130,246,.4);color:#93c5fd;">
                                <span class="plano-tag-dot" style="background:#3b82f6;"></span>Odonto · Agenciamento
                                <span class="status-tag-count">0</span>
                            </button>

                            <button class="status-tag-btn" data-status="odonto_so_recorrencia"
                                data-bg="rgba(99,102,241,.1)" data-border="rgba(99,102,241,.4)" data-text="#a5b4fc" data-active="#6366f1"
                                style="background:rgba(99,102,241,.1);border-color:rgba(99,102,241,.4);color:#a5b4fc;">
                                <span class="plano-tag-dot" style="background:#6366f1;"></span>Odonto · Recorrência
                                <span class="status-tag-count">0</span>
                            </button>

                            <button class="status-tag-btn" data-status="gap_recorrencia"
                                data-bg="rgba(251,191,36,.1)" data-border="rgba(251,191,36,.4)" data-text="#fde68a" data-active="#f59e0b"
                                style="background:rgba(251,191,36,.1);border-color:rgba(251,191,36,.4);color:#fde68a;">
                                <span class="plano-tag-dot" style="background:#fbbf24;"></span>Gap na Recorrência
                                <span class="status-tag-count">0</span>
                            </button>

                        </div>
                    </fieldset>

                </div>

                {{-- ── Matriz Visão por Plano ── --}}
                <div id="pag-matrix-section" class="pag-matrix-section" style="display:none;">
                    <div class="pag-matrix-head">
                        <span class="pag-matrix-title">Visão por Plano</span>
                        <button id="pag-matrix-toggle-btn" class="pag-matrix-toggle">▲ Recolher</button>
                    </div>
                    <div id="pag-matrix-body" class="pag-matrix-body">
                        <div id="pag-matrix-container"></div>
                    </div>
                </div>


                {{-- ── Tabela ── --}}
                <table id="tabela_pagamento"
                       class="table table-sm text-left listarpagamento"
                       style="table-layout:fixed;width:100%;">
                    <thead>
                        <tr style="font-size:0.9em;">
                            <th class="dt-center">Tipo</th>
                            <th>Plano</th>
                            <th>Cadastro</th>
                            <th>Código</th>
                            <th>CNPJ</th>
                            <th>Cliente</th>
                            <th>UF</th>
                            <th>Cidade</th>
                            <th>Vendedor</th>
                            <th class="dt-center">Vidas</th>
                            <th class="dt-right">Valor</th>
                            <th class="dt-right">Comissões</th>
                            <th class="dt-center">Últ. Parcela</th>
                            <th class="dt-center">Detalhe</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- ── Modal Atualizar (Upload de Planilhas) ── --}}
    <div id="modal-atualizar-overlay" class="modal-colar-overlay" style="display:none;">
        <div class="modal-colar-box" style="width:93vw;max-width:93vw;">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title">Atualizar Planilhas</p>
                    <p class="modal-colar-sub">Selecione o tipo de planilha para importar</p>
                </div>
                <button class="modal-colar-close" id="btn-fechar-modal-atualizar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body" style="padding-top:0;padding-bottom:22px;">
                <div class="pag-upload-row" style="margin:0;">

                    {{-- Grupo Saúde --}}
                    <fieldset class="pag-upload-fieldset pag-upload-fieldset-saude">
                        <legend class="pag-upload-legend pag-upload-legend-saude">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:12px;height:12px;">
                                <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/>
                            </svg>
                            Saúde
                        </legend>

                        <button class="pag-upload-card" data-tipo="agenciamento_saude">
                            <div class="pag-upload-icon" style="background:rgba(34,197,94,.12);border-color:rgba(34,197,94,.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:20px;height:20px;">
                                    <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#34d399" style="width:14px;height:14px;margin-left:4px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <div class="pag-upload-info">
                                <span class="pag-upload-label" style="color:#34d399;">Agenciamento</span>
                                <span class="pag-upload-sub">Saúde</span>
                            </div>
                            <div class="pag-upload-badge" style="border-color:rgba(34,197,94,.4);color:#34d399;">XLS</div>
                        </button>

                        <button class="pag-upload-card" data-tipo="recorrencia_saude">
                            <div class="pag-upload-icon" style="background:rgba(52,211,153,.12);border-color:rgba(52,211,153,.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#6ee7b7" style="width:20px;height:20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#6ee7b7" style="width:14px;height:14px;margin-left:4px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <div class="pag-upload-info">
                                <span class="pag-upload-label" style="color:#6ee7b7;">Recorrência</span>
                                <span class="pag-upload-sub">Saúde</span>
                            </div>
                            <div class="pag-upload-badge" style="border-color:rgba(52,211,153,.4);color:#6ee7b7;">XLS</div>
                        </button>
                    </fieldset>

                    {{-- Grupo Odonto --}}
                    <fieldset class="pag-upload-fieldset pag-upload-fieldset-odonto">
                        <legend class="pag-upload-legend pag-upload-legend-odonto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:12px;height:12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/>
                            </svg>
                            Odonto
                        </legend>

                        <button class="pag-upload-card" data-tipo="agenciamento_odonto">
                            <div class="pag-upload-icon" style="background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:20px;height:20px;">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#93c5fd" style="width:14px;height:14px;margin-left:4px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <div class="pag-upload-info">
                                <span class="pag-upload-label" style="color:#93c5fd;">Agenciamento</span>
                                <span class="pag-upload-sub">Odonto</span>
                            </div>
                            <div class="pag-upload-badge" style="border-color:rgba(59,130,246,.4);color:#93c5fd;">XLS</div>
                        </button>

                        <button class="pag-upload-card" data-tipo="recorrencia_odonto">
                            <div class="pag-upload-icon" style="background:rgba(99,102,241,.12);border-color:rgba(99,102,241,.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#a5b4fc" style="width:20px;height:20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#a5b4fc" style="width:14px;height:14px;margin-left:4px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <div class="pag-upload-info">
                                <span class="pag-upload-label" style="color:#a5b4fc;">Recorrência</span>
                                <span class="pag-upload-sub">Odonto</span>
                            </div>
                            <div class="pag-upload-badge" style="border-color:rgba(99,102,241,.4);color:#a5b4fc;">XLS</div>
                        </button>
                    </fieldset>

                </div>
            </div>
        </div>
    </div>
    {{-- ── Modal Detalhe Pagamentos ── --}}
    <div id="modal-detalhe-overlay" class="modal-colar-overlay" style="display:none;">
        <div class="modal-colar-box" style="max-width:860px;width:95vw;">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modal-detalhe-titulo">Pagamentos do Contrato</p>
                    <p class="modal-colar-sub" id="modal-detalhe-sub"></p>
                </div>
                <button class="modal-colar-close" id="btn-fechar-modal-detalhe">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body" style="padding:0 0 18px;">
                <div id="modal-detalhe-loading" style="display:flex;justify-content:center;padding:40px;">
                    <div class="dot-flashing"><div></div><div></div><div></div></div>
                </div>
                <div id="modal-detalhe-content" style="display:none;overflow-x:auto;padding:0 22px;">
                    <table id="tabela-detalhe-pagamentos"
                        style="width:100%;border-collapse:collapse;font-size:.73rem;color:#cbd5e1;">
                        <thead>
                            <tr style="border-bottom:1px solid rgba(255,255,255,.1);text-align:left;">
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">Tipo</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Parcela</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Vencimento</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;text-align:right;">VL Base</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;text-align:right;">% Imp.</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;text-align:right;">VL Líquido</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;text-align:right;">% Dist.</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;text-align:right;">VL a Pagar</th>
                                <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Arquivo</th>
                            </tr>
                        </thead>
                        <tbody id="modal-detalhe-tbody"></tbody>
                    </table>
                    <div id="modal-detalhe-vazio" style="display:none;text-align:center;padding:32px;color:rgba(255,255,255,.3);font-size:.82rem;">
                        Nenhum pagamento encontrado para este contrato.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Upload Excel ── --}}
    <div id="modal-upload-excel-overlay" class="modal-colar-overlay" style="display:none;">
        <div class="modal-colar-box" style="max-width:460px;">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modal-upload-excel-title">Upload Excel</p>
                    <p class="modal-colar-sub">Selecione o arquivo <strong style="color:rgba(255,255,255,.55);">.xlsx</strong> ou <strong style="color:rgba(255,255,255,.55);">.csv</strong></p>
                </div>
                <button class="modal-colar-close" id="btn-fechar-modal-upload-excel">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">

                {{-- Dropzone --}}
                <label for="input-upload-excel" id="pag-upload-dropzone"
                    style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;
                           border:2px dashed rgba(255,255,255,.18);border-radius:12px;padding:32px 16px;
                           cursor:pointer;transition:border-color .2s,background .2s;background:rgba(255,255,255,.02);
                           min-height:120px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(255,255,255,.35)" style="width:36px;height:36px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                    <span id="pag-dropzone-label-text" style="color:rgba(255,255,255,.45);font-size:.82rem;text-align:center;">
                        Clique para selecionar ou arraste o arquivo aqui
                    </span>
                    <span style="font-size:.7rem;color:rgba(255,255,255,.25);">.xlsx · .csv</span>
                </label>
                <input type="file" id="input-upload-excel"
                    accept=".xlsx,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv"
                    style="display:none;">

                {{-- Nome do arquivo selecionado --}}
                <div id="pag-upload-file-info" style="display:none;margin-top:10px;padding:8px 12px;
                     background:rgba(79,142,247,.08);border:1px solid rgba(79,142,247,.25);border-radius:8px;
                     display:none;align-items:center;gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:16px;height:16px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                    <span id="pag-upload-file-name" style="color:#93c5fd;font-size:.78rem;word-break:break-all;"></span>
                </div>

                {{-- Botões --}}
                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:18px;">
                    <button id="btn-cancelar-upload-excel"
                        style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.6);
                               padding:9px 20px;border-radius:9px;font-size:.82rem;font-weight:600;cursor:pointer;transition:background .2s;">
                        Cancelar
                    </button>
                    <button id="btn-enviar-upload-excel" disabled
                        style="background:rgba(79,142,247,.25);border:1px solid rgba(79,142,247,.35);color:rgba(79,142,247,.5);
                               padding:9px 20px;border-radius:9px;font-size:.82rem;font-weight:700;cursor:not-allowed;transition:all .2s;">
                        Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Não Vinculados ── --}}
    <div id="modal-nao-vinc-overlay" class="modal-colar-overlay" style="display:none;">
        <div class="modal-colar-box" style="max-width:980px;width:95vw;">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" style="color:#fca5a5;">⚠ Registros Não Vinculados</p>
                    <p class="modal-colar-sub" id="nv-header-sub">Empresa da planilha não encontrada no sistema — pesquise e vincule ao contrato correto</p>
                </div>
                <button class="modal-colar-close" id="btn-fechar-modal-nao-vinc">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body" style="padding:0 0 18px;">
                <div id="nv-loading" style="display:flex;justify-content:center;padding:40px;">
                    <div class="dot-flashing"><div></div><div></div><div></div></div>
                </div>
                <div id="nv-content" style="display:none;">
                    <div id="nv-vazio" style="display:none;text-align:center;padding:48px 22px;">
                        <div style="font-size:2.2rem;margin-bottom:10px;">✓</div>
                        <div style="color:#34d399;font-weight:700;font-size:.95rem;">Todos os registros foram vinculados!</div>
                        <div style="color:rgba(255,255,255,.35);font-size:.8rem;margin-top:4px;">Nenhum registro pendente.</div>
                    </div>
                    <div id="nv-table-wrap" style="overflow-x:auto;padding:0 22px;">
                        <table style="width:100%;border-collapse:collapse;font-size:.73rem;color:#cbd5e1;">
                            <thead>
                                <tr style="border-bottom:1px solid rgba(255,255,255,.1);text-align:left;">
                                    <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Empresa (Planilha)</th>
                                    <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Tipo</th>
                                    <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;text-align:center;">Parcela</th>
                                    <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Vencimento</th>
                                    <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;text-align:right;">Valor</th>
                                    <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;">Arquivo</th>
                                    <th style="padding:8px 6px;color:rgba(255,255,255,.4);font-size:.65rem;text-transform:uppercase;letter-spacing:.05em;min-width:220px;">Vincular ao Contrato</th>
                                </tr>
                            </thead>
                            <tbody id="nv-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="{{ asset('js/pagamento-inicializar.js') }}"></script>
    @endsection

</x-app-layout>
