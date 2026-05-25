var tableempresarial;

var ETAPA_NOMES = ['Cadastro','Planilha','Contrato','Adesão','Vencimento','Vigência','Carteiras','1º Boleto','Finalizado'];

// Paleta de cores para os botões de plano
var PLANO_COLORS = [
    { text: '#93c5fd', border: 'rgba(59,130,246,.4)',  bg: 'rgba(59,130,246,.1)',  active: '#3b82f6', dot: '#3b82f6'  },
    { text: '#c4b5fd', border: 'rgba(139,92,246,.4)',  bg: 'rgba(139,92,246,.1)',  active: '#8b5cf6', dot: '#8b5cf6'  },
    { text: '#fcd34d', border: 'rgba(245,158,11,.4)',  bg: 'rgba(245,158,11,.1)',  active: '#f59e0b', dot: '#f59e0b'  },
    { text: '#86efac', border: 'rgba(34,197,94,.4)',   bg: 'rgba(34,197,94,.1)',   active: '#22c55e', dot: '#22c55e'  },
    { text: '#f9a8d4', border: 'rgba(236,72,153,.4)',  bg: 'rgba(236,72,153,.1)',  active: '#ec4899', dot: '#ec4899'  },
    { text: '#67e8f9', border: 'rgba(6,182,212,.4)',   bg: 'rgba(6,182,212,.1)',   active: '#06b6d4', dot: '#06b6d4'  },
    { text: '#fdba74', border: 'rgba(249,115,22,.4)',  bg: 'rgba(249,115,22,.1)',  active: '#f97316', dot: '#f97316'  },
    { text: '#a5b4fc', border: 'rgba(99,102,241,.4)',  bg: 'rgba(99,102,241,.1)',  active: '#6366f1', dot: '#6366f1'  },
];

// ── Ícones SVG das etapas ─────────────────────────────────────────────────────
var SVG_CHECK = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:20px;height:20px"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.06-1.06l-3.31 3.31-1.48-1.48a.75.75 0 0 0-1.06 1.06l2.01 2.01a.75.75 0 0 0 1.06 0l3.84-3.84Z" clip-rule="evenodd"/></svg>';

var SVG_LOCK = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(255,255,255,.2)" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>';

var SVG_UPLOAD = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>';

var SVG_PDF = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>';

var SVG_CALENDAR = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>';

var SVG_RECEIPT = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/></svg>';

var SVG_CARD = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z"/></svg>';

var SVG_BILL = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>';

var SVG_FLAG = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f59e0b" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"/></svg>';

var SVG_EYE = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(79,142,247,.9)" style="width:13px;height:13px;vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>';

var SVG_DOWNLOAD = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#4f8ef7" style="width:15px;height:15px;margin-left:4px;vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>';

var SVG_EDIT = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(148,163,184,.7)" style="width:12px;height:12px;vertical-align:middle;cursor:pointer;"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>';

var SVG_PDF_DL = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f87171" style="width:14px;height:14px;vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>';

var SVG_WARNING = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fbbf24" style="width:14px;height:14px;vertical-align:middle;cursor:pointer;margin-left:3px;"><path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/></svg>';

var SVG_HEART_SM = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:11px;height:11px;flex-shrink:0;"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>';

var SVG_TOOTH_SM = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:11px;height:11px;flex-shrink:0;"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/></svg>';

// ── Render factory para as colunas de etapa ───────────────────────────────────
// data = row.id | row = linha completa com etapa_atual
// fnDone (opcional): renderer personalizado quando a etapa já está concluída
function criarRenderEtapa(stepNum, label, svgAvail, fnDone) {
    return function (data, type, row) {
        if (type !== 'display') return data;
        var etapa = parseInt(row.etapa_atual) || 0;
        if (etapa >= stepNum) {
            if (typeof fnDone === 'function') return fnDone(data, row);
            return '<div class="etapa-cell etapa-done" title="' + label + ' ✓">' + SVG_CHECK + '</div>';
        } else if (etapa === stepNum - 1) {
            return '<div class="etapa-cell etapa-avail" data-id="' + data + '" data-step="' + stepNum + '" data-label="' + label + '" title="' + label + '">' + svgAvail + '</div>';
        } else {
            return '<div class="etapa-cell etapa-lock" title="' + label + ' (bloqueada)">' + SVG_LOCK + '</div>';
        }
    };
}

// ── Renderers "done" — estrutura consistente: check+canetinha no topo, conteúdo abaixo ──

function renderEtapa8Done(id, row) {
    var editar = '<span class="etapa8-editar" data-id="' + id + '" title="Editar Finalizado" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var dl = '';
    if (row.finalizado_pdf_path) {
        var url = (typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/') + row.finalizado_pdf_path;
        dl = '<a href="' + url + '" target="_blank" title="Baixar PDF Final" style="line-height:0;">' + SVG_PDF_DL + '</a>';
    }
    var date = row.data_baixa_finalizado ? '<span class="etapa-date">' + row.data_baixa_finalizado + '</span>' : '';
    return '<div class="etapa-cell etapa-done etapa2-done" title="Finalizado ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + (dl ? '<div class="etapa2-icons" style="margin-top:2px;">' + dl + '</div>' : '')
        + (date ? '<div style="margin-top:2px;text-align:center;">' + date + '</div>' : '')
        + '</div>';
}

function renderEtapa7Done(id, row) {
    var editar = '<span class="etapa7-editar" data-id="' + id + '" title="Editar 1º Boleto" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var base   = typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/';
    var docs   = [
        { path: row.boleto_saude_path,        title: 'Boleto Saúde'         },
        { path: row.demonstrativo_saude_path,  title: 'Demonstrativo Saúde' },
        { path: row.boleto_odonto_path,        title: 'Boleto Odonto'        },
        { path: row.demonstrativo_odonto_path, title: 'Demonstrativo Odonto' },
    ];
    var links = '';
    docs.forEach(function (d) {
        if (d.path) links += '<a href="' + base + d.path + '" target="_blank" title="' + d.title + '" style="line-height:0;">' + SVG_PDF_DL + '</a>';
    });
    var date = row.data_primeiro_boleto ? '<span class="etapa-date">' + row.data_primeiro_boleto + '</span>' : '';
    return '<div class="etapa-cell etapa-done etapa2-done" title="1º Boleto ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + (links ? '<div class="etapa2-icons" style="margin-top:2px;gap:2px;">' + links + '</div>' : '')
        + date
        + '</div>';
}

function renderEtapa6Done(id, row) {
    var paths = [];
    try { paths = JSON.parse(row.carteirinha_paths || '[]'); } catch (e) {}
    if (!Array.isArray(paths)) paths = [];

    var editar = '<span class="etapa6-editar" data-id="' + id + '" title="Adicionar carteirinhas" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var links  = '';
    paths.slice(0, 3).forEach(function (p, i) {
        var url = (typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/') + p;
        links += '<a href="' + url + '" target="_blank" title="Carteirinha ' + (i + 1) + '" style="line-height:0;">' + SVG_PDF_DL + '</a>';
    });
    if (paths.length > 3) links += '<span class="etapa-date">+' + (paths.length - 3) + '</span>';

    var countText = paths.length > 0 ? '<span class="etapa-date">' + paths.length + ' arq.</span>' : '';
    var dateText  = row.data_carteirinha ? '<span class="etapa-date">' + row.data_carteirinha + '</span>' : '';
    return '<div class="etapa-cell etapa-done etapa2-done" title="Carteirinhas ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + (links ? '<div class="etapa2-icons" style="margin-top:2px;gap:1px;">' + links + '</div>' : '')
        + countText + dateText
        + '</div>';
}

function renderEtapa5Done(id, row) {
    var editar = '<span class="etapa5-editar" data-id="' + id + '" title="Editar Vigência" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var code   = row.codigo_saude ? '<span class="etapa-date" style="color:#34d399;">' + row.codigo_saude + '</span>' : '';
    var date   = row.data_vigencia ? '<span class="etapa-date">' + row.data_vigencia + '</span>' : '';
    return '<div class="etapa-cell etapa-done etapa2-done" title="Vigência ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + code + date
        + '</div>';
}

function renderEtapa4Done(id, row) {
    var editar = '<span class="etapa4-editar" data-id="' + id + '" title="Editar PG Boleto" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var forma  = row.forma_pagamento ? '<span class="etapa-date" style="color:#34d399;">' + row.forma_pagamento + '</span>' : '';
    var date   = row.data_pgto ? '<span class="etapa-date">' + row.data_pgto + '</span>' : '';
    return '<div class="etapa-cell etapa-done etapa2-done" title="PG Boleto ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + forma + date
        + '</div>';
}

function renderEtapa3Done(id, row) {
    var editar = '<span class="etapa3-editar" data-id="' + id + '" title="Re-enviar boleto de adesão" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var dl = '';
    if (row.boleto_adesao_path) {
        var url = (typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/') + row.boleto_adesao_path;
        dl = '<a href="' + url + '" target="_blank" title="Baixar boleto de adesão" style="line-height:0;">' + SVG_PDF_DL + '</a>';
    }
    var warn = '';
    if (parseInt(row.tem_diferenca_valor) === 1) {
        var just = (row.justificativa_diferenca || '').replace(/"/g, '&quot;');
        var bval = row.boleto_adesao_valor || '';
        var pval = row.valor_plano || '';
        warn = '<span class="etapa3-warn-icon" data-justificativa="' + just + '" data-boleto-valor="' + bval + '" data-planilha-valor="' + pval + '" title="Diferença de valor — clique para ver justificativa">' + SVG_WARNING + '</span>';
    }
    var date = row.data_adesao ? '<span class="etapa-date">' + row.data_adesao + '</span>' : '';
    return '<div class="etapa-cell etapa-done etapa2-done" title="Adesão ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + (dl || warn ? '<div class="etapa2-icons" style="margin-top:2px;">' + dl + warn + '</div>' : '')
        + (date ? '<div style="margin-top:2px;text-align:center;">' + date + '</div>' : '')
        + '</div>';
}

function renderEtapa2Done(id, row) {
    var editar = '<span class="etapa2-editar" data-id="' + id + '" title="Re-enviar aditivo" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var dl = '';
    if (row.aditivo_path) {
        var url = (typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/') + row.aditivo_path;
        dl = '<a href="' + url + '" target="_blank" title="Baixar aditivo PDF" style="line-height:0;">' + SVG_PDF_DL + '</a>';
    }
    var date = row.data_aditivo ? '<span class="etapa-date">' + row.data_aditivo + '</span>' : '';
    return '<div class="etapa-cell etapa-done etapa2-done" title="Aditivo enviado ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + (dl ? '<div class="etapa2-icons" style="margin-top:2px;">' + dl + '</div>' : '')
        + (date ? '<div style="margin-top:2px;text-align:center;">' + date + '</div>' : '')
        + '</div>';
}

function renderEtapa1Done(id, row) {
    var editar = '<span class="etapa1-editar" data-id="' + id + '" title="Re-importar planilha" style="line-height:0;margin-left:6px;">' + SVG_EDIT + '</span>';
    var dl = '';
    if (row.planilha_path) {
        var url = (typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/') + row.planilha_path;
        dl = '<a href="' + url + '" download title="Baixar planilha" style="line-height:0;">' + SVG_DOWNLOAD + '</a>';
    }
    return '<div class="etapa-cell etapa-done etapa2-done" title="Planilha importada ✓">'
        + '<div class="etapa2-icons">' + SVG_CHECK + editar + '</div>'
        + (dl ? '<div class="etapa2-icons" style="margin-top:2px;justify-content:center;">' + dl + '</div>' : '')
        + '</div>';
}

// ── DataTable ────────────────────────────────────────────────────────────────
function inicializarEmpresarial(corretora_id) {

    if ($.fn.DataTable.isDataTable('.listarempresarial')) {
        $('.listarempresarial').DataTable().destroy();
    }

    tableempresarial = $(".listarempresarial").DataTable({
        dom: '<"flex justify-between"<"#title_empresarial">Bftr><t><"flex justify-between"lp>',
        language: {
            search: "Pesquisar",
            paginate: { next: "Próx.", previous: "Ant.", first: "Primeiro", last: "Último" },
            emptyTable:    "Nenhum registro encontrado",
            info:          "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            infoEmpty:     "Mostrando 0 até 0 de 0 registros",
            infoFiltered:  "(Filtrados de _MAX_ registros)",
            infoThousands: ".",
            loadingRecords:"Carregando...",
            processing:    "Processando...",
            lengthMenu:    "Exibir _MENU_ por página",
            zeroRecords:   "Nenhum registro encontrado"
        },
        ajax: {
            url: urlGeralEmpresarialPendentes,
            data: function (d) { d.corretora_id = corretora_id; }
        },
        lengthMenu: [500, 1000, 2000],
        ordering:   false,
        paging:     true,
        searching:  true,
        info:       true,
        autoWidth:  false,
        responsive: true,
        processing: true,
        columns: [
            // ── Dados do contrato ──────────────────────────────────────────
            { data: "tipo_contrato", name: "tipo_contrato", orderable: false, width: "4%", className: "dt-center col-tipo", // 0
              render: function (data, type, row) {
                  if (type !== 'display') return data || '';
                  if (data === 'ambos') {
                      return '<div style="display:inline-flex;flex-direction:column;gap:2px;align-items:flex-start;">'
                          + '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_HEART_SM + 'Saúde</span>'
                          + '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_TOOTH_SM + 'Odonto</span>'
                          + '</div>';
                  } else if (data === 'saude') {
                      return '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_HEART_SM + 'Saúde</span>';
                  } else if (data === 'odonto') {
                      return '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.62rem;font-weight:600;line-height:1;">' + SVG_TOOTH_SM + 'Odonto</span>';
                  }
                  return '-';
              }
            },
            { data: "plano",          name: "plano",          width: "5%"  }, // 1
            { data: "created_at",     name: "created_at",     width: "6%"  }, // 2
            { data: "codigo_externo", name: "codigo_externo", width: "6%", className: "col-codigo", // 3
              render: function (data, type, row) {
                  if (type !== 'display') return data || '';
                  var icoS = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>';
                  var icoO = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/></svg>';
                  var cS = row.codigo_saude  || null;
                  var cO = row.codigo_odonto || null;
                  var tipo = row.tipo_contrato || null;
                  if (tipo === 'ambos') {
                      return '<div style="display:flex;flex-direction:column;gap:2px;">'
                          + '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.7rem;">' + icoS + (cS || '-') + '</span>'
                          + '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.7rem;">' + icoO + (cO || '-') + '</span>'
                          + '</div>';
                  } else if (tipo === 'saude') {
                      return '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.7rem;">' + icoS + (cS || '-') + '</span>';
                  } else if (tipo === 'odonto') {
                      return '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.7rem;">' + icoO + (cO || '-') + '</span>';
                  }
                  return data || '-';
              }
            },
            { data: "cnpj",           name: "cnpj",           width: "10%" }, // 4
            { data: "razao_social",   name: "razao_social",   width: "11%" }, // 5
            { data: "uf",             name: "uf",             width: "3%"  }, // 6
            { data: "cidade",         name: "cidade",         width: "6%"  }, // 7
            { data: "usuario",        name: "usuario",        width: "7%"  }, // 8

            // ── Vidas + Valor ──────────────────────────────────────────────
            { data: "quantidade_vidas", name: "quantidade_vidas", width: "3%", className: "dt-center" }, // 9
            { data: "valor_plano", name: "valor_plano", width: "8%", className: "dt-right",             // 10
              render: function (data, type, row) {
                  if (type !== 'display') return data;
                  if (!data) return '-';
                  var fmtNum = function (v) {
                      return 'R$ ' + parseFloat(v || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                  };
                  var tipo       = row.tipo_contrato || null;
                  var temBenef   = parseInt(row.quantidade_vidas) > 0;
                  var eyeBtn     = temBenef
                      ? '<button class="btn-beneficiarios" data-id="' + row.id + '" title="Ver beneficiários"'
                        + ' style="background:none;border:none;padding:0;cursor:pointer;line-height:0;flex-shrink:0;">'
                        + SVG_EYE + '</button>'
                      : '';
                  var valS = parseFloat(row.valor_saude  || 0);
                  var valO = parseFloat(row.valor_odonto || 0);

                  var icoS = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>';
                  var icoO = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#93c5fd" style="width:10px;height:10px;flex-shrink:0;vertical-align:middle;"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/></svg>';

                  var valorHtml;
                  if (tipo === 'ambos') {
                      var total = valS + valO;
                      valorHtml = '<div style="display:flex;flex-direction:column;gap:1px;align-items:flex-end;">'
                          + '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;font-size:.74rem;white-space:nowrap;">' + icoS + fmtNum(valS) + '</span>'
                          + '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;font-size:.74rem;white-space:nowrap;">' + icoO + fmtNum(valO) + '</span>'
                          + '<span style="color:rgba(255,255,255,.2);font-size:.6rem;letter-spacing:.05em;align-self:stretch;text-align:right;">──────</span>'
                          + '<span style="font-size:.74rem;font-weight:700;color:#fcd34d;white-space:nowrap;">' + fmtNum(total) + '</span>'
                          + '</div>';
                  } else if (tipo === 'saude') {
                      valorHtml = '<span style="display:inline-flex;align-items:center;gap:3px;color:#34d399;white-space:nowrap;">' + icoS + fmtNum(valS) + '</span>';
                  } else if (tipo === 'odonto') {
                      valorHtml = '<span style="display:inline-flex;align-items:center;gap:3px;color:#93c5fd;white-space:nowrap;">' + icoO + fmtNum(valO) + '</span>';
                  } else {
                      valorHtml = '<span style="white-space:nowrap;">' + fmtNum(parseFloat(data)) + '</span>';
                  }

                  if (!eyeBtn) return valorHtml;
                  return '<span style="display:inline-flex;align-items:center;gap:5px;justify-content:flex-end;">'
                       + valorHtml + eyeBtn + '</span>';
              }
            },

            // ── Etapas (data: "id" → usado como data-id no render) ────────
            { data: "id", name: "etapa1", orderable: false, className: "dt-center", width: "4%", render: criarRenderEtapa(1, "Importar Planilha", SVG_UPLOAD, renderEtapa1Done) }, // 11
            { data: "id", name: "etapa2", orderable: false, className: "dt-center", width: "6%", render: criarRenderEtapa(2, "Aditivo PDF",        SVG_PDF,    renderEtapa2Done) }, // 12
            { data: "id", name: "etapa3", orderable: false, className: "dt-center", width: "5%", render: criarRenderEtapa(3, "Adesão",             SVG_CALENDAR, renderEtapa3Done) }, // 13
            { data: "id", name: "etapa4", orderable: false, className: "dt-center", width: "6%", render: criarRenderEtapa(4, "PG Boleto",          SVG_RECEIPT, renderEtapa4Done) }, // 14
            { data: "id", name: "etapa5", orderable: false, className: "dt-center", width: "5%", render: criarRenderEtapa(5, "Vigência",           SVG_CALENDAR, renderEtapa5Done) }, // 15
            { data: "id", name: "etapa6", orderable: false, className: "dt-center", width: "5%", render: criarRenderEtapa(6, "Carteirinha",        SVG_CARD, renderEtapa6Done) }, // 16
            { data: "id", name: "etapa7", orderable: false, className: "dt-center", width: "6%", render: criarRenderEtapa(7, "1º Boleto",          SVG_BILL, renderEtapa7Done) }, // 17
            { data: "id", name: "etapa8", orderable: false, className: "dt-center", width: "5%", render: criarRenderEtapa(8, "Finalizado", SVG_FLAG, renderEtapa8Done) }, // 18
            { data: "id", name: "acoes", orderable: false, className: "dt-center", width: "5%", // 19
              render: function (data, type, row) {
                  if (type !== 'display') return data;
                  var svgEye = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(79,142,247,.85)" style="width:16px;height:16px;vertical-align:middle;">'
                      + '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.963-7.178Z"/>'
                      + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>';
                  var svgPen = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(148,163,184,.75)" style="width:14px;height:14px;vertical-align:middle;">'
                      + '<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>';
                  var btnDetalhe = '<button class="btn-detalhe-contrato" data-id="' + data + '" title="Ver detalhes" '
                      + 'style="background:none;border:none;padding:3px;cursor:pointer;line-height:0;opacity:.7;transition:opacity .15s;" '
                      + 'onmouseenter="this.style.opacity=1" onmouseleave="this.style.opacity=.7">'
                      + svgEye + '</button>';
                  var btnEditar = '<button class="btn-editar-contrato" data-id="' + data + '" title="Editar contrato" '
                      + 'style="background:none;border:none;padding:3px;cursor:pointer;line-height:0;opacity:.7;transition:opacity .15s;margin-left:2px;" '
                      + 'onmouseenter="this.style.opacity=1" onmouseleave="this.style.opacity=.7">'
                      + svgPen + '</button>';
                  return '<span style="display:inline-flex;align-items:center;gap:1px;">' + btnDetalhe + btnEditar + '</span>';
              }
            },
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'vivaz-empresarial',
                text: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>Exportar Tabela',
                className: 'btn-exportar',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] },
                filename: 'vivaz-empresarial'
            }
        ],
        initComplete: function (settings, json) {
            $('#dt-export-btn-wrap').append($('.dt-buttons'));

            // Botão "Como funciona?" no lado esquerdo da linha do Pesquisar
            $('#title_empresarial').html(
                '<button type="button" id="btnGuiaEtapas" class="fin-help-btn">'
                + '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>'
                + 'Como funciona?'
                + '</button>'
            );

            // Filtra por Vendedor (coluna 8)
            var corretores = this.api().column(8).data().unique();
            var $selVendedor = $('#mudar_user_empresarial');
            $selVendedor.empty().append('<option value="">-- Todos os Corretores --</option>');
            corretores.each(function (d) {
                $selVendedor.append('<option value="' + d + '">' + d + '</option>');
            });

            // Botões de Plano em colunas de 2
            var api = this.api();
            var planosUnicos = api.column(1).data().unique().toArray().filter(Boolean).sort();
            var $planoContainer = $('#plano-filter-btns');
            $planoContainer.empty();

            // Monta todos os botões primeiro
            var btnEls = [];
            var totalTodos = api.data().count();
            btnEls.push(
                $('<button class="plano-tag-btn plano-tag-todos plano-tag-ativo" data-plano="">'
                + '<span class="plano-tag-dot" style="background:rgba(255,255,255,.4);"></span>'
                + 'Todos<span class="plano-tag-count">' + totalTodos + '</span>'
                + '</button>')
            );

            planosUnicos.forEach(function (plano, i) {
                var c = PLANO_COLORS[i % PLANO_COLORS.length];
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

            // Planos inline (flat)
            btnEls.forEach(function (btn) { $planoContainer.append(btn); });

            atualizarContadoresEtapa();
        },
        drawCallback: function () {
            atualizarContadoresEtapa();
        },
        createdRow: function (row, data) {
            if (data.tem_diferenca_valor == 1) {
                $(row).addClass('row-diferenca-valor');
            }
        },
        footerCallback: function (row, data, start, end, display) {
            var toNum = function (i) {
                if (typeof i === 'number') return i;
                if (typeof i === 'string') return parseFloat(i) || 0;
                return 0;
            };
            var api          = this.api();
            var total        = api.column(10, { search: 'applied' }).data().reduce(function (a, b) { return toNum(a) + toNum(b); }, 0);
            var total_vidas  = api.column(9,  { search: 'applied' }).data().reduce(function (a, b) { return toNum(a) + toNum(b); }, 0);
            var total_linhas = api.column(9,  { search: 'applied' }).data().count();
            var total_br     = total.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });

            $(".total_por_page_empresarial").html(total_br);
            $(".total_por_vida_empresarial").html(total_vidas);
            $(".total_por_orcamento_empresarial").html(total_linhas);
        }
    });
}

// ── Inicializa e handlers de filtro ──────────────────────────────────────────
$('#tabela_empresarial').on('click', 'tbody tr', function () {
    tableempresarial.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});

inicializarEmpresarial();

$("#mudar_user_empresarial").on('change', function () {
    tableempresarial.column(8).search($(this).val()).draw();
});

// ── Filtro por Plano (botões coloridos) ─────────────────────────────────────
$(document).on('click', '.plano-tag-btn', function () {
    var $btn  = $(this);
    var plano = $btn.data('plano');

    // Reset todos para estilo inativo
    $('.plano-tag-btn').each(function () {
        var $b = $(this);
        $b.removeClass('plano-tag-ativo');
        if ($b.data('bg')) {
            $b.css({ background: $b.data('bg'), borderColor: $b.data('border'), color: $b.data('text') });
        } else {
            $b.css({ background: 'rgba(255,255,255,.04)', borderColor: 'rgba(255,255,255,.18)', color: 'rgba(255,255,255,.65)' });
        }
    });

    // Ativa o clicado
    $btn.addClass('plano-tag-ativo');
    if ($btn.data('active')) {
        $btn.css({ background: $btn.data('active'), borderColor: $btn.data('active'), color: '#fff' });
    } else {
        $btn.css({ background: 'rgba(255,255,255,.14)', borderColor: 'rgba(255,255,255,.35)', color: '#fff' });
    }

    filtroPlanoAtual = plano || '';
    tableempresarial.column(1).search(
        plano ? ('^' + $.fn.dataTable.util.escapeRegex(plano) + '$') : '',
        plano ? true : false,
        false
    ).draw();
});

// ── Etapas: clique para avançar ──────────────────────────────────────────────
$(document).on('click', '.etapa-avail', function () {
    var $el   = $(this);
    var id    = $el.data('id');
    var step  = parseInt($el.data('step'));
    var label = $el.data('label');

    // Etapa 1 → abre modal de upload de planilha
    if (step === 1) {
        if (typeof window.abrirModalPlanilha === 'function') {
            window.abrirModalPlanilha(id);
        }
        return;
    }

    // Etapa 2 → abre modal de upload do aditivo PDF
    if (step === 2) {
        if (typeof window.abrirModalAditivo === 'function') {
            window.abrirModalAditivo(id);
        }
        return;
    }

    // Etapa 3 → abre modal de adesão (data + boleto PDF + valor + justificativa)
    if (step === 3) {
        var rowData = tableempresarial.row($el.closest('tr')).data();
        var valorPlanilha = rowData ? (parseFloat(rowData.valor_plano) || 0) : 0;
        if (typeof window.abrirModalAdesao === 'function') {
            window.abrirModalAdesao(id, valorPlanilha);
        }
        return;
    }

    // Etapa 4 → SweetAlert com data, forma de pagamento e oriundo
    if (step === 4) {
        window.abrirEtapa4Boleto(id);
        return;
    }

    // Etapa 7 → abre modal com os 4 documentos PDF
    if (step === 7) {
        var rowData7 = tableempresarial.row($el.closest('tr')).data();
        if (typeof window.abrirModalPrimeiroBoleto === 'function') {
            window.abrirModalPrimeiroBoleto(id, rowData7);
        }
        return;
    }

    // Etapa 6 → abre modal de upload de carteirinhas PDF
    if (step === 6) {
        if (typeof window.abrirModalCarteirinha === 'function') {
            window.abrirModalCarteirinha(id);
        }
        return;
    }

    // Etapa 5 → abre modal de vigência (colar texto)
    if (step === 5) {
        if (typeof window.abrirModalVigencia === 'function') {
            var rowData5 = tableempresarial.row($el.closest('tr')).data();
            var tipo5 = rowData5 ? (rowData5.tipo_contrato || null) : null;
            window.abrirModalVigencia(id, tipo5);
        }
        return;
    }

    // Etapa 8 → abre modal com data + PDF
    if (step === 8) {
        if (typeof window.abrirModalFinalizado === 'function') {
            window.abrirModalFinalizado(id);
        }
        return;
    }
});

// ── Etapa 1: re-importar planilha (canetinha na célula done) ─────────────────
$(document).on('click', '.etapa1-editar', function (e) {
    e.stopPropagation();
    var id = $(this).data('id');
    if (typeof window.abrirModalPlanilha === 'function') {
        window.abrirModalPlanilha(id, true);
    }
});

// ── Etapa 2: re-enviar aditivo PDF (canetinha na célula done) ────────────────
$(document).on('click', '.etapa2-editar', function (e) {
    e.stopPropagation();
    var id = $(this).data('id');
    if (typeof window.abrirModalAditivo === 'function') {
        window.abrirModalAditivo(id, true);
    }
});

// ── Etapa 3: re-enviar boleto de adesão (canetinha na célula done) ───────────
$(document).on('click', '.etapa3-editar', function (e) {
    e.stopPropagation();
    var $el = $(this);
    var id  = $el.data('id');
    var rowData = tableempresarial.row($el.closest('tr')).data();
    var valorPlanilha = rowData ? (parseFloat(rowData.valor_plano) || 0) : 0;
    if (typeof window.abrirModalAdesao === 'function') {
        window.abrirModalAdesao(id, valorPlanilha, true);
    }
});

// ── Etapa 4: editar PG Boleto (canetinha na célula done) ─────────────────────
$(document).on('click', '.etapa4-editar', function (e) {
    e.stopPropagation();
    var $el = $(this);
    var id  = $el.data('id');
    var rowData = tableempresarial.row($el.closest('tr')).data();
    window.abrirEtapa4Boleto(id, true, rowData);
});

// ── Etapa 5: editar Vigência (canetinha na célula done) ───────────────────────
$(document).on('click', '.etapa5-editar', function (e) {
    e.stopPropagation();
    var $el = $(this);
    var id  = $el.data('id');
    var rowData = tableempresarial.row($el.closest('tr')).data();
    var tipo = rowData ? (rowData.tipo_contrato || null) : null;
    if (typeof window.abrirModalVigencia === 'function') {
        window.abrirModalVigencia(id, tipo, true);
    }
});

// ── Etapa 6: adicionar carteirinhas (canetinha na célula done) ───────────────
$(document).on('click', '.etapa6-editar', function (e) {
    e.stopPropagation();
    var $el = $(this);
    var id = $el.data('id');
    var rowData = (typeof tableempresarial !== 'undefined' && tableempresarial)
        ? tableempresarial.row($el.closest('tr')).data() : null;
    if (typeof window.abrirModalCarteirinha === 'function') {
        window.abrirModalCarteirinha(id, true, rowData);
    }
});

// ── Etapa 7: substituir documentos do 1º boleto (canetinha na célula done) ───
$(document).on('click', '.etapa7-editar', function (e) {
    e.stopPropagation();
    var $el = $(this);
    var id = $el.data('id');
    var rowData = (typeof tableempresarial !== 'undefined' && tableempresarial)
        ? tableempresarial.row($el.closest('tr')).data() : null;
    if (typeof window.abrirModalPrimeiroBoleto === 'function') {
        window.abrirModalPrimeiroBoleto(id, rowData, true);
    }
});

// ── Etapa 8: atualizar finalizado (canetinha na célula done) ──────────────────
$(document).on('click', '.etapa8-editar', function (e) {
    e.stopPropagation();
    var id = $(this).data('id');
    if (typeof window.abrirModalFinalizado === 'function') {
        window.abrirModalFinalizado(id, true);
    }
});

// ── Modal detalhe (mantido para compatibilidade) ─────────────────────────────
$(document).on('click', '.open-modal-empresarial', function (e) {
    e.preventDefault();
    var params = {
        data_analise:    $(this).data('analise'),
        vendedor:        $(this).data('vendedor'),
        plano:           $(this).data('plano'),
        origens:         $(this).data('origens'),
        razao_social:    $(this).data('razao_social'),
        cnpj:            $(this).data('cnpj'),
        vidas:           $(this).data('vidas'),
        celular:         $(this).data('celular'),
        email:           $(this).data('email'),
        valor_plano:     $(this).data('valor_plano'),
        responsavel:     $(this).data('responsavel'),
        cidade:          $(this).data('cidade'),
        uf:              $(this).data('uf'),
        id:              $(this).data('id'),
        plano_contratado:$(this).data('plano_contratado'),
        codigo_corretora:$(this).data('codigo_corretora'),
        codigo_saude:    $(this).data('codigo_saude'),
        codigo_odonto:   $(this).data('codigo_odonto'),
        senha_cliente:   $(this).data('senha_cliente'),
        valor_saude:     $(this).data('valor_saude'),
        valor_odonto:    $(this).data('valor_odonto'),
        valor_total:     $(this).data('valor_total'),
        taxa_adesao:     $(this).data('taxa_adesao'),
        valor_boleto:    $(this).data('valor_boleto'),
        vencimento_boleto:$(this).data('vencimento_boleto'),
        data_boleto:     $(this).data('boleto'),
        codigo_externo:  $(this).data('codigo_externo'),
        data_cadastro:   $(this).data('data_cadastro')
    };
    $.ajax({
        url: empresarialFinanceiroInicializar, method: 'POST', data: params,
        success: function (res) {
            $('#modalLoaderEmpresa').addClass('hidden');
            $('.content-modal-empresarial').removeClass('hidden').html(res);
        }
    });
    $('#myModalEmpresarial').removeClass('hidden').addClass('flex');
});

$("body").on('click', '#closeModalEmpresarial', function () {
    $('#myModalEmpresarial').removeClass('flex').addClass('hidden');
    $('.content-modal-empresarial').html('');
});

// ── Edição inline na modal de detalhe ────────────────────────────────────────
$("body").on('click', '.editar_empresarial_select', function () {
    var input = $(this).closest("div").find("select");
    input.prop('disabled', !input.prop('disabled'));
});

$("body").on('click', '.editar_empresarial', function () {
    var input = $(this).closest("div").find("input");
    if (input.prop('readonly')) {
        input.prop('readonly', false);
        input.focus();
    } else {
        input.prop('readonly', true);
    }
});

// ── Autosave: TAB / click fora salva o campo no banco ────────────────────────
function salvarCampoEmpresarial(id, campo, valor, $el) {
    $.ajax({
        url: urlAtualizarCampoEmpresarial, method: 'POST',
        data: { id: id, campo: campo, valor: valor },
        success: function () {
            $el.css({ outline: '2px solid #34d399' });
            setTimeout(function () { $el.css({ outline: '' }); }, 1500);
        },
        error: function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Erro ao salvar.';
            $el.css({ outline: '2px solid #f87171' });
            setTimeout(function () { $el.css({ outline: '' }); }, 2000);
            console.warn('Erro ao salvar campo "' + campo + '":', msg);
        }
    });
}

$('body').on('blur', '.mudar_empresarial, .mudar_empresarial_valor', function () {
    if ($(this).prop('readonly')) return;
    salvarCampoEmpresarial($('#empresarial_cliente_id').val(), $(this).attr('id'), $(this).val(), $(this));
});

$('body').on('change', '#mudar_corretor_empresarial, #mudar_plano_empresarial, #mudar_tabela_origem_empresarial, #plano_contrado', function () {
    if ($(this).prop('disabled')) return;
    salvarCampoEmpresarial($('#empresarial_cliente_id').val(), $(this).attr('id'), $(this).val(), $(this));
});

// ── Filtro por tipo (Saúde / Odonto / Ambos) ────────────────────────────────
var filtroTipoAtual  = null;
var filtroPlanoAtual = '';

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'tabela_empresarial') return true;
    if (filtroTipoAtual === null) return true;
    var rowData = tableempresarial.row(dataIndex).data();
    if (!rowData) return true;
    return (rowData.tipo_contrato || null) === filtroTipoAtual;
});

// ── Filtro por etapa (tags de pipeline) ──────────────────────────────────────
var filtroEtapaAtual = null;

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'tabela_empresarial') return true;
    if (filtroEtapaAtual === null) return true;
    var rowData = tableempresarial.row(dataIndex).data();
    if (!rowData) return true;
    var etapa = parseInt(rowData.etapa_atual) || 0;
    if (filtroEtapaAtual === 'andamento') return etapa < 8;
    return etapa === filtroEtapaAtual;
});

// Retorna true se row passa por todos os filtros ativos, exceto o grupo indicado
function rowMatchesFiltros(row, excluir) {
    if (excluir !== 'tipo' && filtroTipoAtual !== null) {
        if ((row.tipo_contrato || null) !== filtroTipoAtual) return false;
    }
    if (excluir !== 'etapa' && filtroEtapaAtual !== null) {
        var etapa = parseInt(row.etapa_atual) || 0;
        if (filtroEtapaAtual === 'andamento') {
            if (etapa >= 8) return false;
        } else {
            if (etapa !== filtroEtapaAtual) return false;
        }
    }
    if (excluir !== 'plano' && filtroPlanoAtual !== '') {
        if ((row.plano || '') !== filtroPlanoAtual) return false;
    }
    return true;
}

function atualizarContadoresEtapa() {
    if (!tableempresarial) return;
    var todos = tableempresarial.data().toArray();

    // ── Etapas: conta excluindo o filtro de etapa ──
    var counts = {}, totalEtapa = 0, andamento = 0;
    todos.forEach(function (row) {
        if (!rowMatchesFiltros(row, 'etapa')) return;
        var e = parseInt(row.etapa_atual) || 0;
        counts[e] = (counts[e] || 0) + 1;
        totalEtapa++;
        if (e < 8) andamento++;
    });
    $('#count-todos').text(totalEtapa);
    $('#count-andamento').text(andamento);
    for (var i = 0; i <= 8; i++) {
        $('#count-etapa-' + i).text(counts[i] || 0);
    }

    // ── Tipo: conta excluindo o filtro de tipo ──
    var tipos = { saude: 0, odonto: 0, ambos: 0 }, totalTipo = 0;
    todos.forEach(function (row) {
        if (!rowMatchesFiltros(row, 'tipo')) return;
        totalTipo++;
        var t = row.tipo_contrato || '';
        if (tipos[t] !== undefined) tipos[t]++;
    });
    $('#count-tipo-todos').text(totalTipo);
    $('#count-tipo-saude').text(tipos.saude);
    $('#count-tipo-odonto').text(tipos.odonto);
    $('#count-tipo-ambos').text(tipos.ambos);

    // ── Planos: conta excluindo o filtro de plano ──
    var planosCount = {}, totalPlano = 0;
    todos.forEach(function (row) {
        if (!rowMatchesFiltros(row, 'plano')) return;
        totalPlano++;
        var p = row.plano || '';
        if (p) planosCount[p] = (planosCount[p] || 0) + 1;
    });
    $('.plano-tag-btn').each(function () {
        var plano = $(this).data('plano') || '';
        $(this).find('.plano-tag-count').text(
            plano === '' ? totalPlano : (planosCount[plano] || 0)
        );
    });
}

// ── Transição entre etapas ───────────────────────────────────────────────────
function buscarRowData(contratoId) {
    var found = null;
    if (tableempresarial) {
        tableempresarial.data().each(function (row) {
            if (String(row.id) === String(contratoId)) { found = row; return false; }
        });
    }
    return found;
}

function abrirProximaEtapa(etapaConc, contratoId) {
    switch (etapaConc) {
        case 1:
            if (window.abrirModalAditivo) window.abrirModalAditivo(contratoId);
            break;
        case 2:
            var row2 = buscarRowData(contratoId);
            var vp = row2 ? (parseFloat(row2.valor_plano) || 0) : 0;
            if (window.abrirModalAdesao) window.abrirModalAdesao(contratoId, vp);
            break;
        case 3:
            if (window.abrirEtapa4Boleto) window.abrirEtapa4Boleto(contratoId);
            break;
        case 4:
            var row4 = buscarRowData(contratoId);
            var tipo4 = row4 ? (row4.tipo_contrato || null) : null;
            if (window.abrirModalVigencia) window.abrirModalVigencia(contratoId, tipo4);
            break;
        case 5:
            if (window.abrirModalCarteirinha) window.abrirModalCarteirinha(contratoId);
            break;
        case 6:
            var row6 = buscarRowData(contratoId);
            if (window.abrirModalPrimeiroBoleto) window.abrirModalPrimeiroBoleto(contratoId, row6);
            break;
        case 7:
            if (window.abrirModalFinalizado) window.abrirModalFinalizado(contratoId);
            break;
    }
}

var _etOverlayTimer = null;

function fecharEtOverlay() {
    clearTimeout(_etOverlayTimer);
    $('#et-overlay').removeClass('et-visivel');
}

$(document).on('click', '#et-fechar', fecharEtOverlay);

window.transicaoEtapa = function (etapaConc, contratoId, fecharFn) {
    if (fecharFn) fecharFn();

    var nomeConc = ETAPA_NOMES[etapaConc] || ('Etapa ' + etapaConc);
    var nomeProx = etapaConc < 8 ? ETAPA_NOMES[etapaConc + 1] : null;

    $('#et-nome-conc').text(nomeConc);
    $('#et-proxima').html(
        nomeProx
            ? ('Próxima etapa: <strong>' + nomeProx + '</strong>')
            : '<strong style="color:#34d399;">Contrato finalizado! 🎉</strong>'
    );
    $('#et-overlay').addClass('et-visivel');

    // Recarrega a tabela silenciosamente em background
    if (typeof tableempresarial !== 'undefined' && tableempresarial) {
        tableempresarial.ajax.reload(null, false);
    }

    // Fecha automaticamente após 3 segundos
    _etOverlayTimer = setTimeout(fecharEtOverlay, 3000);
};

// ── Etapa 4: SweetAlert de vencimento do boleto ──────────────────────────────
window.abrirEtapa4Boleto = function (contratoId, modoEdicao, rowData) {
    var inputStyle = 'width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.15);'
                   + 'border-radius:8px;padding:8px 12px;font-size:.84rem;outline:none;box-sizing:border-box;';
    var labelStyle = 'display:block;text-align:left;font-size:.72rem;font-weight:600;'
                   + 'color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.06em;'
                   + 'margin-bottom:5px;margin-top:14px;';

    var formaOpts   = ['Boleto', 'PIX', 'Débito Automático', 'Cartão de Crédito'];
    var oriundoOpts = ['Accert', 'Vivaz'];

    var formaHtml = '<option value="">Selecione...</option>';
    formaOpts.forEach(function (o) { formaHtml += '<option value="' + o + '">' + o + '</option>'; });

    var oriundoHtml = '<option value="">Selecione...</option>';
    oriundoOpts.forEach(function (o) { oriundoHtml += '<option value="' + o + '">' + o + '</option>'; });

    var html = '<div style="text-align:left;">'
        + '<label style="' + labelStyle + '">Data do Vencimento</label>'
        + '<input type="date" id="swal-boleto-data" style="' + inputStyle + 'color-scheme:dark;" />'
        + '<label style="' + labelStyle + '">Forma de Pagamento</label>'
        + '<select id="swal-boleto-forma" style="' + inputStyle + '">' + formaHtml + '</select>'
        + '<label style="' + labelStyle + '">Oriundo</label>'
        + '<select id="swal-boleto-oriundo" style="' + inputStyle + '">' + oriundoHtml + '</select>'
        + '</div>';

    var titulo = modoEdicao
        ? '<span style="font-size:.95rem;font-weight:700;color:#e2e8f0;">Editar PG Boleto</span>'
        : '<span style="font-size:.95rem;font-weight:700;color:#e2e8f0;">Vencimento do Boleto</span>';

    Swal.fire({
        title: titulo,
        html: html,
        background: '#0f1e38',
        color: '#e2e8f0',
        confirmButtonColor: '#4f8ef7',
        confirmButtonText: 'Confirmar',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        didOpen: function () {
            if (modoEdicao && rowData) {
                // data_pgto vem como dd/mm/yyyy → converter para yyyy-mm-dd
                var parts = (rowData.data_pgto || '').split('/');
                if (parts.length === 3) {
                    document.getElementById('swal-boleto-data').value = parts[2] + '-' + parts[1] + '-' + parts[0];
                }
                if (rowData.forma_pagamento) {
                    document.getElementById('swal-boleto-forma').value = rowData.forma_pagamento;
                }
                if (rowData.oriundo) {
                    document.getElementById('swal-boleto-oriundo').value = rowData.oriundo;
                }
            }
        },
        preConfirm: function () {
            var data    = document.getElementById('swal-boleto-data').value;
            var forma   = document.getElementById('swal-boleto-forma').value;
            var oriundo = document.getElementById('swal-boleto-oriundo').value;
            if (!data)    { Swal.showValidationMessage('Informe a data do vencimento.'); return false; }
            if (!forma)   { Swal.showValidationMessage('Selecione a forma de pagamento.'); return false; }
            if (!oriundo) { Swal.showValidationMessage('Selecione o oriundo.'); return false; }
            return { data: data, forma: forma, oriundo: oriundo };
        }
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: urlSalvarBoleto,
            method: 'POST',
            data: {
                id:              contratoId,
                data_pgto:       result.value.data,
                forma_pagamento: result.value.forma,
                oriundo:         result.value.oriundo,
            },
            success: function () {
                if (modoEdicao) {
                    tableempresarial && tableempresarial.ajax.reload(null, false);
                } else if (window.transicaoEtapa) {
                    window.transicaoEtapa(4, contratoId, null);
                } else {
                    tableempresarial && tableempresarial.ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.error)
                    ? xhr.responseJSON.error : 'Erro ao salvar. Tente novamente.';
                Swal.fire({ icon: 'error', title: 'Erro', text: msg, background: '#0f1e38', color: '#e2e8f0' });
            }
        });
    });
};

// ── Filtro por tipo (botões Saúde / Odonto / Ambos) ─────────────────────────
$(document).on('click', '.tipo-tag-btn', function () {
    var $btn = $(this);
    var tipo = $btn.data('tipo') || null;

    $('.tipo-tag-btn').each(function () {
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

    filtroTipoAtual = tipo;
    if (tableempresarial) tableempresarial.draw();
});

// ── Beneficiários por contrato (olhinho na coluna Valor) ─────────────────────
$(document).on('click', '.btn-beneficiarios', function (e) {
    e.stopPropagation();
    var id  = $(this).data('id');
    var url = (typeof urlBeneficiarios !== 'undefined')
        ? urlBeneficiarios.replace('__ID__', id)
        : '/financeiro/beneficiarios/' + id;

    $.ajax({
        url: url,
        method: 'GET',
        success: function (res) {
            if (!res.data || res.data.length === 0) {
                Swal.fire({ icon: 'info', title: 'Sem beneficiários', text: 'Nenhum beneficiário encontrado.', background: '#1a2540', color: '#e2e8f0' });
                return;
            }

            var fmt = function (v) {
                return 'R$ ' + parseFloat(v || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            };

            var thS = 'padding:5px 8px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:rgba(255,255,255,.4);text-align:left;white-space:nowrap;border-bottom:1px solid rgba(255,255,255,.1);';
            var thR = thS + 'text-align:right;';
            var tdS = 'padding:5px 8px;font-size:.78rem;color:#e2e8f0;border-top:1px solid rgba(255,255,255,.05);';
            var tdR = tdS + 'text-align:right;';

            var totalSaude  = 0;
            var totalOdonto = 0;
            res.data.forEach(function (b) {
                totalSaude  += parseFloat(b.valor_saude  || 0);
                totalOdonto += parseFloat(b.valor_odonto || 0);
            });
            var totalGeral = totalSaude + totalOdonto;

            var html = '<div style="max-height:360px;overflow-y:auto;">'
                + '<table style="width:100%;border-collapse:collapse;">'
                + '<thead><tr>'
                + '<th style="' + thS + '">Nome</th>'
                + '<th style="' + thS + '">Nasc.</th>'
                + '<th style="' + thS + '">Idade</th>'
                + '<th style="' + thS + '">Acomod.</th>'
                + '<th style="' + thR + '">Saúde</th>'
                + '<th style="' + thR + '">Odonto</th>'
                + '</tr></thead><tbody>';

            res.data.forEach(function (b) {
                html += '<tr>'
                    + '<td style="' + tdS + '">' + (b.nome_completo || '-') + '</td>'
                    + '<td style="' + tdS + ';white-space:nowrap;">' + (b.data_nascimento || '-') + '</td>'
                    + '<td style="' + tdS + ';text-align:center;">' + (b.idade || '-') + '</td>'
                    + '<td style="' + tdS + '">' + (b.acomodacao || '-') + '</td>'
                    + '<td style="' + tdR + '">' + fmt(b.valor_saude) + '</td>'
                    + '<td style="' + tdR + '">' + fmt(b.valor_odonto) + '</td>'
                    + '</tr>';
            });

            html += '</tbody></table></div>';

            html += '<div style="margin-top:12px;padding:10px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;display:flex;flex-direction:column;gap:6px;">'
                + '<div style="display:flex;justify-content:space-between;align-items:center;">'
                + '<span style="color:rgba(255,255,255,.55);font-size:.8rem;">Total Saúde</span>'
                + '<span style="color:#34d399;font-weight:700;">' + fmt(totalSaude) + '</span>'
                + '</div>'
                + '<div style="display:flex;justify-content:space-between;align-items:center;">'
                + '<span style="color:rgba(255,255,255,.55);font-size:.8rem;">Total Odonto</span>'
                + '<span style="color:#93c5fd;font-weight:700;">' + fmt(totalOdonto) + '</span>'
                + '</div>'
                + '<div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(255,255,255,.1);padding-top:7px;margin-top:2px;">'
                + '<span style="color:#fff;font-weight:700;font-size:.85rem;">Total Geral</span>'
                + '<span style="color:#f0fdf4;font-weight:800;font-size:.9rem;">' + fmt(totalGeral) + '</span>'
                + '</div>'
                + '</div>';

            Swal.fire({
                title: '<span style="font-size:1rem;font-weight:700;">Beneficiários</span>',
                html: html,
                background: '#0f1e38',
                color: '#e2e8f0',
                showConfirmButton: false,
                showCloseButton: true,
                width: 720,
            });
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível carregar os beneficiários.', background: '#1a2540', color: '#e2e8f0' });
        }
    });
});

$(document).on('click', '.fin-tag', function () {
    var $btn     = $(this);
    var etapaStr = $btn.data('etapa');

    $('.fin-tag').removeClass('fin-tag-ativo');
    $btn.addClass('fin-tag-ativo');

    if (etapaStr === '' || etapaStr === undefined || etapaStr === null) {
        filtroEtapaAtual = null;
    } else if (etapaStr === 'andamento') {
        filtroEtapaAtual = 'andamento';
    } else {
        filtroEtapaAtual = parseInt(etapaStr);
    }

    if (tableempresarial) {
        tableempresarial.draw();
    }
});
