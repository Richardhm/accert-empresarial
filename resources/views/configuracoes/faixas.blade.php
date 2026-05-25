<x-app-layout>

@section('css')
<link rel="stylesheet" href="{{ asset('css/estilo-financeiro.css') }}">
<style>
/* ── Configurações — Faixas Etárias ── */
.cfg-page  { background:#0f1623; min-height:100vh; padding:28px 20px; }
.cfg-inner { max-width:1100px; margin:0 auto; }

.cfg-header { margin-bottom:24px; }
.cfg-title  { font-size:1.35rem; font-weight:800; color:#fff; margin:0; }
.cfg-sub    { font-size:.78rem; color:rgba(255,255,255,.4); margin:4px 0 0; }

/* ── Filtro ── */
.cfg-filtro {
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.08);
    border-radius:14px;
    padding:20px 24px;
    display:flex;
    flex-wrap:wrap;
    gap:14px;
    align-items:flex-end;
    margin-bottom:24px;
}
.cfg-filtro-group { display:flex; flex-direction:column; gap:5px; min-width:160px; flex:1; }
.cfg-filtro-group label { font-size:.74rem; font-weight:600; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.04em; }
.cfg-filtro-select {
    background:#1a2540;
    color:#e2e8f0;
    border:1px solid rgba(255,255,255,.12);
    border-radius:9px;
    padding:8px 12px;
    font-size:.83rem;
    outline:none;
    width:100%;
    transition:border-color .2s;
}
.cfg-filtro-select:focus { border-color:rgba(79,142,247,.5); }
.cfg-filtro-select option { background:#1a2540; color:#e2e8f0; }

.cfg-btn-carregar {
    display:inline-flex; align-items:center; gap:7px;
    background:#4f8ef7; color:#fff;
    border:none; border-radius:9px;
    padding:9px 20px; font-size:.83rem; font-weight:700;
    cursor:pointer; transition:background .2s;
    white-space:nowrap; align-self:flex-end;
}
.cfg-btn-carregar:hover { background:#3a7de0; }
.cfg-btn-carregar:disabled { opacity:.55; cursor:default; }

/* ── Tabela de faixas ── */
.cfg-table-wrap {
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.08);
    border-radius:14px;
    overflow:hidden;
}
.cfg-table-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 20px;
    border-bottom:1px solid rgba(255,255,255,.08);
}
.cfg-table-titulo { font-size:.9rem; font-weight:700; color:#fff; }
.cfg-table-sub    { font-size:.75rem; color:rgba(255,255,255,.4); margin-top:2px; }

.cfg-btn-salvar {
    display:inline-flex; align-items:center; gap:7px;
    background:#34d399; color:#0a1628;
    border:none; border-radius:9px;
    padding:9px 22px; font-size:.83rem; font-weight:700;
    cursor:pointer; transition:background .2s, opacity .2s;
}
.cfg-btn-salvar:hover { background:#10b981; }
.cfg-btn-salvar:disabled { opacity:.55; cursor:default; }

/* ── Grid de valores ── */
.cfg-grid { width:100%; border-collapse:collapse; }

.cfg-grid thead tr.cfg-th-grupo th {
    padding:10px 8px 4px;
    font-size:.7rem;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.05em;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,.06);
}
.cfg-grid thead tr.cfg-th-sub th {
    padding:4px 8px 10px;
    font-size:.68rem;
    font-weight:600;
    color:rgba(255,255,255,.45);
    text-align:center;
    border-bottom:2px solid rgba(255,255,255,.1);
}

.th-faixa   { width:9%; color:rgba(255,255,255,.5); text-align:left !important; padding-left:20px !important; }
.th-copart  { color:#93c5fd; background:rgba(79,142,247,.07); }
.th-sem     { color:#6ee7b7; background:rgba(52,211,153,.07); }
.th-apart   { background:rgba(79,142,247,.04); color:#93c5fd; }
.th-enfer   { background:rgba(79,142,247,.04); color:#93c5fd; }
.th-sapart  { background:rgba(52,211,153,.04); color:#6ee7b7; }
.th-senfer  { background:rgba(52,211,153,.04); color:#6ee7b7; }

.cfg-grid tbody tr { border-bottom:1px solid rgba(255,255,255,.05); transition:background .15s; }
.cfg-grid tbody tr:hover { background:rgba(255,255,255,.03); }
.cfg-grid tbody tr:last-child { border-bottom:none; }

.cfg-grid tbody td { padding:8px; text-align:center; }
.cfg-grid tbody td.td-faixa {
    text-align:left; padding-left:20px;
    font-size:.78rem; font-weight:600; color:rgba(255,255,255,.65);
}

.cfg-faixa-input {
    width:100%; max-width:110px;
    background:#1a2540;
    color:#e2e8f0;
    border:1px solid rgba(255,255,255,.1);
    border-radius:7px;
    padding:6px 10px;
    font-size:.82rem;
    text-align:right;
    outline:none;
    transition:border-color .2s;
    box-sizing:border-box;
}
.cfg-faixa-input:focus { border-color:rgba(79,142,247,.5); }
.cfg-faixa-input.input-copart { border-color:rgba(79,142,247,.15); }
.cfg-faixa-input.input-sem    { border-color:rgba(52,211,153,.15); }
.cfg-faixa-input.input-copart:focus { border-color:rgba(79,142,247,.55); }
.cfg-faixa-input.input-sem:focus    { border-color:rgba(52,211,153,.55); }

.cfg-empty {
    text-align:center; padding:40px 20px;
    color:rgba(255,255,255,.3); font-size:.85rem;
}
.cfg-msg { padding:10px 20px; font-size:.82rem; display:none; }
.cfg-msg.erro   { color:#fca5a5; }
.cfg-msg.ok     { color:#6ee7b7; }
.swal-progress-green { background: #34d399 !important; }
</style>
@endsection

<div class="cfg-page">
    <div class="cfg-inner">

        <div class="cfg-header">
            <h1 class="cfg-title">Configuração de Faixas Etárias</h1>
            <p class="cfg-sub">Defina os valores por plano e cidade para cada faixa etária</p>
        </div>

        {{-- ── Filtro ── --}}
        <div class="cfg-filtro">
            <div class="cfg-filtro-group">
                <label>Plano</label>
                <select id="cfg_plano" class="cfg-filtro-select">
                    <option value="">Selecione o plano...</option>
                    @foreach($planos as $p)
                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="cfg-filtro-group" style="max-width:100px;">
                <label>UF</label>
                <select id="cfg_uf" class="cfg-filtro-select">
                    <option value="">UF...</option>
                </select>
            </div>
            <div class="cfg-filtro-group">
                <label>Cidade</label>
                <select id="cfg_cidade" class="cfg-filtro-select" disabled>
                    <option value="">Selecione a UF primeiro...</option>
                </select>
            </div>
            <button id="btnCarregarFaixas" class="cfg-btn-carregar" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Carregar
            </button>
        </div>

        {{-- ── Tabela ── --}}
        <div class="cfg-table-wrap">
            <div class="cfg-table-header">
                <div>
                    <div class="cfg-table-titulo" id="cfg-table-titulo">Selecione plano e cidade para editar</div>
                    <div class="cfg-table-sub"   id="cfg-table-sub">10 faixas etárias · 4 valores cada</div>
                </div>
                <button id="btnSalvarFaixas" class="cfg-btn-salvar" style="display:none;" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                    Salvar Tabela
                </button>
            </div>

            <div id="cfgMsgErro" class="cfg-msg erro"></div>
            <div id="cfgMsgOk"   class="cfg-msg ok"></div>

            <div id="cfgGridWrap">
                <div class="cfg-empty">Selecione um plano, UF e cidade acima — os valores carregam automaticamente.</div>
            </div>
        </div>

    </div>
</div>

<script>
$(function () {
    var urlCarregar = "{{ route('configuracoes.faixas.carregar') }}";
    var urlSalvar   = "{{ route('configuracoes.faixas.salvar') }}";

    var faixasLabels = {
        0: '0 a 18', 1: '19 a 23', 2: '24 a 28', 3: '29 a 33', 4: '34 a 38',
        5: '39 a 43', 6: '44 a 48', 7: '49 a 53', 8: '54 a 58', 9: '59+'
    };

    // ── UF cascade via JSON ──
    $.getJSON("{{ asset('js/estados_cidades.json') }}", function (estadosCidades) {
        var ufOpts = '<option value="">UF...</option>';
        $.each(estadosCidades, function (i, estado) {
            ufOpts += '<option value="' + estado.sigla + '">' + estado.sigla + '</option>';
        });
        $('#cfg_uf').html(ufOpts);

        $('#cfg_uf').on('change', function () {
            var uf = $(this).val();
            $('#cfg_cidade').html('<option value="">Selecione a cidade...</option>').prop('disabled', true);
            $('#btnSalvarFaixas').hide();
            $('#cfgGridWrap').html('<div class="cfg-empty">Selecione a cidade para carregar os valores.</div>');
            $('#cfg-table-titulo').text('Selecione plano e cidade para editar');
            $('#cfg-table-sub').text('10 faixas etárias · 4 valores cada');
            atualizarBtnCarregar();
            if (!uf) return;

            $.each(estadosCidades, function (i, estado) {
                if (estado.sigla === uf) {
                    var opts = '<option value="">Selecione a cidade...</option>';
                    $.each(estado.cidades, function (j, c) {
                        opts += '<option value="' + c + '">' + c + '</option>';
                    });
                    $('#cfg_cidade').html(opts).prop('disabled', false);
                    return false;
                }
            });
        });
    });

    // Auto-carregar quando plano ou cidade mudar (e os 3 estiverem preenchidos)
    $('#cfg_plano').on('change', function () {
        atualizarBtnCarregar();
        if (todosSelecionados()) carregarFaixas();
    });
    $('#cfg_cidade').on('change', function () {
        atualizarBtnCarregar();
        if (todosSelecionados()) carregarFaixas();
    });

    function todosSelecionados() {
        return $('#cfg_plano').val() && $('#cfg_uf').val() && $('#cfg_cidade').val();
    }

    function atualizarBtnCarregar() {
        $('#btnCarregarFaixas').prop('disabled', !todosSelecionados());
    }

    // Botão "Carregar" mantido como atalho manual
    $('#btnCarregarFaixas').on('click', function () {
        if (todosSelecionados()) carregarFaixas();
    });

    // ── Função principal de carregamento ──
    function carregarFaixas() {
        var planoId   = $('#cfg_plano').val();
        var uf        = $('#cfg_uf').val();
        var cidade    = $('#cfg_cidade').val();
        var planoNome = $('#cfg_plano option:selected').text();

        $('#cfgMsgErro').hide().text('');
        $('#btnCarregarFaixas').prop('disabled', true);
        $('#cfgGridWrap').html('<div class="cfg-empty" style="color:rgba(255,255,255,.5);">Carregando...</div>');
        $('#btnSalvarFaixas').hide();

        $.ajax({
            url: urlCarregar,
            method: 'GET',
            data: { plano_id: planoId, uf: uf, cidade: cidade },
            success: function (res) {
                var temDados = res.cidade_id !== null && Object.keys(res.valores || {}).length > 0;
                renderGrid(res.valores || {});
                $('#cfg-table-titulo').text(planoNome + ' — ' + cidade + ' / ' + uf);
                $('#cfg-table-sub').text('Edite os valores e clique em ' + (temDados ? 'Atualizar Tabela' : 'Salvar Tabela'));
                var btnLabel = temDados
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Atualizar Tabela'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Salvar Tabela';
                $('#btnSalvarFaixas').html(btnLabel).show().prop('disabled', false);
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao carregar.';
                $('#cfgMsgErro').text(msg).show();
                $('#cfgGridWrap').html('<div class="cfg-empty">Erro ao carregar. Tente novamente.</div>');
            },
            complete: function () {
                $('#btnCarregarFaixas').prop('disabled', false);
            }
        });
    }

    // ── Renderizar grid ──
    function renderGrid(valores) {
        var html = '<table class="cfg-grid">';
        html += '<thead>';
        html += '<tr class="cfg-th-grupo">';
        html += '<th class="th-faixa">Faixa Etária</th>';
        html += '<th colspan="2" class="th-copart">Com Coparticipação</th>';
        html += '<th colspan="2" class="th-sem">Sem Coparticipação</th>';
        html += '</tr>';
        html += '<tr class="cfg-th-sub">';
        html += '<th class="th-faixa"></th>';
        html += '<th class="th-apart">Apartamento</th>';
        html += '<th class="th-enfer">Enfermaria</th>';
        html += '<th class="th-sapart">Apartamento</th>';
        html += '<th class="th-senfer">Enfermaria</th>';
        html += '</tr>';
        html += '</thead><tbody>';

        for (var i = 0; i <= 9; i++) {
            var v = valores[i] || {};
            html += '<tr>';
            html += '<td class="td-faixa">' + faixasLabels[i] + '</td>';
            html += '<td>' + inputFaixa(i, 'com_copart_apart', v.com_copart_apart, 'input-copart') + '</td>';
            html += '<td>' + inputFaixa(i, 'com_copart_enfer', v.com_copart_enfer, 'input-copart') + '</td>';
            html += '<td>' + inputFaixa(i, 'sem_copart_apart', v.sem_copart_apart, 'input-sem') + '</td>';
            html += '<td>' + inputFaixa(i, 'sem_copart_enfer', v.sem_copart_enfer, 'input-sem') + '</td>';
            html += '</tr>';
        }
        html += '</tbody></table>';
        $('#cfgGridWrap').html(html);

        // Máscara de moeda nos inputs
        if (typeof $.fn.mask === 'function') {
            $('.cfg-faixa-input').mask('#.##0,00', { reverse: true });
        }
    }

    function inputFaixa(faixa, campo, valor, cls) {
        var val = valor ? parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
        return '<input type="text" class="cfg-faixa-input ' + cls + '" '
             + 'data-faixa="' + faixa + '" data-campo="' + campo + '" '
             + 'value="' + val + '" placeholder="0,00">';
    }

    // ── Salvar ──
    $('#btnSalvarFaixas').on('click', function () {
        var planoId = $('#cfg_plano').val();
        var uf      = $('#cfg_uf').val();
        var cidade  = $('#cfg_cidade').val();

        if (!planoId || !uf || !cidade) {
            $('#cfgMsgErro').text('Selecione plano, UF e cidade antes de salvar.').show();
            return;
        }

        // Normaliza campos vazios para 0,00 antes de enviar
        $('.cfg-faixa-input').each(function () {
            if ($(this).val().trim() === '') $(this).val('0,00');
        });

        var faixas = {};
        $('.cfg-faixa-input').each(function () {
            var fi = $(this).data('faixa');
            var campo = $(this).data('campo');
            var val = $(this).val();
            if (!faixas[fi]) faixas[fi] = {};
            faixas[fi][campo] = val;
        });

        $('#cfgMsgErro, #cfgMsgOk').hide().text('');
        $('#btnSalvarFaixas').prop('disabled', true).text('Salvando...');

        $.ajax({
            url: urlSalvar,
            method: 'POST',
            data: {
                _token:   $('meta[name="csrf-token"]').attr('content'),
                plano_id: planoId,
                uf:       uf,
                cidade:   cidade,
                faixas:   faixas,
            },
            success: function (res) {
                var cidade = $('#cfg_cidade').val();
                var uf     = $('#cfg_uf').val();
                var plano  = $('#cfg_plano option:selected').text();
                Swal.fire({
                    icon: 'success',
                    title: 'Tabela salva com sucesso!',
                    html: '<span style="font-size:.95rem;color:rgba(255,255,255,.75);">'
                        + plano + ' · ' + cidade + ' / ' + uf
                        + '</span>',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    background: '#1a2540',
                    color: '#e2e8f0',
                    iconColor: '#34d399',
                    customClass: { timerProgressBar: 'swal-progress-green' }
                });
                $('#btnSalvarFaixas').prop('disabled', false).html(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Atualizar Tabela'
                );
                $('#cfg-table-sub').text('Edite os valores e clique em Atualizar Tabela');
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao salvar.';
                Swal.fire({ icon: 'error', title: 'Erro ao salvar', text: msg, background: '#1a2540', color: '#e2e8f0', iconColor: '#f87171' });
                $('#btnSalvarFaixas').prop('disabled', false);
            },
            complete: function () {}
        });
    });
});
</script>

@section('scripts')
@endsection

</x-app-layout>
