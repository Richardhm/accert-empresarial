<main id="aba_empresarial">
    <div class="fin-layout">

        {{-- ── Sidebar ── --}}
        <aside class="fin-sidebar">

            {{-- Stats --}}
            <div class="fin-stat-card">
                <div class="fin-stat-item">
                    <span class="fin-stat-lbl">Contratos</span>
                    <span class="fin-stat-val total_por_orcamento_empresarial">0</span>
                </div>
                <div class="fin-stat-divider"></div>
                <div class="fin-stat-item">
                    <span class="fin-stat-lbl">Vidas</span>
                    <span class="fin-stat-val total_por_vida_empresarial">0</span>
                </div>
                <div class="fin-stat-divider"></div>
                <div class="fin-stat-item">
                    <span class="fin-stat-lbl">Total</span>
                    <span class="fin-stat-val fin-stat-money total_por_page_empresarial">R$ 0,00</span>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="fin-filter-card">
                <p class="fin-filter-label">Filtrar por corretor</p>
                <select id="mudar_user_empresarial" name="mudar_user_empresarial" class="fin-select">
                    <option value="">— Todos os Corretores —</option>
                </select>

                <p class="fin-filter-label" style="margin-top:14px;">Filtrar por plano</p>
                <select id="mudar_planos_empresarial" name="mudar_planos_empresarial" class="fin-select">
                    <option value="">— Todos os Planos —</option>
                </select>
            </div>

        </aside>

        {{-- ── Tabela ── --}}
        <div class="fin-table-panel">
            <table id="tabela_empresarial"
                   class="table table-sm text-left listarempresarial"
                   style="table-layout:fixed;width:100%;">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cod.</th>
                        <th>Corretor</th>
                        <th>Cadastrado Por</th>
                        <th>Cliente</th>
                        <th>CNPJ</th>
                        <th>Vidas</th>
                        <th>Valor</th>
                        <th>Comissão</th>
                        <th>%</th>
                        <th>Plano</th>
                        <th>Vencimento</th>
                        <th>Stts. Pgto</th>
                        <th>Status</th>
                        <th>Ver</th>
                        <th>Resposta</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</main>
