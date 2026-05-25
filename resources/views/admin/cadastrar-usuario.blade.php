<x-app-layout>

@section('css')
<link rel="stylesheet" href="{{ asset('css/estilo-financeiro.css') }}">
<style>
/* ── Page ── */
.usr-page  { background:#0f1623; min-height:100vh; padding:28px 20px; }
.usr-inner { max-width:1300px; margin:0 auto; }

/* ── Header ── */
.usr-header  { margin-bottom:28px; }
.usr-title   { font-size:1.35rem; font-weight:800; color:#fff; margin:0; }
.usr-sub     { font-size:.78rem; color:rgba(255,255,255,.4); margin:4px 0 0; }

/* ── Layout ── */
.usr-layout {
    display:grid;
    grid-template-columns:360px 1fr;
    gap:20px;
    align-items:start;
}
@media(max-width:900px) { .usr-layout { grid-template-columns:1fr; } }

/* ── Card base ── */
.usr-card {
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.08);
    border-radius:14px;
    overflow:hidden;
}
.usr-card-header {
    display:flex; align-items:center; gap:10px;
    padding:14px 20px 12px;
    border-bottom:1px solid rgba(255,255,255,.07);
}
.usr-card-title {
    font-size:.92rem; font-weight:700; color:#fff; margin:0;
}
.usr-card-body { padding:20px; }

/* ── Alerts ── */
.usr-alert {
    border-radius:9px; padding:11px 14px;
    margin-bottom:16px; font-size:.82rem; font-weight:500;
}
.usr-alert.ok  { background:rgba(52,211,153,.1); border:1px solid rgba(52,211,153,.3); color:#6ee7b7; }
.usr-alert.err { background:rgba(248,113,113,.1); border:1px solid rgba(248,113,113,.3); color:#fca5a5; }
.usr-alert ul  { margin:0; padding:0 0 0 16px; }
.usr-alert li  { margin:2px 0; }

/* ── Form ── */
.usr-form-group  { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
.usr-form-label  {
    font-size:.73rem; font-weight:600; color:rgba(255,255,255,.5);
    text-transform:uppercase; letter-spacing:.04em;
}
.usr-form-input {
    background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(255,255,255,.12); border-radius:9px;
    padding:9px 13px; font-size:.84rem; outline:none; width:100%;
    transition:border-color .2s; box-sizing:border-box;
}
.usr-form-input:focus { border-color:rgba(79,142,247,.5); }
.usr-form-input::placeholder { color:rgba(255,255,255,.2); }

.usr-btn-submit {
    width:100%; display:flex; align-items:center; justify-content:center; gap:8px;
    background:#4f8ef7; color:#fff;
    border:none; border-radius:9px;
    padding:10px; font-size:.88rem; font-weight:700;
    cursor:pointer; transition:background .2s; margin-top:4px;
}
.usr-btn-submit:hover { background:#3a7de0; }

/* ── Tabela ── */
.usr-table-wrap { overflow-x:auto; }
.usr-table {
    width:100%; border-collapse:collapse;
    font-size:.82rem;
}
.usr-table thead tr {
    border-bottom:2px solid rgba(255,255,255,.1);
}
.usr-table thead th {
    padding:10px 14px; text-align:left;
    font-size:.7rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.05em;
    color:rgba(255,255,255,.4);
}
.usr-table thead th.center { text-align:center; }
.usr-table tbody tr {
    border-bottom:1px solid rgba(255,255,255,.05);
    transition:background .12s;
}
.usr-table tbody tr:last-child { border-bottom:none; }
.usr-table tbody tr:hover { background:rgba(255,255,255,.03); }
.usr-table tbody td { padding:11px 14px; color:#e2e8f0; }
.usr-table tbody td.center { text-align:center; }
.usr-table tbody td.muted   { color:rgba(255,255,255,.5); font-size:.78rem; }

/* ── Código badge ── */
.usr-codigo {
    display:inline-block;
    background:rgba(79,142,247,.1); color:#93c5fd;
    border:1px solid rgba(79,142,247,.2);
    border-radius:6px; padding:2px 8px;
    font-size:.72rem; font-family:monospace; letter-spacing:.04em;
}

/* ── Comissão input inline ── */
.usr-com-wrap { display:flex; align-items:center; justify-content:center; gap:6px; }
.usr-com-input {
    width:72px; text-align:center;
    background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(255,255,255,.1); border-radius:7px;
    padding:5px 8px; font-size:.82rem; outline:none;
    transition:border-color .2s;
}
.usr-com-input:focus { border-color:rgba(79,142,247,.5); }
.usr-com-suffix { font-size:.72rem; color:rgba(255,255,255,.35); }
.usr-com-save {
    display:none;
    background:rgba(52,211,153,.15); color:#34d399;
    border:1px solid rgba(52,211,153,.3); border-radius:7px;
    padding:4px 10px; font-size:.72rem; font-weight:700;
    cursor:pointer; transition:all .2s; white-space:nowrap;
}
.usr-com-save:hover { background:rgba(52,211,153,.28); }
.usr-com-save.visible { display:inline-flex; align-items:center; gap:4px; }

/* ── Delete button ── */
.usr-btn-del {
    background:none; border:none; cursor:pointer;
    color:rgba(248,113,113,.5); padding:4px; border-radius:6px;
    transition:all .2s; display:flex; align-items:center;
}
.usr-btn-del:hover { color:#f87171; background:rgba(248,113,113,.1); }

/* ── Empty state ── */
.usr-empty {
    text-align:center; padding:36px 20px;
    color:rgba(255,255,255,.25); font-size:.83rem;
}

.swal-progress-green { background:#34d399 !important; }
</style>
@endsection

<div class="usr-page">
    <div class="usr-inner">

        {{-- ── Header ── --}}
        <div class="usr-header">
            <h1 class="usr-title">Cadastro de Usuários</h1>
            <p class="usr-sub">Gerencie os corretores, e-mails e comissões</p>
        </div>

        <div class="usr-layout">

            {{-- ── Formulário ── --}}
            <div class="usr-card">
                <div class="usr-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(79,142,247,.8)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                    </svg>
                    <h2 class="usr-card-title">Novo Usuário</h2>
                </div>
                <div class="usr-card-body">

                    @if(session('success'))
                        <div class="usr-alert ok">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="usr-alert err">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.store_usuario') }}" method="POST">
                        @csrf

                        <div class="usr-form-group">
                            <label class="usr-form-label">Nome completo</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="usr-form-input" placeholder="Ex: João da Silva" required>
                        </div>

                        <div class="usr-form-group">
                            <label class="usr-form-label">E-mail</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="usr-form-input" placeholder="email@exemplo.com" required>
                        </div>

                        <div class="usr-form-group" style="margin-bottom:20px;">
                            <label class="usr-form-label">Comissão (%)</label>
                            <input type="number" name="comissao" value="{{ old('comissao') }}"
                                   class="usr-form-input" placeholder="Ex: 30"
                                   min="0" max="100" step="0.01" required>
                        </div>

                        <button type="submit" class="usr-btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Cadastrar Usuário
                        </button>
                    </form>

                    <p style="margin-top:14px;font-size:.72rem;color:rgba(255,255,255,.25);text-align:center;line-height:1.5;">
                        Uma senha temporária é gerada automaticamente.<br>O usuário pode alterá-la no primeiro acesso.
                    </p>

                </div>
            </div>

            {{-- ── Listagem ── --}}
            <div class="usr-card">
                <div class="usr-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="rgba(255,255,255,.45)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                    <h2 class="usr-card-title">Usuários Cadastrados</h2>
                    <span style="margin-left:auto;font-size:.72rem;color:rgba(255,255,255,.3);">
                        {{ $usuarios->count() }} usuário(s)
                    </span>
                </div>
                <div class="usr-table-wrap">
                    @if($usuarios->isEmpty())
                        <div class="usr-empty">Nenhum usuário cadastrado ainda.</div>
                    @else
                        <table class="usr-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th class="center">Comissão</th>
                                    <th class="center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                <tr data-user-id="{{ $usuario->id }}">
                                    <td style="font-weight:600;">{{ $usuario->name }}</td>
                                    <td class="muted">{{ $usuario->email }}</td>
                                    <td class="center">
                                        <div class="usr-com-wrap">
                                            <input type="number"
                                                   class="usr-com-input"
                                                   value="{{ $usuario->comissao?->valor ?? 0 }}"
                                                   min="0" max="100" step="0.01"
                                                   data-user-id="{{ $usuario->id }}"
                                                   data-original="{{ $usuario->comissao?->valor ?? 0 }}">
                                            <span class="usr-com-suffix">%</span>
                                            <button class="usr-com-save" data-user-id="{{ $usuario->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke-width="2.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                                </svg>
                                                Salvar
                                            </button>
                                        </div>
                                    </td>
                                    <td class="center">
                                        <button class="usr-btn-del"
                                                data-user-id="{{ $usuario->id }}"
                                                data-nome="{{ $usuario->name }}"
                                                title="Excluir usuário">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── Comissão: mostrar botão Salvar ao editar ──────────────────────────────
    $(document).on('input', '.usr-com-input', function () {
        var $btn = $(this).closest('.usr-com-wrap').find('.usr-com-save');
        if ($(this).val() != $(this).data('original')) {
            $btn.addClass('visible');
        } else {
            $btn.removeClass('visible');
        }
    });

    // ── Salvar comissão ───────────────────────────────────────────────────────
    $(document).on('click', '.usr-com-save', function () {
        var $btn     = $(this);
        var userId   = $btn.data('user-id');
        var $input   = $btn.closest('.usr-com-wrap').find('.usr-com-input');
        var comissao = $input.val();

        $btn.prop('disabled', true).text('...');

        $.ajax({
            url: '{{ route("admin.atualizar_comissao") }}',
            method: 'POST',
            data: { user_id: userId, comissao: comissao },
            success: function () {
                $input.data('original', comissao);
                $btn.removeClass('visible').prop('disabled', false).html(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke-width="2.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Salvar'
                );
                $input.css({'border-color':'rgba(52,211,153,.6)'});
                setTimeout(function () { $input.css({'border-color':''}); }, 1500);
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erro ao salvar.';
                Swal.fire({ icon:'error', title:'Erro', text:msg, background:'#1a2540', color:'#e2e8f0', iconColor:'#f87171', confirmButtonColor:'#4f8ef7' });
                $btn.prop('disabled', false).html(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke-width="2.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Salvar'
                );
            }
        });
    });

    // ── Excluir usuário ───────────────────────────────────────────────────────
    $(document).on('click', '.usr-btn-del', function () {
        var userId = $(this).data('user-id');
        var nome   = $(this).data('nome');

        Swal.fire({
            title: 'Excluir usuário?',
            html: '<span style="color:rgba(255,255,255,.65);font-size:.9rem;"><strong style="color:#fff;">' + nome + '</strong> e sua comissão serão removidos permanentemente.</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Excluir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#4f8ef7',
            background: '#1a2540',
            color: '#e2e8f0',
            iconColor: '#fbbf24',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: '{{ route("admin.excluir_usuario") }}',
                method: 'POST',
                data: { user_id: userId },
                success: function () {
                    $('tr[data-user-id="' + userId + '"]').fadeOut(250, function () { $(this).remove(); });
                },
                error: function () {
                    Swal.fire({ icon:'error', title:'Erro', text:'Não foi possível excluir o usuário.', background:'#1a2540', color:'#e2e8f0', iconColor:'#f87171', confirmButtonColor:'#4f8ef7' });
                }
            });
        });
    });
});
</script>

@section('scripts')
@endsection

</x-app-layout>
