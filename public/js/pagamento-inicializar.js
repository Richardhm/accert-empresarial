var tablePagamento;

var PLANO_COLORS_PAG = [
    { text: '#93c5fd', border: 'rgba(59,130,246,.4)',  bg: 'rgba(59,130,246,.1)',  active: '#3b82f6', dot: '#3b82f6'  },
    { text: '#c4b5fd', border: 'rgba(139,92,246,.4)',  bg: 'rgba(139,92,246,.1)',  active: '#8b5cf6', dot: '#8b5cf6'  },
    { text: '#fcd34d', border: 'rgba(245,158,11,.4)',  bg: 'rgba(245,158,11,.1)',  active: '#f59e0b', dot: '#f59e0b'  },
    { text: '#86efac', border: 'rgba(34,197,94,.4)',   bg: 'rgba(34,197,94,.1)',   active: '#22c55e', dot: '#22c55e'  },
    { text: '#f9a8d4', border: 'rgba(236,72,153,.4)',  bg: 'rgba(236,72,153,.1)',  active: '#ec4899', dot: '#ec4899'  },
    { text: '#67e8f9', border: 'rgba(6,182,212,.4)',   bg: 'rgba(6,182,212,.1)',   active: '#06b6d4', dot: '#06b6d4'  },
    { text: '#fdba74', border: 'rgba(249,115,22,.4)',  bg: 'rgba(249,115,22,.1)',  active: '#f97316', dot: '#f97316'  },
    { text: '#a5b4fc', border: 'rgba(99,102,241,.4)',  bg: 'rgba(99,102,241,.1)',  active: '#6366f1', dot: '#6366f1'  },
];

var SVG_HEART_PAG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>';
var SVG_TOOTH_PAG  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/></svg>';

// ── Tipo: render da col 0 ─────────────────────────────────────────────────────
function renderTipoPag(data, type) {
    if (type !== 'display') return data || '';
    if (data === 'ambos') {
        return '<div style="display:inline-flex;flex-direction:column;gap:2px;align-items:flex-start;">'
            + '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_HEART_PAG + 'Saúde</span>'
            + '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_TOOTH_PAG + 'Odonto</span>'
            + '</div>';
    } else if (data === 'saude') {
        return '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_HEART_PAG + 'Saúde</span>';
    } else if (data === 'odonto') {
        return '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_TOOTH_PAG + 'Odonto</span>';
    }
    return '-';
}

// ── Código: render da col 3 ───────────────────────────────────────────────────
function renderCodigoPag(data, type, row) {
    if (type !== 'display') return data || '';
    var cS = row.codigo_saude  || null;
    var cO = row.codigo_odonto || null;
    var tipo = row.tipo_contrato || null;
    if (tipo === 'ambos') {
        return '<div style="display:flex;flex-direction:column;gap:2px;">'
            + '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.7rem;">' + SVG_HEART_PAG + (cS || '-') + '</span>'
            + '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.7rem;">' + SVG_TOOTH_PAG + (cO || '-') + '</span>'
            + '</div>';
    } else if (tipo === 'saude') {
        return '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.7rem;">' + SVG_HEART_PAG + (cS || '-') + '</span>';
    } else if (tipo === 'odonto') {
        return '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.7rem;">' + SVG_TOOTH_PAG + (cO || '-') + '</span>';
    }
    return data || '-';
}

// ── Valor: render da col 10 ───────────────────────────────────────────────────
function renderValorPag(data, type, row) {
    if (type !== 'display') return data;
    if (!data) return '-';
    var fmt = function (v) {
        return 'R$ ' + parseFloat(v || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };
    var tipo = row.tipo_contrato || null;
    var valS = parseFloat(row.valor_saude  || 0);
    var valO = parseFloat(row.valor_odonto || 0);
    var icoS = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>';
    var icoO = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/></svg>';

    if (tipo === 'ambos') {
        return '<div style="display:flex;flex-direction:column;gap:1px;align-items:flex-end;">'
            + '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.74rem;white-space:nowrap;">' + icoS + fmt(valS) + '</span>'
            + '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.74rem;white-space:nowrap;">' + icoO + fmt(valO) + '</span>'
            + '</div>';
    } else if (tipo === 'saude') {
        return '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;white-space:nowrap;">' + icoS + fmt(valS) + '</span>';
    } else if (tipo === 'odonto') {
        return '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;white-space:nowrap;">' + icoO + fmt(valO) + '</span>';
    }
    return '<span style="white-space:nowrap;">' + fmt(parseFloat(data)) + '</span>';
}

// ── Filtros ───────────────────────────────────────────────────────────────────
var filtroTipoPag    = null;
var filtroStatusPag  = null;
var filtroMesPag     = null;

var MESES_PT = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
    if (settings.nTable.id !== 'tabela_pagamento') return true;
    if (filtroTipoPag === null) return true;
    if (!rowData) return true;
    return (rowData.tipo_contrato || null) === filtroTipoPag;
});

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
    if (settings.nTable.id !== 'tabela_pagamento') return true;
    if (!filtroStatusPag) return true;
    if (!rowData) return true;

    var tipo   = rowData.tipo_contrato || '';
    var temAgS = parseInt(rowData.tem_agenciamento_saude  || 0);
    var temReS = parseInt(rowData.tem_recorrencia_saude   || 0);
    var temAgO = parseInt(rowData.tem_agenciamento_odonto || 0);
    var temReO = parseInt(rowData.tem_recorrencia_odonto  || 0);
    var temGap = parseInt(rowData.tem_gap_recorrencia     || 0);
    var totalC = parseFloat(rowData.total_comissoes       || 0);

    switch (filtroStatusPag) {
        case 'sem_pagamento':
            return totalC === 0;
        case 'saude_so_agenciamento':
            return (tipo === 'saude' || tipo === 'ambos') && temAgS === 1 && temReS === 0;
        case 'saude_so_recorrencia':
            return (tipo === 'saude' || tipo === 'ambos') && temAgS === 0 && temReS === 1;
        case 'odonto_so_agenciamento':
            return (tipo === 'odonto' || tipo === 'ambos') && temAgO === 1 && temReO === 0;
        case 'odonto_so_recorrencia':
            return (tipo === 'odonto' || tipo === 'ambos') && temAgO === 0 && temReO === 1;
        case 'so_agenciamento':
            return (temAgS === 1 || temAgO === 1) && temReS === 0 && temReO === 0;
        case 'so_recorrencia':
            return (temReS === 1 || temReO === 1) && temAgS === 0 && temAgO === 0;
        case 'gap_recorrencia':
            return temGap === 1;
        default:
            return true;
    }
});

// ── DataTable ─────────────────────────────────────────────────────────────────
function inicializarPagamento() {
    if ($.fn.DataTable.isDataTable('#tabela_pagamento')) {
        $('#tabela_pagamento').DataTable().destroy();
    }

    tablePagamento = $('#tabela_pagamento').DataTable({
        dom: '<"flex justify-between"<"#title_pagamento">Bftr><t><"flex justify-between"lp>',
        language: {
            search: 'Pesquisar',
            paginate: { next: 'Próx.', previous: 'Ant.', first: 'Primeiro', last: 'Último' },
            emptyTable:    'Nenhum contrato finalizado encontrado',
            info:          'Mostrando de _START_ até _END_ de _TOTAL_ registros',
            infoEmpty:     'Mostrando 0 até 0 de 0 registros',
            infoFiltered:  '(Filtrados de _MAX_ registros)',
            infoThousands: '.',
            loadingRecords:'Carregando...',
            processing:    'Processando...',
            lengthMenu:    'Exibir _MENU_ por página',
            zeroRecords:   'Nenhum registro encontrado'
        },
        ajax: { url: urlListarPagamento },
        lengthMenu: [500, 1000, 2000],
        ordering:   false,
        paging:     true,
        searching:  true,
        info:       true,
        autoWidth:  false,
        responsive: true,
        processing: true,
        columns: [
            // col 0 — Tipo
            { data: 'tipo_contrato', name: 'tipo_contrato', orderable: false, width: '4%',  className: 'dt-center col-tipo',
              render: renderTipoPag },
            // col 1 — Plano
            { data: 'plano',             name: 'plano',             width: '5%'  },
            // col 2 — Cadastro
            { data: 'created_at',        name: 'created_at',        width: '6%'  },
            // col 3 — Código
            { data: 'codigo_externo',    name: 'codigo_externo',    width: '6%',  className: 'col-codigo',
              render: renderCodigoPag },
            // col 4 — CNPJ
            { data: 'cnpj',              name: 'cnpj',              width: '10%' },
            // col 5 — Cliente
            { data: 'razao_social',      name: 'razao_social',      width: '12%' },
            // col 6 — UF
            { data: 'uf',                name: 'uf',                width: '3%'  },
            // col 7 — Cidade
            { data: 'cidade',            name: 'cidade',            width: '7%'  },
            // col 8 — Vendedor
            { data: 'usuario',           name: 'usuario',           width: '7%'  },
            // col 9 — Vidas
            { data: 'quantidade_vidas',  name: 'quantidade_vidas',  width: '3%',  className: 'dt-center' },
            // col 10 — Valor
            { data: 'valor_plano',       name: 'valor_plano',       width: '9%',  className: 'dt-right',
              render: renderValorPag },
            // col 11 — Comissões
            { data: 'total_comissoes', name: 'total_comissoes', orderable: false, width: '8%', className: 'dt-right',
              render: function (data, type) {
                  if (type !== 'display') return data;
                  var val = parseFloat(data || 0);
                  var cor = val > 0 ? '#34d399' : 'rgba(255,255,255,.25)';
                  return '<span style="color:' + cor + ';white-space:nowrap;font-size:.74rem;">R$ '
                       + val.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                       + '</span>';
              }
            },
            // col 12 — Última Parcela
            { data: 'ultima_parcela', name: 'ultima_parcela', orderable: false, width: '5%', className: 'dt-center',
              render: function (data, type) {
                  if (type !== 'display') return data || '';
                  if (!data) return '<span style="color:rgba(255,255,255,.2);font-size:.7rem;">—</span>';
                  return '<span style="background:rgba(79,142,247,.15);border:1px solid rgba(79,142,247,.35);'
                       + 'color:#93c5fd;padding:2px 8px;border-radius:20px;font-size:.7rem;font-weight:700;">'
                       + data + 'ª</span>';
              }
            },
            // col 13 — Detalhe
            { data: 'id', name: 'detalhe', orderable: false, width: '3%', className: 'dt-center',
              render: function (data, type) {
                  if (type !== 'display') return data;
                  return '<button class="btn-detalhe-pagamento" data-id="' + data + '" title="Ver pagamentos"'
                       + ' style="background:none;border:none;padding:0;cursor:pointer;line-height:0;">'
                       + '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"'
                       + ' stroke="rgba(79,142,247,.9)" style="width:15px;height:15px;vertical-align:middle;">'
                       + '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.963-7.178Z"/>'
                       + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>'
                       + '</svg>'
                       + '</button>';
              }
            },
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'pagamento-empresarial',
                text: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>Exportar',
                className: 'btn-exportar',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] },
                filename: 'pagamento-empresarial'
            }
        ],
        initComplete: function () {
            $('#dt-export-btn-wrap-pag').append($('.dt-buttons'));

            // Populate corretor select
            var corretores = this.api().column(8).data().unique();
            var $sel = $('#mudar_user_pagamento');
            $sel.empty().append('<option value="">— Todos os Corretores —</option>');
            corretores.each(function (d) {
                $sel.append('<option value="' + d + '">' + d + '</option>');
            });

            // Counts dos botões de Status
            var allRows = this.api().data().toArray();
            var sc = { '': allRows.length, sem_pagamento: 0, saude_so_agenciamento: 0,
                       saude_so_recorrencia: 0, odonto_so_agenciamento: 0,
                       odonto_so_recorrencia: 0, gap_recorrencia: 0 };
            allRows.forEach(function (r) {
                var tipo   = r.tipo_contrato || '';
                var temAgS = parseInt(r.tem_agenciamento_saude  || 0);
                var temReS = parseInt(r.tem_recorrencia_saude   || 0);
                var temAgO = parseInt(r.tem_agenciamento_odonto || 0);
                var temReO = parseInt(r.tem_recorrencia_odonto  || 0);
                var temGap = parseInt(r.tem_gap_recorrencia     || 0);
                var totalC = parseFloat(r.total_comissoes       || 0);
                if (totalC === 0) sc.sem_pagamento++;
                if ((tipo === 'saude' || tipo === 'ambos') && temAgS === 1 && temReS === 0) sc.saude_so_agenciamento++;
                if ((tipo === 'saude' || tipo === 'ambos') && temAgS === 0 && temReS === 1) sc.saude_so_recorrencia++;
                if ((tipo === 'odonto' || tipo === 'ambos') && temAgO === 1 && temReO === 0) sc.odonto_so_agenciamento++;
                if ((tipo === 'odonto' || tipo === 'ambos') && temAgO === 0 && temReO === 1) sc.odonto_so_recorrencia++;
                if (temGap === 1) sc.gap_recorrencia++;
            });
            $('#pagamento-page .status-tag-btn').each(function () {
                var key = $(this).data('status') !== undefined ? ($(this).data('status') || '') : '';
                $(this).find('.status-tag-count').text(sc[key] !== undefined ? sc[key] : 0);
            });

            // ── Matriz Visão por Plano ─────────────────────────────────────────
            var mData = {}, mPlans = [];
            allRows.forEach(function (r) {
                var pl = r.plano || '—';
                if (!mData[pl]) { mData[pl] = { total:0, sem_pagamento:0, so_agenciamento:0, so_recorrencia:0, gap_recorrencia:0 }; mPlans.push(pl); }
                var agS = parseInt(r.tem_agenciamento_saude  || 0), reS = parseInt(r.tem_recorrencia_saude   || 0);
                var agO = parseInt(r.tem_agenciamento_odonto || 0), reO = parseInt(r.tem_recorrencia_odonto  || 0);
                var gap = parseInt(r.tem_gap_recorrencia || 0), tot = parseFloat(r.total_comissoes || 0);
                mData[pl].total++;
                if (tot === 0)                                         mData[pl].sem_pagamento++;
                if ((agS||agO) && !reS && !reO)                        mData[pl].so_agenciamento++;
                if ((reS||reO) && !agS && !agO)                        mData[pl].so_recorrencia++;
                if (gap === 1)                                          mData[pl].gap_recorrencia++;
            });
            mPlans.sort();

            var mCols = [
                { key:'sem_pagamento',   label:'Sem Pag',    color:'#fca5a5', activeBg:'rgba(248,113,113,.18)' },
                { key:'so_agenciamento', label:'Só Agenc',   color:'#86efac', activeBg:'rgba(34,197,94,.18)'   },
                { key:'so_recorrencia',  label:'Só Recorr',  color:'#93c5fd', activeBg:'rgba(59,130,246,.18)'  },
                { key:'gap_recorrencia', label:'Gap',         color:'#fde68a', activeBg:'rgba(251,191,36,.18)'  },
            ];

            var mH = '<table class="pag-matrix-table"><thead><tr>'
                + '<th style="color:rgba(255,255,255,.35);">Plano</th>';
            mCols.forEach(function (c) { mH += '<th style="color:' + c.color + ';">' + c.label + '</th>'; });
            mH += '<th style="color:rgba(255,255,255,.3);">Total</th></tr></thead><tbody>';

            mPlans.forEach(function (pl) {
                var d = mData[pl];
                mH += '<tr><td class="pag-matrix-td-plano" data-plano="' + pl + '">' + pl + '</td>';
                mCols.forEach(function (c) {
                    var n = d[c.key] || 0;
                    mH += n === 0
                        ? '<td class="pag-matrix-num mat-zero">—</td>'
                        : '<td class="pag-matrix-num" data-plano="' + pl + '" data-status="' + c.key + '" data-color="' + c.color + '" data-activebg="' + c.activeBg + '" style="color:' + c.color + ';">' + n + '</td>';
                });
                mH += '<td class="pag-matrix-td-total" data-plano="' + pl + '">' + d.total + '</td></tr>';
            });
            mH += '</tbody></table>';
            $('#pag-matrix-container').html(mH);
            $('#pag-matrix-section').show();

            // ── Select de mês ──────────────────────────────────────────────────
            var mesesSet = {};
            allRows.forEach(function (r) {
                if (r.meses_pagamento) {
                    r.meses_pagamento.split(',').forEach(function (m) { if (m) mesesSet[m] = true; });
                }
            });
            var mesesOrdenados = Object.keys(mesesSet).sort().reverse();
            var $mesSel = $('#filtro_mes_pagamento').empty()
                .append('<option value="">— Todos os Meses —</option>');
            mesesOrdenados.forEach(function (m) {
                var p = m.split('-');
                var label = MESES_PT[parseInt(p[1])] + ' ' + p[0];
                $mesSel.append('<option value="' + m + '">' + label + '</option>');
            });
            if (mesesOrdenados.length) $mesSel.show();

            // Plano buttons (2 per column)
            var api = this.api();
            var planosUnicos = api.column(1).data().unique().toArray().filter(Boolean).sort();
            var $planoContainer = $('#plano-filter-btns-pag');
            $planoContainer.empty();

            var btnEls = [];
            var totalTodos = api.data().count();
            btnEls.push(
                $('<button class="plano-tag-btn plano-tag-todos plano-tag-ativo-pag" data-plano="">'
                + '<span class="plano-tag-dot" style="background:rgba(255,255,255,.4);"></span>'
                + 'Todos<span class="plano-tag-count">' + totalTodos + '</span>'
                + '</button>')
            );
            planosUnicos.forEach(function (plano, i) {
                var c = PLANO_COLORS_PAG[i % PLANO_COLORS_PAG.length];
                var count = api.column(1).data().filter(function (v) { return v === plano; }).count();
                btnEls.push(
                    $('<button class="plano-tag-btn" data-plano="' + plano + '"'
                    + ' data-bg="' + c.bg + '" data-border="' + c.border + '" data-text="' + c.text + '" data-active="' + c.active + '" data-dot="' + c.dot + '"'
                    + ' style="background:' + c.bg + ';border-color:' + c.border + ';color:' + c.text + ';">'
                    + '<span class="plano-tag-dot" style="background:' + c.dot + ';"></span>'
                    + plano + '<span class="plano-tag-count">' + count + '</span>'
                    + '</button>')
                );
            });
            btnEls.forEach(function (btn) { $planoContainer.append(btn); });
        },
        footerCallback: function () {
            var api = this.api();
            var toNum = function (v) { return parseFloat(v) || 0; };
            var fmt   = function (v) { return v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }); };

            var totalVidas  = api.column(9,  { search: 'applied' }).data().reduce(function (a, b) { return toNum(a) + toNum(b); }, 0);
            var totalLinhas = api.column(9,  { search: 'applied' }).data().count();
            var totalValor  = api.column(10, { search: 'applied' }).data().reduce(function (a, b) { return toNum(a) + toNum(b); }, 0);

            $('.pag-total-contratos').html(totalLinhas);
            $('.pag-total-vidas').html(totalVidas);
            $('.pag-total-valor').html(fmt(totalValor));
        }
    });
}

inicializarPagamento();

// ── Atualizar: abre modal de upload ──────────────────────────────────────────
$('#btn-toggle-upload').on('click', function () {
    $('#modal-atualizar-overlay').fadeIn(180);
});
$('#btn-fechar-modal-atualizar').on('click', function () {
    $('#modal-atualizar-overlay').fadeOut(160);
});
$('#modal-atualizar-overlay').on('click', function (e) {
    if ($(e.target).is('#modal-atualizar-overlay')) $(this).fadeOut(160);
});

// ── Corretor filter ───────────────────────────────────────────────────────────
$('#mudar_user_pagamento').on('change', function () {
    tablePagamento.column(8).search($(this).val()).draw();
});

// ── Mês filter ────────────────────────────────────────────────────────────────
$('#filtro_mes_pagamento').on('change', function () {
    filtroMesPag = $(this).val() || null;
    if (tablePagamento) tablePagamento.draw();
});

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
    if (settings.nTable.id !== 'tabela_pagamento') return true;
    if (!filtroMesPag) return true;
    if (!rowData) return true;
    var meses = (rowData.meses_pagamento || '').split(',');
    return meses.indexOf(filtroMesPag) !== -1;
});

// ── Matriz: toggle ───────────────────────────────────────────────────────────
$(document).on('click', '#pag-matrix-toggle-btn', function () {
    var $body = $('#pag-matrix-body');
    var collapsed = $body.is(':hidden');
    $body.slideToggle(180);
    $(this).text(collapsed ? '▲ Recolher' : '▼ Expandir');
});

// ── Matriz: helper ────────────────────────────────────────────────────────────
function aplicarFiltroMatriz(plano, status, $cell) {
    // limpa destaques da matriz
    $('#pag-matrix-container .mat-active').removeClass('mat-active').css({ background: '', borderRadius: '' });

    // reseta botões de plano
    $('#pagamento-page .plano-tag-btn').each(function () {
        var $b = $(this);
        $b.removeClass('plano-tag-ativo-pag');
        if ($b.data('bg')) { $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') }); }
        else { $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' }); }
    });

    // reseta botões de status
    $('#pagamento-page .status-tag-btn').each(function () {
        var $b = $(this);
        $b.removeClass('status-tag-ativo');
        if ($b.data('bg')) { $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') }); }
        else { $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' }); }
    });

    // destaca célula ativa
    if ($cell) {
        $cell.addClass('mat-active');
        var bg = $cell.data('activebg');
        if (bg) $cell.css({ background: bg, borderRadius: '6px' });
    }

    // aplica filtros
    tablePagamento.column(1).search(
        plano ? ('^' + $.fn.dataTable.util.escapeRegex(plano) + '$') : '',
        !!plano, false
    );
    filtroStatusPag = status || null;
    tablePagamento.draw();
}

// ── Matriz: cliques ───────────────────────────────────────────────────────────
$(document).on('click', '#pag-matrix-container .pag-matrix-num:not(.mat-zero)', function () {
    aplicarFiltroMatriz($(this).data('plano'), $(this).data('status'), $(this));
});
$(document).on('click', '#pag-matrix-container .pag-matrix-td-plano', function () {
    aplicarFiltroMatriz($(this).data('plano'), null, $(this));
});
$(document).on('click', '#pag-matrix-container .pag-matrix-td-total', function () {
    aplicarFiltroMatriz($(this).data('plano'), null, $(this));
});

// ── Plano filter ──────────────────────────────────────────────────────────────
$(document).on('click', '#pagamento-page .plano-tag-btn', function () {
    var $btn  = $(this);
    var plano = $btn.data('plano');

    $('#pagamento-page .plano-tag-btn').each(function () {
        var $b = $(this);
        $b.removeClass('plano-tag-ativo-pag');
        if ($b.data('bg')) {
            $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') });
        } else {
            $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' });
        }
    });
    $btn.addClass('plano-tag-ativo-pag');
    if ($btn.data('active')) {
        $btn.css({ background: $btn.data('active'), borderColor: $btn.data('active'), color: '#fff' });
    } else {
        $btn.css({ background: 'rgba(255,255,255,.14)', borderColor: 'rgba(255,255,255,.35)', color: '#fff' });
    }

    $('#pag-matrix-container .mat-active').removeClass('mat-active').css({ background: '', borderRadius: '' });
    tablePagamento.column(1).search(
        plano ? ('^' + $.fn.dataTable.util.escapeRegex(plano) + '$') : '',
        plano ? true : false,
        false
    ).draw();
});

// ── Tipo filter ───────────────────────────────────────────────────────────────
$(document).on('click', '#pagamento-page .tipo-tag-btn', function () {
    var $btn = $(this);
    var tipo = $btn.data('tipo') || null;

    $('#pagamento-page .tipo-tag-btn').each(function () {
        var $b = $(this);
        $b.removeClass('tipo-tag-ativo');
        if ($b.data('bg')) {
            $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') });
        } else {
            $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' });
        }
    });
    $btn.addClass('tipo-tag-ativo');
    if ($btn.data('active')) {
        $btn.css({ background: $btn.data('active'), borderColor: $btn.data('active'), color: '#fff' });
    } else {
        $btn.css({ background: 'rgba(255,255,255,.14)', borderColor: 'rgba(255,255,255,.35)', color: '#fff' });
    }

    filtroTipoPag = tipo;
    if (tablePagamento) tablePagamento.draw();
});

// ── Status filter ─────────────────────────────────────────────────────────────
$(document).on('click', '#pagamento-page .status-tag-btn', function () {
    var $btn   = $(this);
    var status = $btn.data('status') || null;

    $('#pagamento-page .status-tag-btn').each(function () {
        var $b = $(this);
        $b.removeClass('status-tag-ativo');
        if ($b.data('bg')) {
            $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') });
        } else {
            $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' });
        }
    });
    $btn.addClass('status-tag-ativo');
    if ($btn.data('active')) {
        $btn.css({ background: $btn.data('active'), borderColor: $btn.data('active'), color: '#fff' });
    } else {
        $btn.css({ background: 'rgba(255,255,255,.14)', borderColor: 'rgba(255,255,255,.35)', color: '#fff' });
    }

    $('#pag-matrix-container .mat-active').removeClass('mat-active').css({ background: '', borderRadius: '' });
    filtroStatusPag = status;

    // "Todos" = reset completo: limpa plano, tipo, mês e matriz
    if (!status) {
        filtroTipoPag = null;
        filtroMesPag  = null;
        $('#filtro_mes_pagamento').val('');
        tablePagamento.column(8).search('');
        $('#mudar_user_pagamento').val('');
        tablePagamento.column(1).search('', false, false);

        $('#pagamento-page .plano-tag-btn').each(function () {
            var $b = $(this);
            $b.removeClass('plano-tag-ativo-pag');
            if ($b.data('bg')) { $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') }); }
            else { $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' }); }
        });
        $('#pagamento-page .plano-tag-btn[data-plano=""]')
            .addClass('plano-tag-ativo-pag')
            .css({ background: 'rgba(255,255,255,.14)', borderColor: 'rgba(255,255,255,.35)', color: '#fff' });

        $('#pagamento-page .tipo-tag-btn').each(function () {
            var $b = $(this);
            $b.removeClass('tipo-tag-ativo');
            if ($b.data('bg')) { $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') }); }
            else { $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' }); }
        });
        $('#pagamento-page .tipo-tag-btn[data-tipo=""]')
            .addClass('tipo-tag-ativo')
            .css({ background: 'rgba(255,255,255,.14)', borderColor: 'rgba(255,255,255,.35)', color: '#fff' });
    }

    if (tablePagamento) tablePagamento.draw();
});

// ── Row highlight ─────────────────────────────────────────────────────────────
$('#tabela_pagamento').on('click', 'tbody tr', function () {
    tablePagamento.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});

// ── Upload Excel modal ────────────────────────────────────────────────────────
var uploadExcelTipoAtual = null;

var UPLOAD_TIPOS = {
    agenciamento_saude:   { label: 'Agenciamento — Saúde',   cor: '#34d399' },
    agenciamento_odonto:  { label: 'Agenciamento — Odonto',  cor: '#93c5fd' },
    recorrencia_saude:    { label: 'Recorrência — Saúde',    cor: '#6ee7b7' },
    recorrencia_odonto:   { label: 'Recorrência — Odonto',   cor: '#a5b4fc' },
};

function abrirModalUploadExcel(tipo) {
    uploadExcelTipoAtual = tipo;
    var info = UPLOAD_TIPOS[tipo] || { label: tipo, cor: '#fff' };

    $('#modal-upload-excel-title').text(info.label).css('color', info.cor);
    $('#input-upload-excel').val('');
    $('#pag-upload-file-info').hide();
    $('#pag-upload-file-name').text('');
    $('#pag-dropzone-label-text').text('Clique para selecionar ou arraste o arquivo aqui');
    $('#pag-upload-dropzone').css({ borderColor: 'rgba(255,255,255,.18)', background: 'rgba(255,255,255,.02)' });

    $('#btn-enviar-upload-excel')
        .prop('disabled', true)
        .css({ background: 'rgba(79,142,247,.25)', borderColor: 'rgba(79,142,247,.35)', color: 'rgba(79,142,247,.5)', cursor: 'not-allowed' });

    $('#modal-upload-excel-overlay').fadeIn(180);
}

function fecharModalUploadExcel() {
    $('#modal-upload-excel-overlay').fadeOut(160);
    uploadExcelTipoAtual = null;
}

$(document).on('click', '.pag-upload-card', function () {
    $('#modal-atualizar-overlay').fadeOut(160);
    abrirModalUploadExcel($(this).data('tipo'));
});

$('#btn-fechar-modal-upload-excel, #btn-cancelar-upload-excel').on('click', fecharModalUploadExcel);

$('#modal-upload-excel-overlay').on('click', function (e) {
    if ($(e.target).is('#modal-upload-excel-overlay')) fecharModalUploadExcel();
});

$('#pag-upload-dropzone').on('dragover', function (e) {
    e.preventDefault();
    $(this).css({ borderColor: 'rgba(79,142,247,.6)', background: 'rgba(79,142,247,.06)' });
}).on('dragleave drop', function (e) {
    e.preventDefault();
    $(this).css({ borderColor: 'rgba(255,255,255,.18)', background: 'rgba(255,255,255,.02)' });
    if (e.type === 'drop') {
        var files = e.originalEvent.dataTransfer.files;
        if (files && files.length) {
            $('#input-upload-excel').prop('files', files);
            triggerFileSelected(files[0]);
        }
    }
});

$('#input-upload-excel').on('change', function () {
    if (this.files && this.files.length) triggerFileSelected(this.files[0]);
});

function triggerFileSelected(file) {
    $('#pag-upload-file-name').text(file.name);
    $('#pag-upload-file-info').css('display', 'flex');
    $('#pag-dropzone-label-text').text(file.name);
    $('#btn-enviar-upload-excel')
        .prop('disabled', false)
        .css({ background: '#4f8ef7', borderColor: '#4f8ef7', color: '#fff', cursor: 'pointer' });
}

$('#btn-enviar-upload-excel').on('click', function () {
    if ($(this).prop('disabled')) return;
    var file = $('#input-upload-excel')[0].files[0];
    if (!file || !uploadExcelTipoAtual) return;

    var $btn = $(this);
    $btn.prop('disabled', true).text('Enviando...').css('cursor', 'not-allowed');

    var formData = new FormData();
    formData.append('arquivo', file);
    formData.append('tipo', uploadExcelTipoAtual);
    formData.append('_token', csrfToken);

    $.ajax({
        url:         urlUploadPlanilha,
        type:        'POST',
        data:        formData,
        processData: false,
        contentType: false,
        success: function (res) {
            fecharModalUploadExcel();
            var cor = res.nao_vinculados > 0 ? '#fbbf24' : '#34d399';
            var msg = res.mensagem;
            $('<div>')
                .text(msg)
                .css({
                    position:'fixed', bottom:'28px', left:'50%', transform:'translateX(-50%)',
                    background:'#1a2540', border:'1px solid ' + cor, color: cor,
                    padding:'10px 22px', borderRadius:'10px', fontSize:'.82rem',
                    fontWeight:'700', zIndex:9999, boxShadow:'0 8px 30px rgba(0,0,0,.4)'
                })
                .appendTo('body')
                .delay(4000).fadeOut(400, function () { $(this).remove(); });

            // Recarrega a tabela principal para atualizar última parcela
            if (tablePagamento) tablePagamento.ajax.reload(null, false);
            carregarContadorNaoVinculados();
        },
        error: function (xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Erro ao importar a planilha.';
            $('<div>')
                .text(msg)
                .css({
                    position:'fixed', bottom:'28px', left:'50%', transform:'translateX(-50%)',
                    background:'#1a2540', border:'1px solid #f87171', color:'#f87171',
                    padding:'10px 22px', borderRadius:'10px', fontSize:'.82rem',
                    fontWeight:'700', zIndex:9999, boxShadow:'0 8px 30px rgba(0,0,0,.4)'
                })
                .appendTo('body')
                .delay(4000).fadeOut(400, function () { $(this).remove(); });

            $btn.prop('disabled', false).text('Enviar')
                .css({ background:'#4f8ef7', borderColor:'#4f8ef7', color:'#fff', cursor:'pointer' });
        }
    });
});

// ── Detalhe: olhinho ──────────────────────────────────────────────────────────
var TIPO_LABELS = {
    agenciamento_saude:  { label: 'Agenciamento',  cor: '#34d399' },
    agenciamento_odonto: { label: 'Agenciamento',  cor: '#93c5fd' },
    recorrencia_saude:   { label: 'Recorrência',   cor: '#6ee7b7' },
    recorrencia_odonto:  { label: 'Recorrência',   cor: '#a5b4fc' },
};

$(document).on('click', '.btn-detalhe-pagamento', function (e) {
    e.stopPropagation();
    var id = $(this).data('id');

    $('#modal-detalhe-loading').show();
    $('#modal-detalhe-content').hide();
    $('#modal-detalhe-titulo').text('Pagamentos do Contrato');
    $('#modal-detalhe-sub').text('');
    $('#modal-detalhe-overlay').fadeIn(180);

    $.get(urlDetalhePagamento + '/' + id, function (res) {
        $('#modal-detalhe-loading').hide();

        var c = res.contrato;
        if (c) {
            $('#modal-detalhe-titulo').text(c.razao_social || 'Contrato #' + id);
            var codigos = [];
            if (c.codigo_saude)  codigos.push('Saúde: ' + c.codigo_saude);
            if (c.codigo_odonto) codigos.push('Odonto: ' + c.codigo_odonto);
            $('#modal-detalhe-sub').text(codigos.join(' | ') + (c.cnpj ? '  •  CNPJ: ' + c.cnpj : ''));
        }

        var $tbody = $('#modal-detalhe-tbody').empty();
        var pagamentos = res.pagamentos || [];

        if (pagamentos.length === 0) {
            $('#modal-detalhe-vazio').show();
            $('#tabela-detalhe-pagamentos').hide();
        } else {
            $('#modal-detalhe-vazio').hide();
            $('#tabela-detalhe-pagamentos').show();

            var fmt = function (v) {
                if (!v && v !== 0) return '—';
                return 'R$ ' + parseFloat(v).toLocaleString('pt-BR', { minimumFractionDigits:2, maximumFractionDigits:2 });
            };
            var fmtPct = function (v) {
                if (!v && v !== 0) return '—';
                return parseFloat(v).toLocaleString('pt-BR', { minimumFractionDigits:2, maximumFractionDigits:2 }) + '%';
            };

            pagamentos.forEach(function (p) {
                var info = TIPO_LABELS[p.tipo_planilha] || { label: p.tipo_planilha, cor: '#fff' };
                var venc = p.vencimento
                    ? p.vencimento.split('-').reverse().join('/')
                    : '—';
                var isAgenc = p.tipo_planilha.startsWith('agenciamento');
                var parcelaStr = isAgenc
                    ? '<span style="color:#fbbf24;font-weight:700;">1ª</span>'
                    : '<span style="font-weight:700;">' + (p.parcela || '—') + 'ª</span>';

                $tbody.append(
                    '<tr style="border-bottom:1px solid rgba(255,255,255,.05);transition:background .1s;" '
                  + 'onmouseover="this.style.background=\'rgba(79,142,247,.07)\'" '
                  + 'onmouseout="this.style.background=\'\'">'
                  + '<td style="padding:7px 6px;"><span style="color:' + info.cor + ';font-size:.7rem;font-weight:700;">' + info.label + '</span></td>'
                  + '<td style="padding:7px 6px;">' + parcelaStr + '</td>'
                  + '<td style="padding:7px 6px;white-space:nowrap;">' + venc + '</td>'
                  + '<td style="padding:7px 6px;text-align:right;white-space:nowrap;">' + fmt(p.vl_base_com) + '</td>'
                  + '<td style="padding:7px 6px;text-align:right;">' + fmtPct(p.pct_imposto) + '</td>'
                  + '<td style="padding:7px 6px;text-align:right;white-space:nowrap;">' + fmt(p.vl_liquido) + '</td>'
                  + '<td style="padding:7px 6px;text-align:right;">' + fmtPct(p.pc_dist) + '</td>'
                  + '<td style="padding:7px 6px;text-align:right;white-space:nowrap;color:#34d399;font-weight:700;">' + fmt(p.vl_a_pagar) + '</td>'
                  + '<td style="padding:7px 6px;color:rgba(255,255,255,.35);font-size:.65rem;white-space:nowrap;">' + (p.arquivo_original || '—') + '</td>'
                  + '</tr>'
                );
            });
        }

        $('#modal-detalhe-content').show();
    }).fail(function () {
        $('#modal-detalhe-loading').hide();
        $('#modal-detalhe-content').show();
        $('#modal-detalhe-vazio').show().text('Erro ao carregar pagamentos.');
    });
});

$('#btn-fechar-modal-detalhe').on('click', function () {
    $('#modal-detalhe-overlay').fadeOut(160);
});
$('#modal-detalhe-overlay').on('click', function (e) {
    if ($(e.target).is('#modal-detalhe-overlay')) $('#modal-detalhe-overlay').fadeOut(160);
});

// ── Não Vinculados ────────────────────────────────────────────────────────────
var NV_TIPO_LABELS = {
    agenciamento_saude:  { label: 'Agenc. Saúde',   cor: '#34d399' },
    agenciamento_odonto: { label: 'Agenc. Odonto',  cor: '#93c5fd' },
    recorrencia_saude:   { label: 'Recorr. Saúde',  cor: '#6ee7b7' },
    recorrencia_odonto:  { label: 'Recorr. Odonto', cor: '#a5b4fc' },
};

function nvEsc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function nvBtnHtml(id) {
    return '<button class="nv-btn-abrir-busca" data-id="' + id + '"'
         + ' style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#fca5a5;'
         + 'padding:4px 12px;border-radius:7px;font-size:.72rem;font-weight:700;cursor:pointer;white-space:nowrap;">🔗 Vincular</button>';
}

function carregarContadorNaoVinculados() {
    $.get(urlNaoVinculados, function (res) {
        var count = parseInt(res.count) || 0;
        if (count > 0) {
            $('#nao-vinc-count').text(count);
            $('#btn-nao-vinculados').css('display', 'inline-flex');
        } else {
            $('#btn-nao-vinculados').hide();
        }
    });
}

carregarContadorNaoVinculados();

function renderNaoVinculados(registros) {
    var $tbody = $('#nv-tbody').empty();
    if (!registros || registros.length === 0) {
        $('#nv-vazio').show();
        $('#nv-table-wrap').hide();
        return;
    }
    $('#nv-vazio').hide();
    $('#nv-table-wrap').show();

    var fmt = function (v) {
        if (!v && v !== 0) return '—';
        return 'R$ ' + parseFloat(v).toLocaleString('pt-BR', { minimumFractionDigits:2, maximumFractionDigits:2 });
    };

    registros.forEach(function (reg) {
        var info   = NV_TIPO_LABELS[reg.tipo_planilha] || { label: reg.tipo_planilha, cor: '#fff' };
        var venc   = reg.vencimento ? reg.vencimento.split('-').reverse().join('/') : '—';
        var parc   = reg.parcela ? (reg.parcela + 'ª') : '—';
        var $tr    = $('<tr data-id="' + reg.id + '" style="border-bottom:1px solid rgba(255,255,255,.05);">');

        $tr.html(
            '<td style="padding:7px 6px;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="' + nvEsc(reg.empresa_conveniada) + '">' + nvEsc(reg.empresa_conveniada || '—') + '</td>'
          + '<td style="padding:7px 6px;white-space:nowrap;"><span style="color:' + info.cor + ';font-size:.7rem;font-weight:700;">' + info.label + '</span></td>'
          + '<td style="padding:7px 6px;text-align:center;white-space:nowrap;">' + parc + '</td>'
          + '<td style="padding:7px 6px;white-space:nowrap;">' + venc + '</td>'
          + '<td style="padding:7px 6px;text-align:right;white-space:nowrap;color:#34d399;">' + fmt(reg.vl_a_pagar) + '</td>'
          + '<td style="padding:7px 6px;color:rgba(255,255,255,.3);font-size:.65rem;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="' + nvEsc(reg.arquivo_original) + '">' + nvEsc(reg.arquivo_original || '—') + '</td>'
          + '<td class="nv-vincular-cell" style="padding:7px 6px;position:relative;">' + nvBtnHtml(reg.id) + '</td>'
        );
        $tbody.append($tr);
    });
}

$('#btn-nao-vinculados').on('click', function () {
    $('#nv-loading').show();
    $('#nv-content').hide();
    $('#modal-nao-vinc-overlay').fadeIn(180);

    $.get(urlNaoVinculados, function (res) {
        $('#nv-loading').hide();
        renderNaoVinculados(res.registros || []);
        $('#nv-content').show();
    }).fail(function () {
        $('#nv-loading').hide();
        $('#nv-content').show();
        $('#nv-vazio').show().find('div:first').text('Erro ao carregar registros.');
    });
});

// Abrir busca inline
$(document).on('click', '.nv-btn-abrir-busca', function () {
    var id    = $(this).data('id');
    var $cell = $(this).closest('td');
    $cell.html(
        '<div style="position:relative;">'
      + '<input class="nv-search-input" data-id="' + id + '" placeholder="Razão social, CNPJ ou código…" autocomplete="off"'
      + ' style="width:100%;background:#1a2540;border:1px solid rgba(239,68,68,.4);color:#e2e8f0;border-radius:7px;padding:5px 10px;font-size:.75rem;outline:none;box-sizing:border-box;">'
      + '<div class="nv-search-results" style="display:none;position:absolute;top:calc(100% + 2px);left:0;right:0;background:#1a2540;border:1px solid rgba(79,142,247,.3);border-radius:7px;z-index:9999;max-height:200px;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,.5);"></div>'
      + '<button class="nv-btn-cancelar-busca" data-id="' + id + '" style="margin-top:4px;background:none;border:none;color:rgba(255,255,255,.3);font-size:.68rem;cursor:pointer;padding:2px 0;">cancelar</button>'
      + '</div>'
    );
    $cell.find('.nv-search-input').focus();
});

// Cancelar busca → restaurar botão
$(document).on('click', '.nv-btn-cancelar-busca', function () {
    var id    = $(this).data('id');
    $(this).closest('td').html(nvBtnHtml(id));
});

// Search com debounce
var nvSearchTimer = null;
$(document).on('input', '.nv-search-input', function () {
    var $input   = $(this);
    var q        = $input.val().trim();
    var $results = $input.siblings('.nv-search-results');

    clearTimeout(nvSearchTimer);
    if (q.length < 2) { $results.hide().empty(); return; }

    nvSearchTimer = setTimeout(function () {
        $results.html('<div style="padding:10px 12px;color:rgba(255,255,255,.3);font-size:.74rem;">Buscando…</div>').show();

        $.get(urlBuscarContratos, { q: q }, function (contratos) {
            $results.empty();
            if (!contratos.length) {
                $results.html('<div style="padding:10px 12px;color:rgba(255,255,255,.3);font-size:.74rem;">Nenhum contrato encontrado.</div>').show();
                return;
            }
            var ETAPA_LABELS = {
                1:'Cadastro',2:'Planilha',3:'Aditivo',4:'Adesão',
                5:'Boleto',6:'Vigência',7:'Carteirinha',8:'Finalizado'
            };
            contratos.forEach(function (c) {
                var codes = [c.codigo_saude, c.codigo_odonto].filter(Boolean).join(' / ');
                var etapa = parseInt(c.etapa_atual) || 0;
                var etapaLabel  = etapa ? (ETAPA_LABELS[etapa] || ('Etapa ' + etapa)) : null;
                var etapaCor    = etapa === 8 ? '#34d399' : '#fbbf24';
                var etapaHtml   = etapaLabel
                    ? '<span style="color:' + etapaCor + ';font-size:.65rem;font-weight:700;background:rgba(255,255,255,.06);'
                      + 'border-radius:4px;padding:1px 5px;margin-left:4px;">' + etapaLabel + '</span>'
                    : '';
                var $item = $('<div class="nv-result-item" data-id="' + c.id + '" data-nome="' + nvEsc(c.razao_social) + '"'
                    + ' style="padding:8px 12px;cursor:pointer;border-bottom:1px solid rgba(255,255,255,.06);transition:background .1s;">'
                    + '<div style="color:#e2e8f0;font-size:.77rem;font-weight:600;display:flex;align-items:center;gap:4px;flex-wrap:wrap;">'
                    + nvEsc(c.razao_social) + etapaHtml + '</div>'
                    + '<div style="color:rgba(255,255,255,.35);font-size:.67rem;margin-top:2px;">'
                    + (codes ? codes + '  ·  ' : '') + 'CNPJ: ' + (c.cnpj || '—')
                    + '</div>'
                    + '</div>');
                $results.append($item);
            });
            $results.show();
        }).fail(function () {
            $results.html('<div style="padding:10px 12px;color:#f87171;font-size:.74rem;">Erro na busca.</div>').show();
        });
    }, 280);
});

$(document).on('mouseenter', '.nv-result-item', function () {
    $(this).css('background', 'rgba(79,142,247,.12)');
}).on('mouseleave', '.nv-result-item', function () {
    $(this).css('background', '');
});

// Selecionar resultado → mostrar confirmação
$(document).on('click', '.nv-result-item', function (e) {
    e.stopPropagation();
    var contratoId   = $(this).data('id');
    var contratoNome = $(this).data('nome');
    var $cell        = $(this).closest('td');
    var $tr          = $(this).closest('tr');
    var regId        = $tr.data('id');

    $cell.html(
        '<div style="font-size:.73rem;color:#93c5fd;font-weight:600;margin-bottom:5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;" title="' + nvEsc(contratoNome) + '">' + nvEsc(contratoNome) + '</div>'
      + '<div style="display:flex;gap:6px;">'
      + '<button class="nv-btn-confirmar" data-reg-id="' + regId + '" data-contrato-id="' + contratoId + '"'
      + ' style="background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.35);color:#86efac;padding:3px 10px;border-radius:6px;font-size:.7rem;font-weight:700;cursor:pointer;">Confirmar</button>'
      + '<button class="nv-btn-cancelar-busca" data-id="' + regId + '"'
      + ' style="background:none;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.4);padding:3px 8px;border-radius:6px;font-size:.7rem;cursor:pointer;">Cancelar</button>'
      + '</div>'
    );
});

// Confirmar vínculo
$(document).on('click', '.nv-btn-confirmar', function () {
    var $btn       = $(this);
    var regId      = $btn.data('reg-id');
    var contratoId = $btn.data('contrato-id');
    var $tr        = $btn.closest('tr');

    $btn.prop('disabled', true).text('Salvando…');

    $.ajax({
        url:  urlVincularBase + '/' + regId,
        type: 'POST',
        data: { _token: csrfToken, contrato_id: contratoId },
        success: function (res) {
            $tr.css('background', 'rgba(34,197,94,.08)');
            setTimeout(function () {
                $tr.fadeOut(400, function () {
                    $(this).remove();
                    var restam = (res.restam !== undefined) ? parseInt(res.restam) : Math.max(0, parseInt($('#nao-vinc-count').text()) - 1);
                    $('#nao-vinc-count').text(restam);
                    if (restam <= 0) {
                        $('#btn-nao-vinculados').hide();
                        $('#nv-table-wrap').hide();
                        $('#nv-vazio').show();
                    }
                });
            }, 260);
        },
        error: function (xhr) {
            $btn.prop('disabled', false).text('Confirmar');
            var msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Erro ao vincular.';
            $('<div>').text(msg).css({
                position:'fixed', bottom:'28px', left:'50%', transform:'translateX(-50%)',
                background:'#1a2540', border:'1px solid #f87171', color:'#f87171',
                padding:'10px 22px', borderRadius:'10px', fontSize:'.82rem',
                fontWeight:'700', zIndex:9999, boxShadow:'0 8px 30px rgba(0,0,0,.4)'
            }).appendTo('body').delay(3500).fadeOut(400, function () { $(this).remove(); });
        }
    });
});

$('#btn-fechar-modal-nao-vinc').on('click', function () {
    $('#modal-nao-vinc-overlay').fadeOut(160);
});
$('#modal-nao-vinc-overlay').on('click', function (e) {
    if ($(e.target).is('#modal-nao-vinc-overlay')) $(this).fadeOut(160);
});

// Fechar dropdown ao clicar fora da célula
$(document).on('click', function (e) {
    if (!$(e.target).closest('.nv-vincular-cell').length) {
        $('.nv-search-results').hide();
    }
});
