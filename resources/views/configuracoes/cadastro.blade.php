<x-app-layout>

@section('css')
<link rel="stylesheet" href="{{ asset('css/estilo-financeiro.css') }}">
<style>
/* ── Page ── */
.cad-page  { background:#0f1623; min-height:100vh; padding:28px 20px; }
.cad-inner { max-width:1400px; margin:0 auto; }

/* ── Header row ── */
.cad-header-row {
    display:flex; justify-content:space-between; align-items:flex-start;
    margin-bottom:28px; flex-wrap:wrap; gap:14px;
}
.cad-title { font-size:1.35rem; font-weight:800; color:#fff; margin:0; }
.cad-sub   { font-size:.78rem; color:rgba(255,255,255,.4); margin:4px 0 0; }

/* ── Action buttons ── */
.cad-action-btns { display:flex; gap:10px; flex-wrap:wrap; }
.cad-btn-saude {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(34,197,94,.12); color:#4ade80;
    border:1px solid rgba(34,197,94,.32); border-radius:10px;
    padding:9px 18px; font-size:.85rem; font-weight:700;
    cursor:pointer; transition:all .2s;
}
.cad-btn-saude:hover { background:rgba(34,197,94,.22); border-color:rgba(34,197,94,.55); }
.cad-btn-odonto {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(59,130,246,.12); color:#60a5fa;
    border:1px solid rgba(59,130,246,.32); border-radius:10px;
    padding:9px 18px; font-size:.85rem; font-weight:700;
    cursor:pointer; transition:all .2s;
}
.cad-btn-odonto:hover { background:rgba(59,130,246,.22); border-color:rgba(59,130,246,.55); }
.cad-btn-planos {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(139,92,246,.12); color:#c4b5fd;
    border:1px solid rgba(139,92,246,.32); border-radius:10px;
    padding:9px 18px; font-size:.85rem; font-weight:700;
    cursor:pointer; transition:all .2s;
}
.cad-btn-planos:hover { background:rgba(139,92,246,.22); border-color:rgba(139,92,246,.55); }

/* ── Modal Planos ── */
.planos-modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.72); z-index:10000;
    align-items:center; justify-content:center;
}
.planos-modal-overlay.show { display:flex; }
.planos-modal-box {
    background:#111927;
    border:1px solid rgba(255,255,255,.1);
    border-radius:16px;
    width:min(560px, 96vw);
    max-height:88vh;
    display:flex; flex-direction:column;
    overflow:hidden;
}
.planos-modal-header {
    display:flex; justify-content:space-between; align-items:center;
    padding:15px 20px 13px;
    border-bottom:1px solid rgba(255,255,255,.08); flex-shrink:0;
}
.planos-modal-title { font-size:1rem; font-weight:700; color:#fff; }
.planos-modal-body  { overflow-y:auto; flex:1; padding:20px; }

/* Form novo plano */
.planos-novo-form {
    display:flex; gap:8px; align-items:center;
    background:rgba(139,92,246,.07);
    border:1px solid rgba(139,92,246,.2);
    border-radius:11px; padding:12px 14px;
    margin-bottom:18px; flex-wrap:wrap;
}
.planos-novo-inp {
    flex:1; min-width:140px;
    background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(255,255,255,.12); border-radius:8px;
    padding:8px 12px; font-size:.84rem; outline:none;
    transition:border-color .2s;
}
.planos-novo-inp:focus { border-color:rgba(139,92,246,.55); }
.planos-novo-inp::placeholder { color:rgba(255,255,255,.25); }
.planos-emp-label {
    display:inline-flex; align-items:center; gap:6px;
    font-size:.76rem; color:rgba(255,255,255,.55); cursor:pointer;
    white-space:nowrap;
}
.planos-emp-label input[type=checkbox] { accent-color:#8b5cf6; width:14px; height:14px; cursor:pointer; }
.planos-btn-add {
    display:inline-flex; align-items:center; gap:6px;
    background:#8b5cf6; color:#fff;
    border:none; border-radius:8px;
    padding:8px 16px; font-size:.82rem; font-weight:700;
    cursor:pointer; transition:background .2s; white-space:nowrap;
}
.planos-btn-add:hover { background:#7c3aed; }
.planos-btn-add:disabled { opacity:.5; cursor:default; }

/* Lista de planos */
.planos-lista { display:flex; flex-direction:column; gap:6px; }
.planos-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; border-radius:10px;
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.07);
    transition:background .15s;
}
.planos-item:hover { background:rgba(255,255,255,.07); }
.planos-item-nome {
    flex:1; font-size:.88rem; font-weight:600; color:#e2e8f0;
}
.planos-item-emp {
    font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em;
    padding:2px 8px; border-radius:20px;
    background:rgba(139,92,246,.12); color:#c4b5fd;
    border:1px solid rgba(139,92,246,.25);
    white-space:nowrap;
}
.planos-item-emp.nao {
    background:rgba(255,255,255,.05); color:rgba(255,255,255,.3);
    border-color:rgba(255,255,255,.1);
}
.planos-item-edit {
    display:flex; align-items:center; gap:4px;
}
.planos-item-edit input {
    background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(139,92,246,.4); border-radius:7px;
    padding:5px 10px; font-size:.83rem; outline:none;
    width:160px;
}
.planos-ibtn {
    background:none; border:none; cursor:pointer;
    color:rgba(255,255,255,.35); transition:color .2s;
    padding:4px; border-radius:6px; display:flex; align-items:center;
}
.planos-ibtn:hover { color:#fff; background:rgba(255,255,255,.08); }
.planos-ibtn.edit:hover { color:#c4b5fd; }
.planos-ibtn.del:hover  { color:#f87171; }
.planos-ibtn.save:hover { color:#34d399; }
.planos-ibtn.cancel-edit:hover { color:rgba(255,255,255,.7); }
.planos-empty {
    text-align:center; padding:28px;
    color:rgba(255,255,255,.25); font-size:.82rem;
}
.planos-msg-erro { color:#fca5a5; font-size:.78rem; margin-top:8px; display:none; }

/* ── Section ── */
.cad-section { margin-bottom:40px; }
.cad-section-hd {
    display:flex; align-items:center; gap:10px;
    margin-bottom:16px;
}
.cad-section-dot {
    width:10px; height:10px; border-radius:50%; flex-shrink:0;
}
.cad-section-dot.saude  { background:#22c55e; box-shadow:0 0 8px rgba(34,197,94,.5); }
.cad-section-dot.odonto { background:#3b82f6; box-shadow:0 0 8px rgba(59,130,246,.5); }
.cad-section-title {
    font-size:.82rem; font-weight:700; color:rgba(255,255,255,.55);
    text-transform:uppercase; letter-spacing:.07em; margin:0;
}
.cad-section-line { flex:1; height:1px; background:rgba(255,255,255,.07); }
.cad-section-count {
    font-size:.72rem; color:rgba(255,255,255,.3); white-space:nowrap;
}

/* ── Cards grid ── */
.cad-cards-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(360px, 1fr));
    gap:16px;
}

/* ── Card ── */
.cad-card {
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.09);
    border-radius:14px;
    overflow:hidden;
    display:flex; flex-direction:column;
}
.cad-card-header {
    display:flex; justify-content:space-between; align-items:center;
    padding:13px 16px 11px;
    border-bottom:1px solid rgba(255,255,255,.07);
}
.cad-card-plano { font-size:.95rem; font-weight:700; color:#fff; }
.cad-card-badge {
    font-size:.66rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.05em; padding:3px 9px; border-radius:20px;
}
.cad-card-badge.saude  { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.25); }
.cad-card-badge.odonto { background:rgba(59,130,246,.12); color:#60a5fa; border:1px solid rgba(59,130,246,.25); }

/* ── City switcher ── */
.cad-city-row {
    display:flex; flex-wrap:wrap; gap:5px;
    padding:9px 16px;
    border-bottom:1px solid rgba(255,255,255,.06);
}
.cad-city-btn {
    font-size:.7rem; font-weight:600;
    padding:3px 10px; border-radius:20px;
    border:1px solid rgba(255,255,255,.1);
    background:rgba(255,255,255,.04);
    color:rgba(255,255,255,.45);
    cursor:pointer; transition:all .15s;
}
.cad-city-btn:hover  { background:rgba(255,255,255,.08); color:rgba(255,255,255,.75); }
.cad-city-btn.active { background:rgba(79,142,247,.18); border-color:rgba(79,142,247,.42); color:#93c5fd; }

/* ── Card body ── */
.cad-card-body { flex:1; }
.cad-card-loading {
    display:flex; align-items:center; justify-content:center;
    padding:22px; color:rgba(255,255,255,.25); font-size:.78rem;
}

/* ── Mini saude table ── */
.cad-mini-tbl {
    width:100%; border-collapse:collapse; font-size:.7rem;
}
.cad-mini-tbl thead .mth-group th {
    padding:6px 4px 2px; text-align:center;
    font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em;
    border-bottom:1px solid rgba(255,255,255,.05);
}
.cad-mini-tbl thead .mth-sub th {
    padding:2px 4px 6px; text-align:center;
    font-size:.63rem; font-weight:600; color:rgba(255,255,255,.38);
    border-bottom:1px solid rgba(255,255,255,.08);
}
.cad-mini-tbl .mth-faixa  { width:18%; text-align:left !important; padding-left:14px !important; color:rgba(255,255,255,.4); }
.cad-mini-tbl .mth-copart { color:#7dd3fc; }
.cad-mini-tbl .mth-sem    { color:#6ee7b7; }
.cad-mini-tbl .mth-apart  { color:rgba(125,211,252,.55); }
.cad-mini-tbl .mth-enfer  { color:rgba(125,211,252,.55); }
.cad-mini-tbl .mth-sapart { color:rgba(110,231,183,.55); }
.cad-mini-tbl .mth-senfer { color:rgba(110,231,183,.55); }
.cad-mini-tbl tbody tr { border-bottom:1px solid rgba(255,255,255,.04); }
.cad-mini-tbl tbody tr:last-child { border-bottom:none; }
.cad-mini-tbl tbody td { padding:4px 4px; text-align:center; color:rgba(255,255,255,.7); }
.cad-mini-tbl tbody td:first-child {
    text-align:left; padding-left:14px;
    color:rgba(255,255,255,.45); font-weight:600;
}
.cad-mini-tbl .val-copart { color:#7dd3fc; }
.cad-mini-tbl .val-sem    { color:#6ee7b7; }
.cad-mini-tbl .val-zero   { color:rgba(255,255,255,.18); }

/* ── Odonto card value ── */
.cad-odonto-display {
    padding:24px 16px;
    text-align:center;
}
.cad-odonto-lbl {
    font-size:.7rem; font-weight:600; text-transform:uppercase;
    letter-spacing:.04em; color:rgba(255,255,255,.35); margin-bottom:8px;
}
.cad-odonto-val {
    font-size:1.6rem; font-weight:700; color:#60a5fa;
}
.cad-odonto-val.zero { color:rgba(255,255,255,.2); font-size:1.1rem; }

/* ── Card footer ── */
.cad-card-footer {
    padding:9px 16px;
    border-top:1px solid rgba(255,255,255,.07);
    display:flex; justify-content:flex-end;
}
.cad-btn-editar {
    display:inline-flex; align-items:center; gap:6px;
    font-size:.76rem; font-weight:700;
    padding:5px 13px; border-radius:8px;
    background:rgba(79,142,247,.1); color:#93c5fd;
    border:1px solid rgba(79,142,247,.22);
    cursor:pointer; transition:all .2s;
}
.cad-btn-editar:hover { background:rgba(79,142,247,.2); border-color:rgba(79,142,247,.45); }

/* ── Empty state ── */
.cad-empty {
    color:rgba(255,255,255,.28); font-size:.83rem;
    padding:28px 20px; text-align:center;
    border:1px dashed rgba(255,255,255,.1); border-radius:12px;
    grid-column:1/-1;
}
.cad-empty strong { color:rgba(255,255,255,.5); }

/* ── Modal overlay ── */
.cad-modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.72); z-index:10000;
    align-items:center; justify-content:center;
}
.cad-modal-overlay.show { display:flex; }

/* ── Modal box ── */
.cad-modal-box {
    background:#111927;
    border:1px solid rgba(255,255,255,.1);
    border-radius:16px;
    width:min(800px, 96vw);
    max-height:92vh;
    display:flex; flex-direction:column;
    overflow:hidden;
}
.cad-modal-header {
    display:flex; justify-content:space-between; align-items:center;
    padding:15px 20px 13px;
    border-bottom:1px solid rgba(255,255,255,.08); flex-shrink:0;
}
.cad-modal-title { font-size:1rem; font-weight:700; color:#fff; }
.cad-modal-close {
    background:rgba(255,255,255,.07); border:none; color:rgba(255,255,255,.55);
    width:28px; height:28px; border-radius:7px;
    cursor:pointer; font-size:1.1rem;
    display:flex; align-items:center; justify-content:center;
    transition:all .2s; line-height:1;
}
.cad-modal-close:hover { background:rgba(255,255,255,.14); color:#fff; }

/* ── Step indicator ── */
.cad-steps {
    display:flex; border-bottom:1px solid rgba(255,255,255,.07);
    flex-shrink:0;
}
.cad-step {
    flex:1; padding:11px 14px; text-align:center;
    font-size:.76rem; font-weight:600;
    color:rgba(255,255,255,.3);
    background:rgba(255,255,255,.02);
    transition:all .2s;
}
.cad-step + .cad-step { border-left:1px solid rgba(255,255,255,.07); }
.cad-step.active  { background:rgba(79,142,247,.1); color:#93c5fd; }
.cad-step.done    { background:rgba(52,211,153,.06); color:#6ee7b7; }

/* ── Modal body ── */
.cad-modal-body { overflow-y:auto; flex:1; padding:20px 22px; }

/* ── Step 1 form ── */
.cad-form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
.cad-form-label { font-size:.73rem; font-weight:600; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:.04em; }
.cad-form-select {
    background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(255,255,255,.12); border-radius:9px;
    padding:9px 12px; font-size:.83rem; outline:none; width:100%;
    transition:border-color .2s;
}
.cad-form-select:focus { border-color:rgba(79,142,247,.5); }
.cad-form-select option { background:#1a2540; color:#e2e8f0; }
.cad-form-grid2 { display:grid; grid-template-columns:110px 1fr; gap:14px; }

/* ── Step 2 info bar ── */
.cad-modal-info {
    background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08);
    border-radius:9px; padding:10px 14px;
    margin-bottom:16px; font-size:.8rem; color:rgba(255,255,255,.55);
}
.cad-modal-info strong { color:#e2e8f0; }

/* ── Step 2 faixa grid ── */
.cad-modal-faixa-tbl { width:100%; border-collapse:collapse; }
.cad-modal-faixa-tbl thead .cfg-th-grupo th {
    padding:8px 6px 3px; font-size:.68rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.05em;
    text-align:center; border-bottom:1px solid rgba(255,255,255,.05);
}
.cad-modal-faixa-tbl thead .cfg-th-sub th {
    padding:3px 6px 8px; font-size:.65rem; font-weight:600;
    color:rgba(255,255,255,.38); text-align:center;
    border-bottom:2px solid rgba(255,255,255,.09);
}
.cad-modal-faixa-tbl .th-faixa   { width:14%; color:rgba(255,255,255,.45); text-align:left !important; padding-left:4px !important; }
.cad-modal-faixa-tbl .th-copart  { color:#93c5fd; }
.cad-modal-faixa-tbl .th-sem     { color:#6ee7b7; }
.cad-modal-faixa-tbl tbody tr { border-bottom:1px solid rgba(255,255,255,.04); }
.cad-modal-faixa-tbl tbody tr:last-child { border-bottom:none; }
.cad-modal-faixa-tbl tbody td { padding:5px 5px; text-align:center; }
.cad-modal-faixa-tbl tbody td.td-faixa {
    text-align:left; font-size:.75rem; font-weight:600; color:rgba(255,255,255,.5);
}
.cad-faixa-inp {
    width:100%;
    background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(255,255,255,.1); border-radius:6px;
    padding:5px 7px; font-size:.79rem; text-align:right;
    outline:none; transition:border-color .2s;
}
.cad-faixa-inp.inp-copart { border-color:rgba(79,142,247,.18); }
.cad-faixa-inp.inp-sem    { border-color:rgba(52,211,153,.18); }
.cad-faixa-inp.inp-copart:focus { border-color:rgba(79,142,247,.6); }
.cad-faixa-inp.inp-sem:focus    { border-color:rgba(52,211,153,.6); }

/* ── Step 2 odonto ── */
.cad-modal-odonto-wrap {
    background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08);
    border-radius:12px; padding:28px; text-align:center;
}
.cad-modal-odonto-lbl {
    font-size:.75rem; font-weight:600; text-transform:uppercase;
    letter-spacing:.04em; color:rgba(255,255,255,.45); margin-bottom:14px;
}
.cad-modal-odonto-row { display:flex; align-items:center; gap:10px; max-width:220px; margin:0 auto; }
.cad-modal-odonto-pfx { font-size:1rem; font-weight:700; color:rgba(255,255,255,.4); }
.cad-modal-odonto-inp {
    flex:1; background:#1a2540; color:#e2e8f0;
    border:1px solid rgba(52,211,153,.32); border-radius:9px;
    padding:10px 14px; font-size:1.1rem; font-weight:600;
    text-align:right; outline:none; transition:border-color .2s, box-shadow .2s;
}
.cad-modal-odonto-inp:focus { border-color:rgba(52,211,153,.7); box-shadow:0 0 0 3px rgba(52,211,153,.12); }

/* ── Modal footer ── */
.cad-modal-footer {
    padding:13px 22px;
    border-top:1px solid rgba(255,255,255,.07);
    display:flex; justify-content:flex-end; gap:10px;
    flex-shrink:0;
}
.cad-mbtn-cancel {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.06); color:rgba(255,255,255,.6);
    border:1px solid rgba(255,255,255,.1); border-radius:9px;
    padding:8px 16px; font-size:.83rem; font-weight:600;
    cursor:pointer; transition:all .2s;
}
.cad-mbtn-cancel:hover { background:rgba(255,255,255,.12); color:#fff; }
.cad-mbtn-back {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.06); color:rgba(255,255,255,.6);
    border:1px solid rgba(255,255,255,.1); border-radius:9px;
    padding:8px 16px; font-size:.83rem; font-weight:600;
    cursor:pointer; transition:all .2s;
}
.cad-mbtn-back:hover { background:rgba(255,255,255,.12); color:#fff; }
.cad-mbtn-next {
    display:inline-flex; align-items:center; gap:7px;
    background:#4f8ef7; color:#fff;
    border:none; border-radius:9px;
    padding:9px 22px; font-size:.84rem; font-weight:700;
    cursor:pointer; transition:background .2s;
}
.cad-mbtn-next:hover { background:#3a7de0; }
.cad-mbtn-next:disabled { opacity:.5; cursor:default; }
.cad-mbtn-save {
    display:inline-flex; align-items:center; gap:7px;
    background:#34d399; color:#0a1628;
    border:none; border-radius:9px;
    padding:9px 22px; font-size:.84rem; font-weight:700;
    cursor:pointer; transition:background .2s;
}
.cad-mbtn-save:hover { background:#10b981; }
.cad-mbtn-save:disabled { opacity:.5; cursor:default; }

.swal-progress-green { background:#34d399 !important; }
</style>
@endsection

<div class="cad-page">
    <div class="cad-inner">

        {{-- ── Header ── --}}
        <div class="cad-header-row">
            <div>
                <h1 class="cad-title">Cadastro de Tabelas de Valor</h1>
                <p class="cad-sub">Gerencie valores de saúde e odonto por plano e cidade</p>
            </div>
            <div class="cad-action-btns">
                <button id="btnNovoSaude" class="cad-btn-saude">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/>
                    </svg>
                    Saúde
                </button>
                <button id="btnNovoOdonto" class="cad-btn-odonto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2c-2.4 0-4.5 1.3-5.5 3.2-.6 1.1-.8 2.3-.6 3.5l1.2 6.8c.3 1.5.9 3.5 2.2 3.5.8 0 1.3-.7 1.7-1.7.4-1 .6-2.3 1-3.3.4 1 .6 2.3 1 3.3.4 1 .9 1.7 1.7 1.7 1.3 0 1.9-2 2.2-3.5l1.2-6.8c.2-1.2 0-2.4-.6-3.5C16.5 3.3 14.4 2 12 2z"/>
                    </svg>
                    Odonto
                </button>
                <button id="btnGerenciarPlanos" class="cad-btn-planos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                    </svg>
                    Planos
                </button>
            </div>
        </div>

        {{-- ── Saúde Section ── --}}
        <div class="cad-section">
            <div class="cad-section-hd">
                <div class="cad-section-dot saude"></div>
                <h2 class="cad-section-title">Saúde</h2>
                <div class="cad-section-line"></div>
                <span class="cad-section-count">{{ count($saudeCards) }} plano(s) cadastrado(s)</span>
            </div>
            <div class="cad-cards-grid">
                @forelse($saudeCards as $card)
                    @php $plano = $card['plano']; $defaultCidade = $card['default_cidade']; @endphp
                    <div class="cad-card" data-plano-id="{{ $plano->id }}" data-tipo="saude">
                        <div class="cad-card-header">
                            <span class="cad-card-plano">{{ $plano->nome }}</span>
                            <span class="cad-card-badge saude">Saúde</span>
                        </div>
                        <div class="cad-city-row">
                            @foreach($card['cidades'] as $cidade)
                                <button class="cad-city-btn {{ $cidade->id === $defaultCidade->id ? 'active' : '' }}"
                                        data-cidade-nome="{{ $cidade->nome }}"
                                        data-cidade-uf="{{ $cidade->uf }}">
                                    {{ $cidade->nome }}
                                </button>
                            @endforeach
                        </div>
                        <div class="cad-card-body">
                            <div class="cad-card-loading">Carregando...</div>
                        </div>
                        <div class="cad-card-footer">
                            <button class="cad-btn-editar"
                                    data-tipo="saude"
                                    data-plano-id="{{ $plano->id }}"
                                    data-plano-nome="{{ $plano->nome }}"
                                    data-cidade-atual="{{ $defaultCidade->nome }}"
                                    data-uf-atual="{{ $defaultCidade->uf }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                </svg>
                                Editar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="cad-empty">
                        Nenhuma tabela de saúde cadastrada ainda.<br>
                        Use o botão <strong>Saúde</strong> acima para criar a primeira tabela.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── Odonto Section ── --}}
        <div class="cad-section">
            <div class="cad-section-hd">
                <div class="cad-section-dot odonto"></div>
                <h2 class="cad-section-title">Odonto</h2>
                <div class="cad-section-line"></div>
                <span class="cad-section-count">{{ count($odontoCards) }} plano(s) cadastrado(s)</span>
            </div>
            <div class="cad-cards-grid">
                @forelse($odontoCards as $card)
                    @php $plano = $card['plano']; $defaultCidade = $card['default_cidade']; @endphp
                    <div class="cad-card" data-plano-id="{{ $plano->id }}" data-tipo="odonto">
                        <div class="cad-card-header">
                            <span class="cad-card-plano">{{ $plano->nome }}</span>
                            <span class="cad-card-badge odonto">Odonto</span>
                        </div>
                        <div class="cad-city-row">
                            @foreach($card['cidades'] as $cidade)
                                <button class="cad-city-btn {{ $cidade->id === $defaultCidade->id ? 'active' : '' }}"
                                        data-cidade-nome="{{ $cidade->nome }}"
                                        data-cidade-uf="{{ $cidade->uf }}">
                                    {{ $cidade->nome }}
                                </button>
                            @endforeach
                        </div>
                        <div class="cad-card-body">
                            <div class="cad-card-loading">Carregando...</div>
                        </div>
                        <div class="cad-card-footer">
                            <button class="cad-btn-editar"
                                    data-tipo="odonto"
                                    data-plano-id="{{ $plano->id }}"
                                    data-plano-nome="{{ $plano->nome }}"
                                    data-cidade-atual="{{ $defaultCidade->nome }}"
                                    data-uf-atual="{{ $defaultCidade->uf }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                </svg>
                                Editar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="cad-empty">
                        Nenhum valor odonto cadastrado ainda.<br>
                        Use o botão <strong>Odonto</strong> acima para criar o primeiro cadastro.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     Modal: Nova tabela / Editar
     ═══════════════════════════════════════════════════════ --}}
<div id="cadModalOverlay" class="cad-modal-overlay">
    <div class="cad-modal-box">

        <div class="cad-modal-header">
            <span id="cadModalTitle" class="cad-modal-title">Nova Tabela</span>
            <button id="cadModalClose" class="cad-modal-close" title="Fechar">✕</button>
        </div>

        <div class="cad-steps">
            <div class="cad-step active" id="cadStep1">1 · Identificação</div>
            <div class="cad-step"        id="cadStep2">2 · Valores</div>
        </div>

        <div class="cad-modal-body">

            {{-- ─ Step 1: Plano / UF / Cidade ─ --}}
            <div id="cadModalStep1">
                <div class="cad-form-group">
                    <label class="cad-form-label">Plano</label>
                    <select id="cadModalPlano" class="cad-form-select">
                        <option value="">Selecione o plano...</option>
                        @foreach($planos as $p)
                            <option value="{{ $p->id }}">{{ $p->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cad-form-grid2">
                    <div class="cad-form-group">
                        <label class="cad-form-label">UF</label>
                        <select id="cadModalUf" class="cad-form-select">
                            <option value="">UF...</option>
                        </select>
                    </div>
                    <div class="cad-form-group">
                        <label class="cad-form-label">Cidade</label>
                        <select id="cadModalCidade" class="cad-form-select" disabled>
                            <option value="">Selecione a UF primeiro...</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ─ Step 2: Valores ─ --}}
            <div id="cadModalStep2" style="display:none;">

                <div id="cadModalInfoTxt" class="cad-modal-info"></div>

                {{-- Saúde: grid de faixas --}}
                <div id="cadModalStep2Saude">
                    <div id="cadModalFaixasWrap"></div>
                </div>

                {{-- Odonto: valor único --}}
                <div id="cadModalStep2Odonto" style="display:none;">
                    <div class="cad-modal-odonto-wrap">
                        <div class="cad-modal-odonto-lbl">Valor mensal por beneficiário</div>
                        <div class="cad-modal-odonto-row">
                            <span class="cad-modal-odonto-pfx">R$</span>
                            <input type="text" id="cadModalOdontoInp" class="cad-modal-odonto-inp" placeholder="0,00">
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="cad-modal-footer">
            <button id="cadModalCancel" class="cad-mbtn-cancel">Cancelar</button>
            <button id="cadModalBack"   class="cad-mbtn-back"   style="display:none;">← Voltar</button>
            <button id="cadModalNext"   class="cad-mbtn-next">Próximo →</button>
            <button id="cadModalSave"   class="cad-mbtn-save"   style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
                Salvar
            </button>
        </div>

    </div>
</div>

<script>
$(function () {
    var urlFaixasCarregar  = "{{ route('configuracoes.faixas.carregar') }}";
    var urlFaixasSalvar    = "{{ route('configuracoes.faixas.salvar') }}";
    var urlOdontoCarregar  = "{{ route('configuracoes.odonto.carregar') }}";
    var urlOdontoSalvar    = "{{ route('configuracoes.odonto.salvar') }}";

    var faixasLabels = {
        0:'0 a 18', 1:'19 a 23', 2:'24 a 28', 3:'29 a 33', 4:'34 a 38',
        5:'39 a 43', 6:'44 a 48', 7:'49 a 53', 8:'54 a 58', 9:'59+'
    };

    var estadosCidades = null;
    var modalState     = { tipo: null, planoId: null, planoNome: null, uf: null, cidade: null };

    // ── Carregar UF/Cidades ──────────────────────────────────────────────────
    $.getJSON("{{ asset('js/estados_cidades.json') }}", function (data) {
        estadosCidades = data;
        preencherUfs();
    });

    function preencherUfs() {
        var opts = '<option value="">UF...</option>';
        $.each(estadosCidades, function (i, est) {
            opts += '<option value="' + est.sigla + '">' + est.sigla + '</option>';
        });
        $('#cadModalUf').html(opts);
    }

    $('#cadModalUf').on('change', function () {
        var uf = $(this).val();
        $('#cadModalCidade').html('<option value="">Selecione a cidade...</option>').prop('disabled', true);
        if (!uf || !estadosCidades) return;
        $.each(estadosCidades, function (i, est) {
            if (est.sigla === uf) {
                var opts = '<option value="">Selecione a cidade...</option>';
                $.each(est.cidades, function (j, c) {
                    opts += '<option value="' + c + '">' + c + '</option>';
                });
                $('#cadModalCidade').html(opts).prop('disabled', false);
                return false;
            }
        });
    });

    // ── Abrir modal: Novo ────────────────────────────────────────────────────
    $('#btnNovoSaude').on('click', function ()  { abrirModalNovo('saude');  });
    $('#btnNovoOdonto').on('click', function () { abrirModalNovo('odonto'); });

    function abrirModalNovo(tipo) {
        modalState = { tipo: tipo, planoId: null, planoNome: null, uf: null, cidade: null };
        $('#cadModalTitle').text('Nova Tabela — ' + (tipo === 'saude' ? 'Saúde' : 'Odonto'));
        $('#cadModalPlano').val('');
        if (estadosCidades) preencherUfs();
        $('#cadModalUf').val('');
        $('#cadModalCidade').html('<option value="">Selecione a UF primeiro...</option>').prop('disabled', true);
        mostrarStep(1);
        $('#cadModalOverlay').addClass('show');
    }

    // ── Abrir modal: Editar ──────────────────────────────────────────────────
    $(document).on('click', '.cad-btn-editar', function () {
        var tipo      = $(this).data('tipo');
        var planoId   = String($(this).data('plano-id'));
        var planoNome = $(this).data('plano-nome');
        var cidade    = $(this).data('cidade-atual');
        var uf        = $(this).data('uf-atual');

        modalState = { tipo: tipo, planoId: planoId, planoNome: planoNome, uf: uf, cidade: cidade };

        $('#cadModalTitle').text('Editar — ' + (tipo === 'saude' ? 'Saúde' : 'Odonto'));

        // Pré-preencher step 1 (caso usuário clique em Voltar)
        $('#cadModalPlano').val(planoId);
        if (estadosCidades) {
            preencherUfs();
            $('#cadModalUf').val(uf).trigger('change');
            setTimeout(function () { $('#cadModalCidade').val(cidade); }, 60);
        }

        mostrarStep(2);
        renderizarStep2();
        $('#cadModalOverlay').addClass('show');
    });

    // ── Navegação de steps ────────────────────────────────────────────────────
    function mostrarStep(step) {
        $('#cadStep1').removeClass('active done');
        $('#cadStep2').removeClass('active done');

        if (step === 1) {
            $('#cadStep1').addClass('active');
            $('#cadModalStep1').show();
            $('#cadModalStep2').hide();
            $('#cadModalBack').hide();
            $('#cadModalNext').show().prop('disabled', false);
            $('#cadModalSave').hide();
        } else {
            $('#cadStep1').addClass('done');
            $('#cadStep2').addClass('active');
            $('#cadModalStep1').hide();
            $('#cadModalStep2').show();
            $('#cadModalBack').show();
            $('#cadModalNext').hide();
            $('#cadModalSave').show().prop('disabled', false).html(
                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Salvar'
            );
        }
    }

    $('#cadModalNext').on('click', function () {
        var planoId   = $('#cadModalPlano').val();
        var uf        = $('#cadModalUf').val();
        var cidade    = $('#cadModalCidade').val();
        var planoNome = $('#cadModalPlano option:selected').text().trim();

        if (!planoId || !uf || !cidade) {
            Swal.fire({ icon:'warning', title:'Atenção', text:'Selecione plano, UF e cidade.', background:'#1a2540', color:'#e2e8f0', iconColor:'#fbbf24', confirmButtonColor:'#4f8ef7' });
            return;
        }

        modalState = { tipo: modalState.tipo, planoId: planoId, planoNome: planoNome, uf: uf, cidade: cidade };
        mostrarStep(2);
        renderizarStep2();
    });

    $('#cadModalBack').on('click', function () {
        mostrarStep(1);
    });

    // ── Renderizar step 2 ─────────────────────────────────────────────────────
    function renderizarStep2() {
        $('#cadModalInfoTxt').html(
            '<strong>' + modalState.planoNome + '</strong> — ' + modalState.cidade + ' / ' + modalState.uf
        );

        if (modalState.tipo === 'saude') {
            $('#cadModalStep2Saude').show();
            $('#cadModalStep2Odonto').hide();
            carregarFaixasModal();
        } else {
            $('#cadModalStep2Saude').hide();
            $('#cadModalStep2Odonto').show();
            carregarOdontoModal();
        }
    }

    function carregarFaixasModal() {
        $('#cadModalFaixasWrap').html('<div style="text-align:center;padding:22px;color:rgba(255,255,255,.3);font-size:.8rem;">Carregando...</div>');
        $.ajax({
            url: urlFaixasCarregar, method:'GET',
            data: { plano_id: modalState.planoId, uf: modalState.uf, cidade: modalState.cidade },
            success: function (res) { renderFaixasModal(res.valores || {}); },
            error: function () {
                $('#cadModalFaixasWrap').html('<div style="text-align:center;padding:22px;color:#fca5a5;font-size:.8rem;">Erro ao carregar valores.</div>');
            }
        });
    }

    function renderFaixasModal(valores) {
        var html = '<table class="cad-modal-faixa-tbl">';
        html += '<thead>';
        html += '<tr class="cfg-th-grupo">';
        html += '<th class="th-faixa">Faixa</th>';
        html += '<th colspan="2" class="th-copart">Com Coparticipação</th>';
        html += '<th colspan="2" class="th-sem">Sem Coparticipação</th>';
        html += '</tr>';
        html += '<tr class="cfg-th-sub">';
        html += '<th class="th-faixa"></th>';
        html += '<th>Apart.</th><th>Enfer.</th>';
        html += '<th>Apart.</th><th>Enfer.</th>';
        html += '</tr>';
        html += '</thead><tbody>';
        for (var i = 0; i <= 9; i++) {
            var v = valores[i] || {};
            html += '<tr>';
            html += '<td class="td-faixa">' + faixasLabels[i] + '</td>';
            html += '<td>' + inputFaixaModal(i, 'com_copart_apart', v.com_copart_apart, 'inp-copart') + '</td>';
            html += '<td>' + inputFaixaModal(i, 'com_copart_enfer', v.com_copart_enfer, 'inp-copart') + '</td>';
            html += '<td>' + inputFaixaModal(i, 'sem_copart_apart', v.sem_copart_apart, 'inp-sem') + '</td>';
            html += '<td>' + inputFaixaModal(i, 'sem_copart_enfer', v.sem_copart_enfer, 'inp-sem') + '</td>';
            html += '</tr>';
        }
        html += '</tbody></table>';
        $('#cadModalFaixasWrap').html(html);
        if (typeof $.fn.mask === 'function') {
            $('.cad-faixa-inp').mask('#.##0,00', { reverse: true });
        }
    }

    function inputFaixaModal(faixa, campo, valor, cls) {
        var val = (valor && parseFloat(valor) > 0)
            ? parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits:2, maximumFractionDigits:2 })
            : '';
        return '<input type="text" class="cad-faixa-inp ' + cls + '" '
             + 'data-faixa="' + faixa + '" data-campo="' + campo + '" '
             + 'value="' + val + '" placeholder="0,00">';
    }

    function carregarOdontoModal() {
        $('#cadModalOdontoInp').val('').prop('disabled', true);
        $.ajax({
            url: urlOdontoCarregar, method:'GET',
            data: { plano_id: modalState.planoId, uf: modalState.uf, cidade: modalState.cidade },
            success: function (res) {
                $('#cadModalOdontoInp').val(res.valor || '').prop('disabled', false);
                if (typeof $.fn.mask === 'function') {
                    $('#cadModalOdontoInp').mask('#.##0,00', { reverse: true });
                }
            },
            error: function () { $('#cadModalOdontoInp').prop('disabled', false); }
        });
    }

    // ── Salvar ────────────────────────────────────────────────────────────────
    $('#cadModalSave').on('click', function () {
        if (modalState.tipo === 'saude') {
            salvarFaixas();
        } else {
            salvarOdonto();
        }
    });

    function salvarFaixas() {
        $('.cad-faixa-inp').each(function () {
            if ($(this).val().trim() === '') $(this).val('0,00');
        });
        var faixas = {};
        $('.cad-faixa-inp').each(function () {
            var fi    = $(this).data('faixa');
            var campo = $(this).data('campo');
            if (!faixas[fi]) faixas[fi] = {};
            faixas[fi][campo] = $(this).val();
        });
        $('#cadModalSave').prop('disabled', true).text('Salvando...');
        $.ajax({
            url: urlFaixasSalvar, method:'POST',
            data: {
                _token:   $('meta[name="csrf-token"]').attr('content'),
                plano_id: modalState.planoId,
                uf:       modalState.uf,
                cidade:   modalState.cidade,
                faixas:   faixas,
            },
            success: function () {
                $('#cadModalOverlay').removeClass('show');
                Swal.fire({
                    icon:'success', title:'Tabela salva!',
                    html:'<span style="font-size:.9rem;color:rgba(255,255,255,.65);">' + modalState.planoNome + ' · ' + modalState.cidade + ' / ' + modalState.uf + '</span>',
                    timer:2500, timerProgressBar:true, showConfirmButton:false,
                    background:'#1a2540', color:'#e2e8f0', iconColor:'#34d399',
                    customClass:{ timerProgressBar:'swal-progress-green' }
                }).then(function () { location.reload(); });
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao salvar.';
                Swal.fire({ icon:'error', title:'Erro ao salvar', text:msg, background:'#1a2540', color:'#e2e8f0', iconColor:'#f87171', confirmButtonColor:'#4f8ef7' });
                $('#cadModalSave').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Salvar');
            }
        });
    }

    function salvarOdonto() {
        var valor = $('#cadModalOdontoInp').val().trim() || '0,00';
        $('#cadModalSave').prop('disabled', true).text('Salvando...');
        $.ajax({
            url: urlOdontoSalvar, method:'POST',
            data: {
                _token:   $('meta[name="csrf-token"]').attr('content'),
                plano_id: modalState.planoId,
                uf:       modalState.uf,
                cidade:   modalState.cidade,
                valor:    valor,
            },
            success: function () {
                $('#cadModalOverlay').removeClass('show');
                Swal.fire({
                    icon:'success', title:'Valor salvo!',
                    html:'<span style="font-size:.9rem;color:rgba(255,255,255,.65);">' + modalState.planoNome + ' · ' + modalState.cidade + ' / ' + modalState.uf + '</span>',
                    timer:2500, timerProgressBar:true, showConfirmButton:false,
                    background:'#1a2540', color:'#e2e8f0', iconColor:'#34d399',
                    customClass:{ timerProgressBar:'swal-progress-green' }
                }).then(function () { location.reload(); });
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao salvar.';
                Swal.fire({ icon:'error', title:'Erro ao salvar', text:msg, background:'#1a2540', color:'#e2e8f0', iconColor:'#f87171', confirmButtonColor:'#4f8ef7' });
                $('#cadModalSave').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Salvar');
            }
        });
    }

    // ── Fechar modal ──────────────────────────────────────────────────────────
    $('#cadModalClose, #cadModalCancel').on('click', function () {
        $('#cadModalOverlay').removeClass('show');
    });
    $('#cadModalOverlay').on('click', function (e) {
        if ($(e.target).is('#cadModalOverlay')) $(this).removeClass('show');
    });

    // ── City switcher nos cards ───────────────────────────────────────────────
    $(document).on('click', '.cad-city-btn', function () {
        var $btn    = $(this);
        var $card   = $btn.closest('.cad-card');
        var tipo    = $card.data('tipo');
        var planoId = String($card.data('plano-id'));
        var cidade  = $btn.data('cidade-nome');
        var uf      = $btn.data('cidade-uf');

        $card.find('.cad-city-btn').removeClass('active');
        $btn.addClass('active');

        // Atualiza dados do botão Editar para refletir a cidade ativa
        $card.find('.cad-btn-editar')
            .data('cidade-atual', cidade)
            .data('uf-atual', uf)
            .attr('data-cidade-atual', cidade)
            .attr('data-uf-atual', uf);

        carregarCorpoCard($card, tipo, planoId, uf, cidade);
    });

    function carregarCorpoCard($card, tipo, planoId, uf, cidade) {
        var $body = $card.find('.cad-card-body');
        $body.html('<div class="cad-card-loading">Carregando...</div>');

        if (tipo === 'saude') {
            $.ajax({
                url: urlFaixasCarregar, method:'GET',
                data: { plano_id: planoId, uf: uf, cidade: cidade },
                success: function (res) { renderSaudeCard($body, res.valores || {}); },
                error:   function () { $body.html('<div class="cad-card-loading" style="color:#fca5a5;">Erro ao carregar.</div>'); }
            });
        } else {
            $.ajax({
                url: urlOdontoCarregar, method:'GET',
                data: { plano_id: planoId, uf: uf, cidade: cidade },
                success: function (res) { renderOdontoCard($body, res.valor); },
                error:   function () { $body.html('<div class="cad-card-loading" style="color:#fca5a5;">Erro ao carregar.</div>'); }
            });
        }
    }

    function renderSaudeCard($body, valores) {
        var html = '<table class="cad-mini-tbl">';
        html += '<thead>';
        html += '<tr class="mth-group">';
        html += '<th class="mth-faixa"></th>';
        html += '<th colspan="2" class="mth-copart">Com Copart.</th>';
        html += '<th colspan="2" class="mth-sem">Sem Copart.</th>';
        html += '</tr>';
        html += '<tr class="mth-sub">';
        html += '<th class="mth-faixa">Faixa</th>';
        html += '<th class="mth-apart">Apart.</th>';
        html += '<th class="mth-enfer">Enfer.</th>';
        html += '<th class="mth-sapart">Apart.</th>';
        html += '<th class="mth-senfer">Enfer.</th>';
        html += '</tr>';
        html += '</thead><tbody>';
        for (var i = 0; i <= 9; i++) {
            var v = valores[i] || {};
            html += '<tr>';
            html += '<td>' + faixasLabels[i] + '</td>';
            html += '<td class="val-copart">' + fmtVal(v.com_copart_apart) + '</td>';
            html += '<td class="val-copart">' + fmtVal(v.com_copart_enfer) + '</td>';
            html += '<td class="val-sem">'    + fmtVal(v.sem_copart_apart) + '</td>';
            html += '<td class="val-sem">'    + fmtVal(v.sem_copart_enfer) + '</td>';
            html += '</tr>';
        }
        html += '</tbody></table>';
        $body.html(html);
    }

    function renderOdontoCard($body, valor) {
        var temValor = valor && parseFloat(valor.replace(',','.').replace(/\./g, '').replace(',', '.')) > 0;
        var html = '<div class="cad-odonto-display">';
        html += '<div class="cad-odonto-lbl">Valor mensal</div>';
        if (temValor) {
            html += '<div class="cad-odonto-val">R$ ' + valor + '</div>';
        } else {
            html += '<div class="cad-odonto-val zero">Não cadastrado</div>';
        }
        html += '</div>';
        $body.html(html);
    }

    function fmtVal(val) {
        if (!val || parseFloat(val) === 0) {
            return '<span class="val-zero">—</span>';
        }
        return parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits:2, maximumFractionDigits:2 });
    }

    // ── Carga inicial dos cards (cidade padrão) ───────────────────────────────
    $('.cad-card').each(function () {
        var $card    = $(this);
        var tipo     = $card.data('tipo');
        var planoId  = String($card.data('plano-id'));
        var $active  = $card.find('.cad-city-btn.active');
        if (!$active.length) return;
        var cidade   = $active.data('cidade-nome');
        var uf       = $active.data('cidade-uf');
        // Sincroniza data attributes do botão Editar com a cidade padrão
        $card.find('.cad-btn-editar')
            .data('cidade-atual', cidade).data('uf-atual', uf)
            .attr('data-cidade-atual', cidade).attr('data-uf-atual', uf);
        carregarCorpoCard($card, tipo, planoId, uf, cidade);
    });

});
</script>

{{-- ═══════════════════════════════════════════════════════
     Modal: Gerenciar Planos
     ═══════════════════════════════════════════════════════ --}}
<div id="planosModalOverlay" class="planos-modal-overlay">
    <div class="planos-modal-box">

        <div class="planos-modal-header">
            <span class="planos-modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#c4b5fd" style="vertical-align:-2px;margin-right:6px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                Gerenciar Planos
            </span>
            <button id="planosModalClose" class="cad-modal-close" title="Fechar">✕</button>
        </div>

        <div class="planos-modal-body">

            {{-- ── Novo plano ── --}}
            <div class="planos-novo-form">
                <input type="text" id="planosNovoNome" class="planos-novo-inp" placeholder="Nome do plano...">
                <button id="planosAdicionarBtn" class="planos-btn-add">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Adicionar
                </button>
            </div>
            <div id="planosMsgErro" class="planos-msg-erro"></div>

            {{-- ── Lista ── --}}
            <div id="planosLista" class="planos-lista">
                <div class="planos-empty">Carregando...</div>
            </div>

        </div>
    </div>
</div>

<script>
// ── Gerenciar Planos ────────────────────────────────────────────────────────
$(function () {
    var urlListar    = "{{ route('configuracoes.planos.listar') }}";
    var urlSalvar    = "{{ route('configuracoes.planos.salvar') }}";
    var csrfToken    = $('meta[name="csrf-token"]').attr('content');

    function urlAtualizar(id) { return '/configuracoes/planos/' + id + '/atualizar'; }
    function urlExcluir(id)   { return '/configuracoes/planos/' + id + '/excluir'; }

    // ── Abrir modal ──────────────────────────────────────────────────────────
    $('#btnGerenciarPlanos').on('click', function () {
        $('#planosNovoNome').val('');
        $('#planosMsgErro').hide().text('');
        $('#planosModalOverlay').addClass('show');
        carregarPlanos();
    });

    $('#planosModalClose').on('click', function () {
        $('#planosModalOverlay').removeClass('show');
    });
    $('#planosModalOverlay').on('click', function (e) {
        if ($(e.target).is('#planosModalOverlay')) $(this).removeClass('show');
    });

    // ── Carregar lista ───────────────────────────────────────────────────────
    function carregarPlanos() {
        $('#planosLista').html('<div class="planos-empty">Carregando...</div>');
        $.getJSON(urlListar, function (planos) {
            renderLista(planos);
        }).fail(function () {
            $('#planosLista').html('<div class="planos-empty" style="color:#fca5a5;">Erro ao carregar planos.</div>');
        });
    }

    function renderLista(planos) {
        if (!planos.length) {
            $('#planosLista').html('<div class="planos-empty">Nenhum plano cadastrado.</div>');
            return;
        }
        var html = '';
        $.each(planos, function (i, p) {
            html += '<div class="planos-item" data-id="' + p.id + '">';
            html += '<span class="planos-item-nome">' + $('<span>').text(p.nome).html() + '</span>';
            // Botão editar
            html += '<button class="planos-ibtn edit planos-editar-btn" title="Editar">';
            html += '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>';
            html += '</button>';
            // Botão excluir
            html += '<button class="planos-ibtn del planos-excluir-btn" title="Excluir">';
            html += '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>';
            html += '</button>';
            html += '</div>';
        });
        $('#planosLista').html(html);
    }

    // ── Adicionar plano ──────────────────────────────────────────────────────
    $('#planosAdicionarBtn').on('click', function () {
        var nome = $('#planosNovoNome').val().trim();
        $('#planosMsgErro').hide().text('');

        if (!nome) {
            $('#planosMsgErro').text('Informe o nome do plano.').show();
            $('#planosNovoNome').focus();
            return;
        }

        $('#planosAdicionarBtn').prop('disabled', true).text('Salvando...');
        $.ajax({
            url: urlSalvar, method: 'POST',
            data: { _token: csrfToken, nome: nome },
            success: function () {
                $('#planosNovoNome').val('');
                carregarPlanos();
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao salvar.';
                $('#planosMsgErro').text(msg).show();
            },
            complete: function () {
                $('#planosAdicionarBtn').prop('disabled', false).html(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg> Adicionar'
                );
            }
        });
    });

    // Enter no input de nome
    $('#planosNovoNome').on('keydown', function (e) {
        if (e.key === 'Enter') $('#planosAdicionarBtn').trigger('click');
    });

    // ── Editar plano (inline) ────────────────────────────────────────────────
    $(document).on('click', '.planos-editar-btn', function () {
        var $item = $(this).closest('.planos-item');
        var nome  = $item.find('.planos-item-nome').text().trim();

        $item.html(
            '<div class="planos-item-edit">'
            + '<input type="text" class="planos-edit-inp" value="' + $('<span>').text(nome).html() + '">'
            + '</div>'
            // Salvar
            + '<button class="planos-ibtn save planos-salvar-btn" title="Salvar">'
            + '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>'
            + '</button>'
            // Cancelar
            + '<button class="planos-ibtn cancel-edit planos-cancelar-btn" title="Cancelar">'
            + '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>'
            + '</button>'
        );
        $item.find('.planos-edit-inp').focus().select();
    });

    // Salvar edição
    $(document).on('click', '.planos-salvar-btn', function () {
        var $item = $(this).closest('.planos-item');
        var id    = $item.data('id');
        var nome  = $item.find('.planos-edit-inp').val().trim();

        if (!nome) { $item.find('.planos-edit-inp').focus(); return; }

        $.ajax({
            url: urlAtualizar(id), method: 'POST',
            data: { _token: csrfToken, nome: nome },
            success: function () { carregarPlanos(); },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao atualizar.';
                Swal.fire({ icon:'error', title:'Erro', text:msg, background:'#1a2540', color:'#e2e8f0', iconColor:'#f87171', confirmButtonColor:'#4f8ef7' });
            }
        });
    });

    // Enter na edição
    $(document).on('keydown', '.planos-edit-inp', function (e) {
        if (e.key === 'Enter') $(this).closest('.planos-item').find('.planos-salvar-btn').trigger('click');
        if (e.key === 'Escape') $(this).closest('.planos-item').find('.planos-cancelar-btn').trigger('click');
    });

    // Cancelar edição
    $(document).on('click', '.planos-cancelar-btn', function () {
        carregarPlanos();
    });

    // ── Excluir plano ────────────────────────────────────────────────────────
    $(document).on('click', '.planos-excluir-btn', function () {
        var $item = $(this).closest('.planos-item');
        var id    = $item.data('id');
        var nome  = $item.find('.planos-item-nome').text().trim();

        Swal.fire({
            title: 'Excluir plano?',
            html: 'O plano <strong>' + $('<span>').text(nome).html() + '</strong> será removido permanentemente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Excluir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#4f8ef7',
            background: '#1a2540', color: '#e2e8f0', iconColor: '#fbbf24',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: urlExcluir(id), method: 'POST',
                data: { _token: csrfToken },
                success: function () { carregarPlanos(); },
                error: function (xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Erro ao excluir.';
                    Swal.fire({ icon:'error', title:'Não foi possível excluir', text:msg, background:'#1a2540', color:'#e2e8f0', iconColor:'#f87171', confirmButtonColor:'#4f8ef7' });
                }
            });
        });
    });
});
</script>

@section('scripts')
@endsection

</x-app-layout>
