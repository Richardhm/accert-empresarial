<x-app-layout>
<style>
    .dash-page { background: #0f1623; min-height: 100vh; padding: 32px 24px; }

    /* ── Cards ── */
    .plano-card {
        background: linear-gradient(135deg, #1a2540 0%, #1e2d4d 100%);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 22px 20px;
        position: relative;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .plano-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.45); }
    .plano-card::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 110px; height: 110px;
        border-radius: 50%;
        opacity: .12;
        background: var(--card-accent, #4f8ef7);
    }
    .card-accent-0 { --card-accent: #4f8ef7; border-top: 3px solid #4f8ef7; }
    .card-accent-1 { --card-accent: #34d399; border-top: 3px solid #34d399; }
    .card-accent-2 { --card-accent: #f59e0b; border-top: 3px solid #f59e0b; }
    .card-accent-3 { --card-accent: #f87171; border-top: 3px solid #f87171; }
    .card-accent-4 { --card-accent: #a78bfa; border-top: 3px solid #a78bfa; }
    .card-accent-5 { --card-accent: #38bdf8; border-top: 3px solid #38bdf8; }

    .card-label  { font-size: .68rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.45); margin-bottom: 4px; }
    .card-title  { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 18px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .card-stat   { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 8px; }
    .stat-block  { text-align: left; }
    .stat-value  { font-size: 1.35rem; font-weight: 800; color: #fff; line-height: 1.1; }
    .stat-sub    { font-size: .68rem; color: rgba(255,255,255,.42); margin-top: 2px; }

    /* ── Painel ── */
    .dash-panel {
        background: #151e30;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 24px;
    }
    .panel-title { font-size: .8rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: rgba(255,255,255,.5); }

    /* ── Search input ── */
    .rank-search {
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 8px;
        padding: 6px 12px 6px 30px;
        font-size: .78rem;
        color: #fff;
        outline: none;
        width: 160px;
        transition: border-color .2s, width .2s;
    }
    .rank-search::placeholder { color: rgba(255,255,255,.3); }
    .rank-search:focus { border-color: rgba(79,142,247,.6); width: 200px; }
    .search-wrap { position: relative; }
    .search-wrap svg { position: absolute; left: 8px; top: 50%; transform: translateY(-50%); opacity: .4; pointer-events: none; }

    /* ── Tabela ranking ── */
    .rank-table { width: 100%; border-collapse: collapse; }
    .rank-table thead th {
        font-size: .65rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
        color: rgba(255,255,255,.35); padding: 0 10px 10px; border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .rank-table thead th:first-child { padding-left: 0; }
    .rank-table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,.05);
        cursor: pointer;
        transition: background .15s;
    }
    .rank-table tbody tr:hover { background: rgba(79,142,247,.08); }
    .rank-table tbody tr:last-child { border-bottom: none; }
    .rank-table tbody td { padding: 11px 10px; vertical-align: middle; }
    .rank-table tbody td:first-child { padding-left: 0; }

    .user-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: .75rem; color: #fff;
        flex-shrink: 0;
    }
    .user-name  { font-size: .82rem; font-weight: 600; color: #e2e8f0; }
    .td-num     { font-size: .82rem; font-weight: 700; color: #f1f5f9; text-align: right; }
    .td-money   { font-size: .82rem; font-weight: 700; color: #34d399; text-align: right; }
    .td-badge   { display: inline-block; background: rgba(79,142,247,.15); color: #7eb3ff; font-size: .7rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }

    /* Pill de total geral */
    .summary-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.09);
        border-radius: 30px; padding: 6px 14px; font-size: .75rem; color: rgba(255,255,255,.6);
    }
    .summary-pill span { font-weight: 700; color: #fff; }

    /* ── Modal empresas ── */
    #modalEmpresas {
        position: fixed; inset: 0; z-index: 9999;
        display: none; align-items: center; justify-content: center;
        background: rgba(0,0,0,.65);
        backdrop-filter: blur(4px);
    }
    #modalEmpresas.open { display: flex; }
    .modal-empresas-box {
        background: #151e30;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 20px;
        width: 92%; max-width: 900px;
        max-height: 88vh;
        display: flex; flex-direction: column;
        overflow: hidden;
        animation: modalIn .18s ease;
    }
    @keyframes modalIn { from { opacity:0; transform:translateY(14px) scale(.98); } to { opacity:1; transform:none; } }

    .modal-head {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255,255,255,.08);
        display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0;
    }
    .modal-head-info h3 { font-size: 1rem; font-weight: 800; color: #fff; margin: 0; }
    .modal-head-info p  { font-size: .73rem; color: rgba(255,255,255,.4); margin: 3px 0 0; }
    .modal-close-btn {
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,.07); border: none; cursor: pointer;
        color: rgba(255,255,255,.6); font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s;
    }
    .modal-close-btn:hover { background: rgba(255,255,255,.14); color: #fff; }

    .modal-stats-bar {
        display: flex; gap: 12px; padding: 14px 24px;
        background: rgba(0,0,0,.15);
        border-bottom: 1px solid rgba(255,255,255,.06);
        flex-shrink: 0; flex-wrap: wrap;
    }
    .mstat { text-align: center; }
    .mstat-val { font-size: 1.05rem; font-weight: 800; color: #fff; }
    .mstat-lbl { font-size: .65rem; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .06em; }

    .modal-body { overflow-y: auto; flex: 1; padding: 0; }
    .modal-body::-webkit-scrollbar { width: 5px; }
    .modal-body::-webkit-scrollbar-track { background: transparent; }
    .modal-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }

    .emp-table { width: 100%; border-collapse: collapse; }
    .emp-table thead th {
        font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
        color: rgba(255,255,255,.35); padding: 12px 16px;
        border-bottom: 1px solid rgba(255,255,255,.07);
        position: sticky; top: 0; background: #151e30; z-index: 1;
    }
    .emp-table tbody tr { border-bottom: 1px solid rgba(255,255,255,.04); transition: background .12s; }
    .emp-table tbody tr:hover { background: rgba(255,255,255,.03); }
    .emp-table tbody td { padding: 11px 16px; font-size: .8rem; color: #cbd5e1; vertical-align: middle; }
    .emp-table tbody td.em-name { color: #f1f5f9; font-weight: 600; }
    .badge-pago    { display:inline-block; background:rgba(52,211,153,.15); color:#34d399; font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:20px; }
    .badge-pending { display:inline-block; background:rgba(245,158,11,.15); color:#f59e0b; font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:20px; }
    .modal-empty { text-align:center; padding:40px; color:rgba(255,255,255,.3); font-size:.85rem; }

    /* ── Spinner modal ── */
    .modal-spinner-wrap { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:52px 0; gap:16px; }
    .modal-spinner {
        width: 38px; height: 38px;
        border: 3px solid rgba(255,255,255,.1);
        border-top-color: #4f8ef7;
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .modal-spinner-txt { font-size: .78rem; color: rgba(255,255,255,.35); letter-spacing: .04em; }
</style>

<div class="dash-page">
    <div style="max-width:1400px;margin:0 auto;">

        {{-- ── Cabeçalho ── --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0;">Dashboard</h1>
                <p style="font-size:.78rem;color:rgba(255,255,255,.4);margin:4px 0 0;">Visão geral dos contratos empresariais</p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <div class="summary-pill">Total de planos <span>{{ count($cardsPlanos) }}</span></div>
                <div class="summary-pill">Vendedores <span>{{ count($rankingUsuarios) }}</span></div>
            </div>
        </div>

        {{-- ── Cards por plano ── --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:28px;">
            @foreach ($cardsPlanos as $i => $plano)
            <div class="plano-card card-accent-{{ $i % 6 }}">
                <p class="card-label">Plano</p>
                <p class="card-title" title="{{ $plano->nome }}">{{ $plano->nome }}</p>
                <div class="card-stat">
                    <div class="stat-block">
                        <div class="stat-value">{{ number_format($plano->total_vidas, 0, ',', '.') }}</div>
                        <div class="stat-sub">Vidas</div>
                    </div>
                    <div class="stat-block" style="text-align:right;">
                        <div class="stat-value" style="font-size:1rem;">R$&nbsp;{{ number_format($plano->total_valor, 0, ',', '.') }}</div>
                        <div class="stat-sub">Total vendido</div>
                    </div>
                </div>
                <div style="margin-top:12px;">
                    <span class="td-badge">{{ $plano->total_contratos }} contrato{{ $plano->total_contratos != 1 ? 's' : '' }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Linha principal: Gráfico + Tabela ── --}}
        <div style="display:grid;grid-template-columns:1fr 440px;gap:20px;align-items:start;">

            {{-- Gráfico --}}
            <div class="dash-panel">
                <p class="panel-title" style="margin-bottom:20px;">Contratos por plano</p>
                <canvas id="planosChart" style="max-height:320px;"></canvas>
            </div>

            {{-- Tabela ranking --}}
            <div class="dash-panel" style="padding-bottom:0;">

                {{-- Header do painel com busca --}}
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                    <p class="panel-title" style="margin:0;">Ranking de vendedores</p>
                    <div class="search-wrap">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="text" id="rankSearch" class="rank-search" placeholder="Buscar vendedor...">
                    </div>
                </div>

                {{-- Scroll wrapper --}}
                <div style="max-height:420px;overflow-y:auto;margin:0 -24px;padding:0 24px 24px;"
                     id="rankScrollArea">
                    <table class="rank-table" id="rankTable">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Vendedor</th>
                                <th style="text-align:right;">Contratos</th>
                                <th style="text-align:right;">Vidas</th>
                                <th style="text-align:right;">Comissão paga</th>
                            </tr>
                        </thead>
                        <tbody id="rankBody">
                            @php
                                $avatarColors = ['#4f8ef7','#34d399','#f59e0b','#f87171','#a78bfa','#38bdf8','#fb923c','#e879f9'];
                            @endphp
                            @foreach ($rankingUsuarios as $i => $u)
                            <tr class="rank-row"
                                data-user-id="{{ $u->user_id }}"
                                data-user-name="{{ $u->nome }}"
                                data-contratos="{{ $u->total_contratos }}"
                                data-vidas="{{ $u->total_vidas }}"
                                data-comissao="{{ number_format($u->total_comissao_paga, 2, ',', '.') }}"
                                data-color="{{ $avatarColors[$i % count($avatarColors)] }}"
                                data-initials="{{ strtoupper(substr($u->nome, 0, 2)) }}">
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="user-avatar" style="background:{{ $avatarColors[$i % count($avatarColors)] }};">
                                            {{ strtoupper(substr($u->nome, 0, 2)) }}
                                        </div>
                                        <span class="user-name">{{ $u->nome }}</span>
                                    </div>
                                </td>
                                <td class="td-num">{{ $u->total_contratos }}</td>
                                <td class="td-num">{{ number_format($u->total_vidas, 0, ',', '.') }}</td>
                                <td class="td-money">R$ {{ number_format($u->total_comissao_paga, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach

                            @if(count($rankingUsuarios) === 0)
                            <tr id="emptyRow">
                                <td colspan="4" style="text-align:center;padding:24px;color:rgba(255,255,255,.3);font-size:.8rem;">
                                    Nenhum registro encontrado
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════ MODAL EMPRESAS ══════════════ --}}
<div id="modalEmpresas">
    <div class="modal-empresas-box">

        {{-- Header --}}
        <div class="modal-head">
            <div class="modal-head-info">
                <h3 id="modalEmpNome">–</h3>
                <p id="modalEmpSub">Contratos empresariais</p>
            </div>
            <button class="modal-close-btn" id="closeModalEmpresas">&times;</button>
        </div>

        {{-- Stats bar --}}
        <div class="modal-stats-bar">
            <div class="mstat" style="padding-right:16px;border-right:1px solid rgba(255,255,255,.08);">
                <div class="mstat-val" id="mstat_contratos">–</div>
                <div class="mstat-lbl">Contratos</div>
            </div>
            <div class="mstat" style="padding:0 16px;border-right:1px solid rgba(255,255,255,.08);">
                <div class="mstat-val" id="mstat_vidas">–</div>
                <div class="mstat-lbl">Vidas</div>
            </div>
            <div class="mstat" style="padding-left:16px;">
                <div class="mstat-val" style="color:#34d399;" id="mstat_comissao">–</div>
                <div class="mstat-lbl">Comissão paga</div>
            </div>
        </div>

        {{-- Body com tabela --}}
        <div class="modal-body" id="modalEmpBody">
            <p class="modal-empty">Carregando...</p>
        </div>
    </div>
</div>

<script>
(function () {
    /* ── Gráfico ── */
    var planos  = @json(array_column($cardsPlanos, 'nome'));
    var vidas   = @json(array_column($cardsPlanos, 'total_vidas'));
    var valores = @json(array_column($cardsPlanos, 'total_valor'));

    var palette = [
        { bg: 'rgba(79,142,247,.75)',  border: '#4f8ef7' },
        { bg: 'rgba(52,211,153,.75)',  border: '#34d399' },
        { bg: 'rgba(245,158,11,.75)',  border: '#f59e0b' },
        { bg: 'rgba(248,113,113,.75)', border: '#f87171' },
        { bg: 'rgba(167,139,250,.75)', border: '#a78bfa' },
        { bg: 'rgba(56,189,248,.75)',  border: '#38bdf8' },
    ];
    var bgColors     = planos.map(function(_, i){ return palette[i % palette.length].bg; });
    var borderColors = planos.map(function(_, i){ return palette[i % palette.length].border; });

    new Chart(document.getElementById('planosChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: planos,
            datasets: [
                {
                    label: 'Vidas',
                    data: vidas,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 6,
                    yAxisID: 'yVidas',
                },
                {
                    label: 'Total Vendido (R$)',
                    data: valores,
                    type: 'line',
                    yAxisID: 'yValor',
                    backgroundColor: bgColors.map(function(c){ return c.replace('.75)','.25)'); }),
                    borderColor: borderColors,
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: borderColors,
                    pointRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { labels: { color: 'rgba(255,255,255,.55)', font: { size: 11 }, boxWidth: 12 } },
                tooltip: {
                    backgroundColor: '#1a2540',
                    borderColor: 'rgba(255,255,255,.1)', borderWidth: 1,
                    titleColor: '#fff', bodyColor: 'rgba(255,255,255,.7)',
                    callbacks: {
                        label: function(c) {
                            return c.datasetIndex === 1
                                ? ' R$ ' + c.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits:2})
                                : ' ' + c.parsed.y.toLocaleString('pt-BR') + ' vidas';
                        }
                    }
                }
            },
            scales: {
                x: { ticks: { color: 'rgba(255,255,255,.5)', font:{size:11} }, grid: { color:'rgba(255,255,255,.04)' } },
                yVidas: { type:'linear', position:'left', beginAtZero:true,
                    ticks: { color:'rgba(255,255,255,.5)', font:{size:11} },
                    grid: { color:'rgba(255,255,255,.06)' } },
                yValor: { type:'linear', position:'right', beginAtZero:true,
                    ticks: { color:'rgba(255,255,255,.35)', font:{size:11},
                        callback: function(v){ return 'R$ ' + v.toLocaleString('pt-BR',{minimumFractionDigits:0}); } },
                    grid: { drawOnChartArea:false } }
            }
        }
    });

    /* ── Busca no ranking ── */
    document.getElementById('rankSearch').addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        var rows = document.querySelectorAll('#rankBody .rank-row');
        var anyVisible = false;
        rows.forEach(function (tr) {
            var name = tr.dataset.userName.toLowerCase();
            var show = !q || name.includes(q);
            tr.style.display = show ? '' : 'none';
            if (show) anyVisible = true;
        });
        var noRes = document.getElementById('rankNoResults');
        if (!anyVisible) {
            if (!noRes) {
                noRes = document.createElement('tr');
                noRes.id = 'rankNoResults';
                noRes.innerHTML = '<td colspan="4" style="text-align:center;padding:20px;color:rgba(255,255,255,.3);font-size:.8rem;">Nenhum vendedor encontrado</td>';
                document.getElementById('rankBody').appendChild(noRes);
            }
            noRes.style.display = '';
        } else if (noRes) {
            noRes.style.display = 'none';
        }
    });

    /* ── Scroll customizado ── */
    var rankArea = document.getElementById('rankScrollArea');
    rankArea.style.scrollbarWidth = 'thin';
    rankArea.style.scrollbarColor = 'rgba(255,255,255,.15) transparent';

    /* ── Modal empresas ── */
    var urlEmpresas = '{{ route("dashboard.empresas.vendedor") }}';
    var modal       = document.getElementById('modalEmpresas');

    function openModal(userId, userName, contratos, vidas, comissao) {
        document.getElementById('modalEmpNome').textContent    = userName;
        document.getElementById('modalEmpSub').textContent     = 'Contratos empresariais';
        document.getElementById('mstat_contratos').textContent = contratos;
        document.getElementById('mstat_vidas').textContent     = Number(vidas).toLocaleString('pt-BR');
        document.getElementById('mstat_comissao').textContent  = 'R$ ' + comissao;
        document.getElementById('modalEmpBody').innerHTML =
            '<div class="modal-spinner-wrap">'
            + '<div class="modal-spinner"></div>'
            + '<span class="modal-spinner-txt">Carregando empresas...</span>'
            + '</div>';
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';

        fetch(urlEmpresas + '?user_id=' + userId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.json(); })
        .then(function(data) {
            if (!data.length) {
                document.getElementById('modalEmpBody').innerHTML = '<p class="modal-empty">Nenhuma empresa encontrada.</p>';
                return;
            }
            var html = '<table class="emp-table"><thead><tr>'
                + '<th style="text-align:left;">Empresa</th>'
                + '<th>Cidade</th>'
                + '<th>Plano</th>'
                + '<th style="text-align:right;">Vidas</th>'
                + '<th style="text-align:right;">Valor</th>'
                + '<th style="text-align:right;">Comissão</th>'
                + '<th style="text-align:center;">Status</th>'
                + '<th>Cadastro</th>'
                + '</tr></thead><tbody>';

            data.forEach(function(e) {
                html += '<tr>'
                    + '<td class="em-name">' + (e.razao_social || '–') + '</td>'
                    + '<td>' + (e.cidade || '–') + '</td>'
                    + '<td>' + (e.plano_nome || '–') + '</td>'
                    + '<td style="text-align:right;">' + (e.quantidade_vidas || 0) + '</td>'
                    + '<td style="text-align:right;">R$ ' + Number(e.valor_plano).toLocaleString('pt-BR',{minimumFractionDigits:2}) + '</td>'
                    + '<td style="text-align:right;color:#34d399;">R$ ' + Number(e.comissao).toLocaleString('pt-BR',{minimumFractionDigits:2}) + '</td>'
                    + '<td style="text-align:center;">'
                    +   (e.pago ? '<span class="badge-pago">Pago</span>' : '<span class="badge-pending">Pendente</span>')
                    + '</td>'
                    + '<td>' + (e.data_cadastro || '–') + '</td>'
                    + '</tr>';
            });

            html += '</tbody></table>';
            document.getElementById('modalEmpBody').innerHTML = html;
        })
        .catch(function() {
            document.getElementById('modalEmpBody').innerHTML = '<p class="modal-empty" style="color:#f87171;">Erro ao carregar dados.</p>';
        });
    }

    function closeModal() {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }

    // Clique nas linhas
    document.querySelectorAll('.rank-row').forEach(function(tr) {
        tr.addEventListener('click', function() {
            openModal(
                this.dataset.userId,
                this.dataset.userName,
                this.dataset.contratos,
                this.dataset.vidas,
                this.dataset.comissao
            );
        });
    });

    // Fechar modal
    document.getElementById('closeModalEmpresas').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
})();
</script>

</x-app-layout>
