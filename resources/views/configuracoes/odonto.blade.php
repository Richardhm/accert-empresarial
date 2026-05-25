<x-app-layout>

@section('css')
<link rel="stylesheet" href="{{ asset('css/estilo-financeiro.css') }}">
<style>
.cfg-page  { background:#0f1623; min-height:100vh; padding:28px 20px; }
.cfg-inner { max-width:700px; margin:0 auto; }

.cfg-header { margin-bottom:24px; }
.cfg-title  { font-size:1.35rem; font-weight:800; color:#fff; margin:0; }
.cfg-sub    { font-size:.78rem; color:rgba(255,255,255,.4); margin:4px 0 0; }

.cfg-back {
    display:inline-flex; align-items:center; gap:6px;
    font-size:.78rem; color:rgba(255,255,255,.45);
    text-decoration:none; margin-bottom:16px;
    transition:color .2s;
}
.cfg-back:hover { color:rgba(255,255,255,.8); }

/* ── Card ── */
.cfg-card {
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.08);
    border-radius:14px;
    padding:28px 28px 24px;
}

.cfg-filtro-group { display:flex; flex-direction:column; gap:5px; margin-bottom:16px; }
.cfg-filtro-group label {
    font-size:.74rem; font-weight:600;
    color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.04em;
}
.cfg-filtro-select {
    background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(255,255,255,.12); border-radius:9px;
    padding:9px 12px; font-size:.83rem; outline:none; width:100%;
    transition:border-color .2s;
}
.cfg-filtro-select:focus { border-color:rgba(79,142,247,.5); }
.cfg-filtro-select option { background:#1a2540; color:#e2e8f0; }

.cfg-grid-3 { display:grid; grid-template-columns:100px 1fr; gap:16px; }

/* ── Valor ── */
.cfg-valor-wrap {
    margin-top:8px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.08);
    border-radius:12px;
    padding:24px;
    display:none;
}
.cfg-valor-label {
    font-size:.74rem; font-weight:600;
    color:rgba(255,255,255,.55); text-transform:uppercase;
    letter-spacing:.04em; margin-bottom:8px;
}
.cfg-valor-input-row { display:flex; align-items:center; gap:12px; }
.cfg-valor-prefix {
    font-size:1rem; font-weight:700; color:rgba(255,255,255,.4);
    flex-shrink:0;
}
.cfg-valor-input {
    flex:1; background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(52,211,153,.3); border-radius:9px;
    padding:10px 14px; font-size:1.1rem; font-weight:600;
    text-align:right; outline:none; transition:border-color .2s, box-shadow .2s;
    max-width:200px;
}
.cfg-valor-input:focus {
    border-color:rgba(52,211,153,.7);
    box-shadow:0 0 0 3px rgba(52,211,153,.12);
}

.cfg-btn-salvar {
    display:inline-flex; align-items:center; gap:7px;
    background:#34d399; color:#0a1628;
    border:none; border-radius:9px;
    padding:10px 24px; font-size:.85rem; font-weight:700;
    cursor:pointer; transition:background .2s;
    margin-top:20px; width:100%; justify-content:center;
}
.cfg-btn-salvar:hover { background:#10b981; }
.cfg-btn-salvar:disabled { opacity:.55; cursor:default; }

.cfg-msg-erro { display:none; color:#fca5a5; font-size:.82rem; margin-top:10px; }

.cfg-badge-existe {
    display:inline-flex; align-items:center; gap:5px;
    background:rgba(52,211,153,.12); border:1px solid rgba(52,211,153,.25);
    color:#6ee7b7; font-size:.72rem; font-weight:600;
    padding:3px 10px; border-radius:20px; margin-left:10px;
}
.cfg-badge-novo {
    display:inline-flex; align-items:center; gap:5px;
    background:rgba(79,142,247,.12); border:1px solid rgba(79,142,247,.25);
    color:#93c5fd; font-size:.72rem; font-weight:600;
    padding:3px 10px; border-radius:20px; margin-left:10px;
}

.swal-progress-green { background:#34d399 !important; }
</style>
@endsection

<div class="cfg-page">
    <div class="cfg-inner">

        <a href="{{ route('configuracoes.faixas') }}" class="cfg-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Faixas Etárias (Saúde)
        </a>

        <div class="cfg-header">
            <h1 class="cfg-title">Valores Odontológicos</h1>
            <p class="cfg-sub">Cadastre o valor único por plano e cidade</p>
        </div>

        <div class="cfg-card">

            {{-- Plano --}}
            <div class="cfg-filtro-group">
                <label>Plano</label>
                <select id="od_plano" class="cfg-filtro-select">
                    <option value="">Selecione o plano...</option>
                    @foreach($planos as $p)
                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>

            {{-- UF + Cidade --}}
            <div class="cfg-grid-3">
                <div class="cfg-filtro-group">
                    <label>UF</label>
                    <select id="od_uf" class="cfg-filtro-select">
                        <option value="">UF...</option>
                    </select>
                </div>
                <div class="cfg-filtro-group">
                    <label>Cidade</label>
                    <select id="od_cidade" class="cfg-filtro-select" disabled>
                        <option value="">Selecione a UF primeiro...</option>
                    </select>
                </div>
            </div>

            {{-- Valor (aparece após selecionar os 3) --}}
            <div id="odValorWrap" class="cfg-valor-wrap">
                <div class="cfg-valor-label">
                    Valor mensal
                    <span id="odBadge"></span>
                </div>
                <div class="cfg-valor-input-row">
                    <span class="cfg-valor-prefix">R$</span>
                    <input type="text" id="od_valor" class="cfg-valor-input" placeholder="0,00">
                </div>
                <div id="odMsgErro" class="cfg-msg-erro"></div>
                <button id="btnSalvarOdonto" class="cfg-btn-salvar" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                    Salvar Valor
                </button>
            </div>

        </div>
    </div>
</div>

<script>
$(function () {
    var urlCarregar = "{{ route('configuracoes.odonto.carregar') }}";
    var urlSalvar   = "{{ route('configuracoes.odonto.salvar') }}";

    // ── UF cascade via JSON ──
    $.getJSON("{{ asset('js/estados_cidades.json') }}", function (estadosCidades) {
        var ufOpts = '<option value="">UF...</option>';
        $.each(estadosCidades, function (i, estado) {
            ufOpts += '<option value="' + estado.sigla + '">' + estado.sigla + '</option>';
        });
        $('#od_uf').html(ufOpts);

        $('#od_uf').on('change', function () {
            var uf = $(this).val();
            $('#od_cidade').html('<option value="">Selecione a cidade...</option>').prop('disabled', true);
            $('#odValorWrap').hide();
            if (!uf) return;
            $.each(estadosCidades, function (i, estado) {
                if (estado.sigla === uf) {
                    var opts = '<option value="">Selecione a cidade...</option>';
                    $.each(estado.cidades, function (j, c) {
                        opts += '<option value="' + c + '">' + c + '</option>';
                    });
                    $('#od_cidade').html(opts).prop('disabled', false);
                    return false;
                }
            });
        });
    });

    // Auto-carregar quando os 3 estiverem preenchidos
    $('#od_plano').on('change', function () {
        if (todosSelecionados()) carregarOdonto();
        else $('#odValorWrap').hide();
    });
    $('#od_cidade').on('change', function () {
        if (todosSelecionados()) carregarOdonto();
        else $('#odValorWrap').hide();
    });

    function todosSelecionados() {
        return $('#od_plano').val() && $('#od_uf').val() && $('#od_cidade').val();
    }

    function carregarOdonto() {
        var planoId = $('#od_plano').val();
        var uf      = $('#od_uf').val();
        var cidade  = $('#od_cidade').val();

        $('#odMsgErro').hide().text('');
        $('#od_valor').val('');
        $('#odBadge').html('');
        $('#btnSalvarOdonto').prop('disabled', true);

        $.ajax({
            url: urlCarregar,
            method: 'GET',
            data: { plano_id: planoId, uf: uf, cidade: cidade },
            success: function (res) {
                $('#od_valor').val(res.valor || '');
                if (res.existe) {
                    $('#odBadge').html('<span class="cfg-badge-existe">● Já cadastrado</span>');
                    $('#btnSalvarOdonto').html(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Atualizar Valor'
                    );
                } else {
                    $('#odBadge').html('<span class="cfg-badge-novo">+ Novo</span>');
                    $('#btnSalvarOdonto').html(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Salvar Valor'
                    );
                }
                $('#odValorWrap').show();
                $('#btnSalvarOdonto').prop('disabled', false);

                if (typeof $.fn.mask === 'function') {
                    $('#od_valor').mask('#.##0,00', { reverse: true });
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao carregar.';
                $('#odMsgErro').text(msg).show();
                $('#odValorWrap').show();
            }
        });
    }

    // ── Salvar ──
    $('#btnSalvarOdonto').on('click', function () {
        var planoId = $('#od_plano').val();
        var uf      = $('#od_uf').val();
        var cidade  = $('#od_cidade').val();
        var valor   = $('#od_valor').val().trim();

        $('#odMsgErro').hide().text('');

        if (!valor) {
            $('#od_valor').val('0,00');
            valor = '0,00';
        }

        $('#btnSalvarOdonto').prop('disabled', true).text('Salvando...');

        $.ajax({
            url: urlSalvar,
            method: 'POST',
            data: {
                _token:   $('meta[name="csrf-token"]').attr('content'),
                plano_id: planoId,
                uf:       uf,
                cidade:   cidade,
                valor:    valor,
            },
            success: function (res) {
                var plano = $('#od_plano option:selected').text();
                Swal.fire({
                    icon: 'success',
                    title: 'Valor salvo com sucesso!',
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
                $('#odBadge').html('<span class="cfg-badge-existe">● Já cadastrado</span>');
                $('#btnSalvarOdonto').prop('disabled', false).html(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Atualizar Valor'
                );
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao salvar.';
                Swal.fire({ icon: 'error', title: 'Erro', text: msg, background: '#1a2540', color: '#e2e8f0', iconColor: '#f87171' });
                $('#btnSalvarOdonto').prop('disabled', false);
            }
        });
    });
});
</script>

@section('scripts')
@endsection

</x-app-layout>
