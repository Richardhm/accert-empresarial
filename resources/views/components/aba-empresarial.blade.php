<main id="aba_empresarial">
    <div class="fin-panel">

        {{-- ── Linha 1: stats (esq) + corretor/export (dir) ── --}}
        <div class="fin-toolbar">

            <div class="fin-stats-row">
                <div class="fin-stat-chip chip-purple">
                    <span class="chip-lbl">Contratos</span>
                    <span class="chip-val total_por_orcamento_empresarial">0</span>
                </div>
                <div class="fin-stat-chip chip-blue">
                    <span class="chip-lbl">Vidas</span>
                    <span class="chip-val total_por_vida_empresarial">0</span>
                </div>
                <div class="fin-stat-chip chip-green">
                    <span class="chip-lbl">Total</span>
                    <span class="chip-val total_por_page_empresarial">R$ 0,00</span>
                </div>
                <div class="fin-stat-chip chip-teal">
                    <span class="chip-lbl">Adesão Pago</span>
                    <span class="chip-val total_adesao_pago">R$ 0,00</span>
                </div>
                <div class="fin-stat-chip chip-amber">
                    <span class="chip-lbl">Adesão Pendente</span>
                    <span class="chip-val total_adesao_pendente">R$ 0,00</span>
                </div>
            </div>

            <div class="fin-selects-row">
                <div class="fin-select-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(255,255,255,.4)"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                    <select id="mudar_user_empresarial" name="mudar_user_empresarial" class="fin-select-inline">
                        <option value="">— Todos os Corretores —</option>
                    </select>
                </div>
                <div id="dt-export-btn-wrap"></div>
                <button id="btnAbrirImportarHistorico" type="button"
                    style="display:flex;align-items:center;gap:5px;padding:5px 10px;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.35);border-radius:7px;color:#fbbf24;font-size:.68rem;font-weight:600;cursor:pointer;white-space:nowrap;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                    Importar Histórico
                </button>
            </div>

        </div>

        {{-- ── Linha 2: Saúde/Odonto + Planos (mesma linha) ── --}}
        <div class="fin-filters-row">

            {{-- Saúde e Odonto ── --}}
            <fieldset class="fin-filter-fieldset fs-saude">
                <legend class="fin-filter-legend">Saúde e Odonto</legend>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    <button class="tipo-tag-btn tipo-tag-ativo" data-tipo=""
                        style="background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.18);color:rgba(255,255,255,.65);">
                        <span class="plano-tag-dot" style="background:rgba(255,255,255,.4);"></span>Todos<span class="plano-tag-count" id="count-tipo-todos"></span>
                    </button>
                    <button class="tipo-tag-btn" data-tipo="ambos"
                        data-bg="rgba(139,92,246,.1)" data-border="rgba(139,92,246,.4)" data-text="#c4b5fd" data-active="#8b5cf6" data-dot="#8b5cf6"
                        style="background:rgba(139,92,246,.1);border-color:rgba(139,92,246,.4);color:#c4b5fd;">
                        <span class="plano-tag-dot" style="background:#8b5cf6;"></span>Saúde + Odonto<span class="plano-tag-count" id="count-tipo-ambos"></span>
                    </button>
                    <button class="tipo-tag-btn" data-tipo="saude"
                        data-bg="rgba(34,197,94,.1)" data-border="rgba(34,197,94,.4)" data-text="#86efac" data-active="#22c55e" data-dot="#22c55e"
                        style="background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.4);color:#86efac;">
                        <span class="plano-tag-dot" style="background:#22c55e;"></span>Apenas Saúde<span class="plano-tag-count" id="count-tipo-saude"></span>
                    </button>
                    <button class="tipo-tag-btn" data-tipo="odonto"
                        data-bg="rgba(59,130,246,.1)" data-border="rgba(59,130,246,.4)" data-text="#93c5fd" data-active="#3b82f6" data-dot="#3b82f6"
                        style="background:rgba(59,130,246,.1);border-color:rgba(59,130,246,.4);color:#93c5fd;">
                        <span class="plano-tag-dot" style="background:#3b82f6;"></span>Apenas Odonto<span class="plano-tag-count" id="count-tipo-odonto"></span>
                    </button>
                </div>
            </fieldset>

            {{-- Planos (gerado pelo JS no initComplete) ── --}}
            <fieldset class="fin-filter-fieldset fs-planos" id="plano-filter-fieldset" style="flex:1;">
                <legend class="fin-filter-legend">Planos</legend>
                <div id="plano-filter-btns" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
            </fieldset>

        </div>

        {{-- ── Linha 3: Etapas — 2 blocos na mesma linha ── --}}
        <div class="fin-filters-row">

            {{-- Bloco 1: pipeline de etapas --}}
            <fieldset class="fin-filter-fieldset fs-etapas" style="flex:1;">
                <legend class="fin-filter-legend">Etapas</legend>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    <button class="fin-tag fin-tag-vencidos" data-etapa="vencidos">
                        <span class="fin-tag-dot"></span>Atrasado
                        <span class="fin-tag-count" id="count-vencidos"></span>
                    </button>
                    <button class="fin-tag fin-tag-andamento" data-etapa="andamento">
                        <span class="fin-tag-dot"></span>Em Andamento
                        <span class="fin-tag-count" id="count-andamento"></span>
                    </button>
                    <button class="fin-tag fin-tag-planilha" data-etapa="0">
                        <span class="fin-tag-dot"></span>Cadastro
                        <span class="fin-tag-count" id="count-etapa-0"></span>
                    </button>
                    <button class="fin-tag fin-tag-aditivo" data-etapa="1">
                        <span class="fin-tag-dot"></span>Contrato
                        <span class="fin-tag-count" id="count-etapa-1"></span>
                    </button>
                    <button class="fin-tag fin-tag-adesao" data-etapa="2">
                        <span class="fin-tag-dot"></span>Adesão
                        <span class="fin-tag-count" id="count-etapa-2"></span>
                    </button>
                    <button class="fin-tag fin-tag-boleto" data-etapa="3">
                        <span class="fin-tag-dot"></span>Vencimento
                        <span class="fin-tag-count" id="count-etapa-3"></span>
                    </button>
                    <button class="fin-tag fin-tag-vigencia" data-etapa="4">
                        <span class="fin-tag-dot"></span>Vigência
                        <span class="fin-tag-count" id="count-etapa-4"></span>
                    </button>
                    <button class="fin-tag fin-tag-carteirinha" data-etapa="5">
                        <span class="fin-tag-dot"></span>Carteiras
                        <span class="fin-tag-count" id="count-etapa-5"></span>
                    </button>
                    <button class="fin-tag fin-tag-primeiro-boleto" data-etapa="6">
                        <span class="fin-tag-dot"></span>1º Boleto
                        <span class="fin-tag-count" id="count-etapa-6"></span>
                    </button>
                    <button class="fin-tag fin-tag-finalizar" data-etapa="7">
                        <span class="fin-tag-dot"></span>Aditivo
                        <span class="fin-tag-count" id="count-etapa-7"></span>
                    </button>
                    <button class="fin-tag fin-tag-concluidos" data-etapa="8">
                        <span class="fin-tag-dot"></span>Finalizado
                        <span class="fin-tag-count" id="count-etapa-8"></span>
                    </button>
                </div>
            </fieldset>

            {{-- Bloco 2: visão geral --}}
            <fieldset class="fin-filter-fieldset fs-etapas" style="flex:none;">
                <legend class="fin-filter-legend">Geral</legend>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    <button class="fin-tag fin-tag-todos fin-tag-ativo" data-etapa="">
                        <span class="fin-tag-dot"></span>Todos
                        <span class="fin-tag-count" id="count-todos"></span>
                    </button>
                    <button class="fin-tag fin-tag-cancelados" data-etapa="cancelados">
                        <span class="fin-tag-dot"></span>Cancelados
                        <span class="fin-tag-count" id="count-cancelados"></span>
                    </button>
                    <button class="fin-tag fin-tag-declinar" data-etapa="declinar">
                        <span class="fin-tag-dot"></span>Declinar
                        <span class="fin-tag-count" id="count-declinados"></span>
                    </button>
                </div>
            </fieldset>

        </div>

        {{-- Legenda --}}
        <div class="fin-legenda">
            <span style="font-weight:600;color:rgba(255,255,255,.6);font-size:.65rem;text-transform:uppercase;letter-spacing:.06em;">Legenda:</span>
            <div class="fin-legenda-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:10px;height:10px;flex-shrink:0;"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>
                <span style="color:#34d399;">Saúde</span>
            </div>
            <div class="fin-legenda-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:10px;height:10px;flex-shrink:0;"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/></svg>
                <span style="color:#93c5fd;">Odonto</span>
            </div>
            <div class="fin-legenda-item">
                <div class="fin-legenda-cor amarelo"></div>
                <span>Boleto de adesão diverge da planilha — clique em ⚠️ para ver justificativa</span>
            </div>
        </div>

        {{-- Tabela --}}
        <table id="tabela_empresarial"
               class="table table-sm text-left listarempresarial"
               style="table-layout:fixed;width:100%;">
            <thead>
                <tr style="font-size:0.9em;">
                    <th class="dt-center">Tipo</th>
                    <th class="dt-center">Plano</th>
                    <th>Código</th>
                    <th>CNPJ</th>
                    <th>Cliente</th>
                    <th>UF</th>
                    <th>Cidade</th>
                    <th>Vendedor</th>
                    <th class="dt-center">Vidas</th>
                    <th class="dt-center">Valor</th>
                    <th class="dt-center">Planilha</th>
                    <th class="dt-center">Contrato</th>
                    <th class="dt-center">Adesão</th>
                    <th class="dt-center">Vencimento</th>
                    <th class="dt-center">Vigência</th>
                    <th class="dt-center">Carteiras</th>
                    <th class="dt-center">1º Boleto</th>
                    <th class="dt-center">Finalizado</th>
                    <th class="dt-center">Ações</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
</main>
