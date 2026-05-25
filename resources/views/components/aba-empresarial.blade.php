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
            </div>

            <div class="fin-selects-row">
                <div class="fin-select-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(255,255,255,.4)"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                    <select id="mudar_user_empresarial" name="mudar_user_empresarial" class="fin-select-inline">
                        <option value="">— Todos os Corretores —</option>
                    </select>
                </div>
                <div id="dt-export-btn-wrap"></div>
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

        {{-- ── Linha 3: Etapas (inline) ── --}}
        <div class="fin-filters-row">

            <fieldset class="fin-filter-fieldset fs-etapas" style="flex:1;">
                <legend class="fin-filter-legend">Etapas</legend>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    <button class="fin-tag fin-tag-todos fin-tag-ativo" data-etapa="">
                        <span class="fin-tag-dot"></span>Todos
                        <span class="fin-tag-count" id="count-todos"></span>
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

        </div>

        {{-- Legenda --}}
        <div class="fin-legenda">
            <span style="font-weight:600;color:rgba(255,255,255,.6);font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;">Legenda:</span>
            <div class="fin-legenda-item">
                <div class="fin-legenda-cor amarelo"></div>
                <span>Valor do boleto de adesão diverge da planilha — clique em ⚠️ para ver a justificativa</span>
            </div>
        </div>

        {{-- Tabela --}}
        <table id="tabela_empresarial"
               class="table table-sm text-left listarempresarial"
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
