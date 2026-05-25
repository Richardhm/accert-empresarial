<x-app-layout>
    @section('css')
        <link rel="stylesheet" href="{{ asset('css/estilo-financeiro.css') }}"/>
    @endsection

    <script>
        var urlGeralEmpresarialPendentes    = "{{ route('contratos.listarEmpresarial.listarContratoEmpresaPendentes') }}";
        var empresarialFinanceiroInicializar = "{{ route('financeiro.modal.contrato.empresarial') }}";
        var urlAtualizarStatusPagamento      = "{{ route('financeiro.status.pagamento') }}";
        var urlAtualizarCampoEmpresarial     = "{{ route('contratos.empresarial.atualizar_campo') }}";
        var urlAvancarEtapa                  = "{{ route('contratos.empresarial.avancar_etapa') }}";
        var urlImportarPlanilha              = "{{ route('contratos.empresarial.importar_planilha') }}";
        var urlUploadAditivoPdf              = "{{ route('contratos.empresarial.upload_aditivo') }}";
        var urlSalvarDataAdesao              = "{{ route('contratos.empresarial.salvar_adesao') }}";
        var urlUploadAdesao                  = "{{ route('contratos.empresarial.upload_adesao') }}";
        var urlExtrairValorBoleto            = "{{ route('contratos.empresarial.extrair_valor_boleto') }}";
        var urlSalvarBoleto                  = "{{ route('contratos.empresarial.salvar_boleto') }}";
        var urlSalvarVigencia                = "{{ route('contratos.empresarial.salvar_vigencia') }}";
        var urlSalvarVigenciaColar           = "{{ route('contratos.empresarial.salvar_vigencia_colar') }}";
        var urlUploadCarteirinha             = "{{ route('contratos.empresarial.upload_carteirinha') }}";
        var urlDeletarCarteirinha            = "{{ route('contratos.empresarial.deletar_carteirinha') }}";
        var urlAtualizarContrato             = "{{ route('contratos.empresarial.atualizar') }}";
        var urlSalvarPrimeiroBoleto          = "{{ route('contratos.empresarial.salvar_primeiro_boleto') }}";
        var urlUploadDocumentoBoleto         = "{{ route('contratos.empresarial.upload_documento_boleto') }}";
        var urlSalvarFinalizado              = "{{ route('contratos.empresarial.salvar_finalizado') }}";
        var urlBeneficiarios                 = "{{ route('financeiro.beneficiarios', ['id' => '__ID__']) }}";
        var urlResumoValor                   = "{{ route('financeiro.resumo_valor', ['id' => '__ID__']) }}";
        var appAssetUrl                      = "{{ asset('') }}";
        var isAdmin = {{ auth()->check() && auth()->user()->isAdministrador() ? 'true' : 'false' }};
        var table;
        var table_individual;
        var parcelaSelecionada;
        var tableodonto;
        var tableempresarial;
    </script>

    {{-- ── Modal Empresarial de detalhes (preservado intacto) ── --}}
    <div id="myModalEmpresarial" class="fixed mx-auto inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <div class="relative w-[50%] rounded-lg shadow-3xl p-2 z-50">
            <div id="modalLoaderEmpresa" class="flex justify-center items-center h-64">
                <div class="dot-flashing"><div></div><div></div><div></div></div>
            </div>
            <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-empresarial hidden"></div>
        </div>
    </div>

    {{-- ── Modal Colar Dados (Novo Contrato) ── --}}
    <div id="modalColarDados" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalColar"></div>
        <div class="modal-colar-box">

            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title">Novo Contrato</p>
                    <p class="modal-colar-sub">Cole os dados e preencha os campos abaixo</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalColar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="modal-colar-body">

                <p class="modal-colar-format-lbl">Campos obrigatórios (qualquer ordem):</p>
                <div class="modal-colar-format-box">
                    <span class="campo-key">RAZÃO SOCIAL:</span> nome da empresa<br>
                    <span class="campo-key">CNPJ:</span> 00.000.000/0000-00<br>
                    <span class="campo-key">CONTATO:</span> nome do responsável<br>
                    <span class="campo-key">TELEFONE:</span> (00) 00000-0000<br>
                    <span class="campo-key">EMAIL:</span> email@exemplo.com
                </div>

                <form id="formColarDados">
                    @csrf

                    <textarea
                        id="textoColar"
                        name="texto_colado"
                        rows="4"
                        class="modal-colar-textarea"
                        placeholder="RAZÃO SOCIAL: Empresa Teste Ltda&#10;CNPJ: 00.000.000/0001-00&#10;CONTATO: João Silva&#10;TELEFONE: (11) 99999-0000&#10;EMAIL: joao@empresa.com"
                    ></textarea>

                    {{-- Abas Saúde / Odonto --}}
                    {{-- Corretor único — válido para Saúde e Odonto --}}
                    <label class="modal-colar-field-lbl">Corretor</label>
                    <select id="colar_user_id" class="modal-colar-select">
                        <option value="">Selecione o corretor...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <div style="margin-top:8px;">
                        <div class="modal-planos-tabs">
                            <button type="button" class="modal-tab-btn modal-tab-ativo" data-tab="saude">
                                <span class="modal-tab-dot" id="dot-saude"></span>
                                Saúde
                            </button>
                            <button type="button" class="modal-tab-btn" data-tab="odonto">
                                <span class="modal-tab-dot" id="dot-odonto"></span>
                                Odonto
                            </button>
                        </div>

                        {{-- Painel Saúde --}}
                        <div id="painel-saude" class="modal-tab-painel">
                            <div style="margin-top:10px;">
                                <label class="modal-colar-field-lbl">Plano</label>
                                <select name="saude_plano_id" id="colar_saude_plano_id" class="modal-colar-select">
                                    <option value="">Selecione o plano de saúde...</option>
                                    @foreach($planos as $p)
                                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-top:0;">
                                <label class="modal-colar-field-lbl">Coparticipação</label>
                                <select name="saude_coparticipacao" id="colar_saude_coparticipacao" class="modal-colar-select">
                                    <option value="">Selecione...</option>
                                    <option value="com">Com Coparticipação</option>
                                    <option value="sem">Sem Coparticipação</option>
                                </select>
                            </div>
                            <div class="modal-colar-grid-uf" style="margin-top:0;">
                                <div>
                                    <label class="modal-colar-field-lbl">UF</label>
                                    <select id="colar_saude_uf" name="saude_uf" class="modal-colar-select">
                                        <option value="">UF...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="modal-colar-field-lbl">Cidade</label>
                                    <select id="colar_saude_cidade" name="saude_cidade" class="modal-colar-select" disabled>
                                        <option value="">Selecione a UF primeiro...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Painel Odonto --}}
                        <div id="painel-odonto" class="modal-tab-painel" style="display:none;">
                            <div style="margin-top:10px;">
                                <label class="modal-colar-field-lbl">Plano</label>
                                <select name="odonto_plano_id" id="colar_odonto_plano_id" class="modal-colar-select">
                                    <option value="">Selecione o plano odontológico...</option>
                                    @foreach($planos as $p)
                                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-colar-grid-uf" style="margin-top:0;">
                                <div>
                                    <label class="modal-colar-field-lbl">UF</label>
                                    <select id="colar_odonto_uf" name="odonto_uf" class="modal-colar-select">
                                        <option value="">UF...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="modal-colar-field-lbl">Cidade</label>
                                    <select id="colar_odonto_cidade" name="odonto_cidade" class="modal-colar-select" disabled>
                                        <option value="">Selecione a UF primeiro...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="colarMsgErro" class="modal-colar-msg erro"></div>
                    <div id="colarMsgSucesso" class="modal-colar-msg sucesso"></div>

                    <div class="modal-colar-actions">
                        <button type="button" id="cancelarModalColar" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnCadastrarColar" class="modal-colar-btn-submit">Cadastrar Contrato</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ── Modal Importar Planilha (Etapa 1) ── --}}
    <div id="modalImportarPlanilha" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalPlanilha"></div>
        <div class="modal-colar-box">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modalPlanilhaTitulo">Importar Planilha de Beneficiários</p>
                    <p class="modal-colar-sub" id="modalPlanilhaSub">Selecione o arquivo .xlsx no formato SIAEG</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalPlanilha">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">
                <p class="modal-colar-format-lbl">Colunas esperadas na planilha:</p>
                <div class="modal-colar-format-box">
                    <span class="campo-key">Titular ou Dependente</span> &nbsp;·&nbsp;
                    <span class="campo-key">Nome Completo</span> &nbsp;·&nbsp;
                    <span class="campo-key">Nome Titular</span> &nbsp;·&nbsp;
                    <span class="campo-key">CPF</span><br>
                    <span class="campo-key">Data de Nascimento</span> &nbsp;·&nbsp;
                    <span class="campo-key">Idade</span> &nbsp;·&nbsp;
                    <span class="campo-key">Nome da Mãe</span><br>
                    <span class="campo-key">SAÚDE/Acomodação</span> &nbsp;·&nbsp;
                    <span class="campo-key">Sexo</span> &nbsp;·&nbsp;
                    <span class="campo-key">Grau do Parentesco</span><br>
                    <span class="campo-key">Data do Casamento</span> &nbsp;·&nbsp;
                    <span class="campo-key">Telefone</span> &nbsp;·&nbsp;
                    <span class="campo-key">Valor</span>
                </div>
                <form id="formImportarPlanilha" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="planilha_contrato_id" name="contrato_id" value="">
                    <input type="hidden" id="planilha_modo_edicao" name="modo_edicao" value="">
                    <input type="hidden" id="planilha_justificativa" name="justificativa_diferenca" value="">

                    <label class="modal-colar-field-lbl">Arquivo .xlsx <span style="color:#f87171;">*</span></label>
                    <input type="file" id="arquivoPlanilha" name="planilha" accept=".xlsx"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;cursor:pointer;">

                    <div id="planilhaMsgErro"   class="modal-colar-msg erro"></div>
                    <div id="planilhaMsgSucesso" class="modal-colar-msg sucesso"></div>

                    <div class="modal-colar-actions">
                        <button type="button" id="cancelarModalPlanilha" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnImportarPlanilha" class="modal-colar-btn-submit">
                            Importar Planilha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal Upload Aditivo PDF (Etapa 2) ── --}}
    <div id="modalAditivoPdf" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalAditivo"></div>
        <div class="modal-colar-box">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modalAditivoTitulo">Contrato — Upload PDF</p>
                    <p class="modal-colar-sub" id="modalAditivoSub">Selecione o arquivo PDF e informe a data do contrato</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalAditivo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">
                <form id="formAditivoPdf" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="aditivo_contrato_id" name="contrato_id" value="">
                    <input type="hidden" id="aditivo_modo_edicao" name="modo_edicao" value="">

                    <label class="modal-colar-field-lbl">Arquivo PDF <span style="color:#f87171;">*</span></label>
                    <input type="file" id="arquivoAditivo" name="aditivo" accept=".pdf"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;cursor:pointer;">

                    <label class="modal-colar-field-lbl" style="margin-top:14px;">Data do Aditivo <span style="color:#f87171;">*</span></label>
                    <input type="date" id="dataAditivoInput" name="data_aditivo"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;
                                  outline:none;color-scheme:dark;">

                    <div id="aditivoMsgErro"   class="modal-colar-msg erro"></div>
                    <div id="aditivoMsgSucesso" class="modal-colar-msg sucesso"></div>

                    <div class="modal-colar-actions">
                        <button type="button" id="cancelarModalAditivo" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnEnviarAditivo" class="modal-colar-btn-submit">Enviar PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal Adesão (Etapa 3): data + boleto PDF (valor lido automaticamente) ── --}}
    <div id="modalAdesao" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalAdesao"></div>
        <div class="modal-colar-box">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modalAdesaoTitulo">Adesão</p>
                    <p class="modal-colar-sub" id="modalAdesaoSub">O valor do boleto será lido automaticamente do PDF</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalAdesao">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">
                <form id="formAdesao" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="adesao_contrato_id" name="contrato_id" value="">
                    <input type="hidden" id="adesao_valor_planilha" value="">
                    <input type="hidden" id="adesao_modo_edicao" name="modo_edicao" value="">

                    <label class="modal-colar-field-lbl">Data de Adesão <span style="color:#f87171;">*</span></label>
                    <input type="date" id="adesaoDataInput" name="data_adesao"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;
                                  outline:none;color-scheme:dark;">

                    <label class="modal-colar-field-lbl" style="margin-top:14px;">Boleto PDF <span style="color:#f87171;">*</span></label>
                    <input type="file" id="arquivoAdesao" name="boleto_adesao" accept=".pdf"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;cursor:pointer;">

                    {{-- Estado: lendo o PDF --}}
                    <div id="adesaoLendoPdf" style="display:none;margin-top:12px;padding:10px 14px;border-radius:8px;
                         background:rgba(79,142,247,.08);border:1px solid rgba(79,142,247,.25);color:rgba(255,255,255,.55);font-size:.8rem;">
                        ⏳ Lendo valor do PDF...
                    </div>

                    {{-- Valor extraído automaticamente --}}
                    <div id="adesaoValorExtraidoWrap" style="display:none;margin-top:12px;">
                        <label class="modal-colar-field-lbl">Valor do Documento (lido automaticamente do PDF)</label>
                        <div id="adesaoValorExtraidoBox"
                             style="background:#1a2540;border:1px solid rgba(52,211,153,.3);border-radius:10px;
                                    padding:10px 14px;font-size:.95rem;font-weight:700;color:#34d399;">
                        </div>
                    </div>

                    {{-- Fallback: valor manual (quando leitura automática falha) --}}
                    <div id="adesaoValorManualWrap" style="display:none;margin-top:12px;">
                        <div style="margin-bottom:8px;padding:9px 12px;border-radius:8px;background:rgba(251,191,36,.08);
                                    border:1px solid rgba(251,191,36,.25);color:#fde68a;font-size:.78rem;">
                            ⚠️ Leitura automática não identificou o valor. Informe manualmente o valor do documento.
                        </div>
                        <label class="modal-colar-field-lbl">Valor do Boleto <span style="color:#f87171;">*</span></label>
                        <input type="text" id="adesaoBoletoValorManual" name="boleto_valor_manual" placeholder="0,00"
                               style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                      border-radius:10px;padding:10px 14px;font-size:.88rem;box-sizing:border-box;outline:none;">
                    </div>

                    {{-- Alerta de diferença de valor --}}
                    <div id="adesaoAlertaDiferenca" style="display:none;margin-top:12px;padding:10px 14px;border-radius:8px;
                         background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.35);color:#fde68a;font-size:.8rem;line-height:1.5;">
                        ⚠️ O valor do boleto difere do valor calculado na planilha
                        (<strong id="adesaoValorPlanilhaTexto"></strong>).
                        Preencha a justificativa abaixo para prosseguir.
                    </div>

                    <div id="adesaoJustificativaWrap" style="display:none;">
                        <label class="modal-colar-field-lbl" style="margin-top:12px;">Justificativa <span style="color:#f87171;">*</span></label>
                        <textarea id="adesaoJustificativa" name="justificativa_diferenca" rows="3"
                                  style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                         border-radius:10px;padding:10px 14px;font-size:.82rem;resize:vertical;
                                         box-sizing:border-box;outline:none;line-height:1.6;"
                                  placeholder="Descreva o motivo da diferença de valor..."></textarea>
                    </div>

                    <div id="adesaoMsgErro"   class="modal-colar-msg erro"></div>
                    <div id="adesaoMsgSucesso" class="modal-colar-msg sucesso"></div>

                    <div class="modal-colar-actions">
                        <button type="button" id="cancelarModalAdesao" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnEnviarAdesao" class="modal-colar-btn-submit">Confirmar Adesão</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal Vigência — Colar Dados (Etapa 5) ── --}}
    <div id="modalVigencia" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalVigencia"></div>
        <div class="modal-colar-box">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modalVigenciaTitulo">Vigência — Colar Dados</p>
                    <p class="modal-colar-sub" id="modalVigenciaSub">Cole o texto com os dados de ativação</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalVigencia">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">
                <p class="modal-colar-format-lbl">Cole os dados nesse formato:</p>
                <div class="modal-colar-format-box">
                    <span class="campo-key">1. Empresa:</span> nome da empresa<br>
                    <span class="campo-key">2. CÓDIGO:</span> SAUDE: XXXXX <span id="vigenciaFormatoOdonto" style="color:rgba(255,255,255,.4);">[ODONTO: XXXXX]</span><br>
                    <span class="campo-key">3. SENHA:</span> 000000<br>
                    <span class="campo-key">4. Vigência:</span> 15/05/2026
                </div>
                <div id="vigenciaAvisoOdonto" style="display:none;margin-top:6px;padding:6px 10px;background:rgba(147,197,253,.08);border:1px solid rgba(147,197,253,.3);border-radius:6px;font-size:.72rem;color:#93c5fd;">
                    Contrato Saúde + Odonto — o código ODONTO é <strong>obrigatório</strong>.
                </div>
                <form id="formVigencia">
                    @csrf
                    <input type="hidden" id="vigencia_contrato_id" name="contrato_id" value="">
                    <input type="hidden" id="vigencia_tipo_contrato" value="">
                    <input type="hidden" id="vigencia_modo_edicao" value="">

                    <label class="modal-colar-field-lbl">Texto Colado <span style="color:#f87171;">*</span></label>
                    <textarea
                        id="textoVigencia"
                        name="texto_colar"
                        rows="6"
                        class="modal-colar-textarea"
                        placeholder="1. Empresa: Nome da Empresa LTDA&#10;2. CÓDIGO: SAUDE: UH8XA ODONTO: SJATL&#10;3. SENHA: 643045&#10;4. Vigência: 15/05/2026"
                    ></textarea>

                    <div id="vigenciaMsgErro"    class="modal-colar-msg erro"></div>
                    <div id="vigenciaMsgSucesso" class="modal-colar-msg sucesso"></div>

                    <div class="modal-colar-actions">
                        <button type="button" id="cancelarModalVigencia" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnSalvarVigencia" class="modal-colar-btn-submit">Salvar Vigência</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal Upload Carteirinha PDF (Etapa 6) ── --}}
    <div id="modalCarteirinha" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalCarteirinha"></div>
        <div class="modal-colar-box">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modalCarteirinhaTitulo">Carteirinha — Upload PDF(s)</p>
                    <p class="modal-colar-sub" id="modalCarteirinhaSub">Selecione um ou vários arquivos PDF</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalCarteirinha">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">

                {{-- Lista de carteirinhas existentes (visível só em edit mode) --}}
                <div id="carteirinhaExistentesWrap" style="display:none;margin-bottom:14px;">
                    <p style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);font-weight:700;margin:0 0 8px;">Arquivos existentes</p>
                    <div id="carteirinhaExistentesList"></div>
                </div>

                <p class="modal-colar-format-lbl" style="margin-bottom:6px;">
                    Arquivos selecionados aparecerão listados abaixo. Novos uploads são <strong style="color:#4f8ef7;">adicionados</strong> aos anteriores, sem apagar.
                </p>
                <form id="formCarteirinha" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="carteirinha_contrato_id" name="contrato_id" value="">
                    <input type="hidden" id="carteirinha_modo_edicao" value="">

                    <label class="modal-colar-field-lbl">Arquivos PDF <span style="color:#f87171;">*</span></label>
                    <input type="file" id="arquivosCarteirinha" name="carteirinhas[]" accept="application/pdf,.pdf" multiple="multiple"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;cursor:pointer;">

                    <div id="carteirinhaListaArquivos" style="margin-top:10px;font-size:.75rem;color:rgba(255,255,255,.45);"></div>

                    <div id="carteirinhaMsgErro" class="modal-colar-msg erro"></div>

                    <div class="modal-colar-actions">
                        <button type="button" id="cancelarModalCarteirinha" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnEnviarCarteirinha" class="modal-colar-btn-submit">Enviar PDF(s)</button>
                    </div>
                </form>

                {{-- Confirmação visual após envio bem-sucedido --}}
                <div id="carteirinhaConfirmacao" style="display:none;margin-top:20px;padding:20px 14px;
                     border-radius:12px;background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.25);text-align:center;">
                    <div id="carteirinhaConfirmacaoCheck" style="margin-bottom:10px;"></div>
                    <div id="carteirinhaConfirmacaoLinks" style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin-bottom:10px;"></div>
                    <div id="carteirinhaConfirmacaoData" style="font-size:.72rem;color:rgba(255,255,255,.45);letter-spacing:.04em;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal 1º Boleto — 4 documentos PDF (Etapa 7) ── --}}
    <div id="modalPrimeiroBoleto" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalPrimeiroBoleto"></div>
        <div class="modal-colar-box">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modalPrimeiroBoletoTitulo">1º Boleto — Documentos</p>
                    <p class="modal-colar-sub" id="modalPrimeiroBoletoSub">Envie os 4 documentos para concluir esta etapa</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalPrimeiroBoleto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">

                <input type="hidden" id="primeiroBoleto_contrato_id" value="">

                {{-- Barra de progresso --}}
                <div style="margin-bottom:20px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <span style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);font-weight:700;">Progresso</span>
                        <span id="boletoProgressoText" style="font-size:.75rem;color:rgba(255,255,255,.5);">0 de 4 enviados</span>
                    </div>
                    <div style="background:rgba(255,255,255,.08);border-radius:8px;height:5px;overflow:hidden;">
                        <div id="boletoProgressoFill" style="height:5px;border-radius:8px;width:0%;background:#4f8ef7;transition:width .35s ease,background .35s;"></div>
                    </div>
                </div>

                {{-- Documento 1: Boleto Saúde --}}
                <div class="boleto-doc-row" style="padding:12px;border-radius:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);margin-bottom:8px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:22px;height:22px;border-radius:50%;background:#1a2540;border:1px solid rgba(79,142,247,.4);display:inline-flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#4f8ef7;flex-shrink:0;">1</span>
                            <span style="font-size:.83rem;font-weight:600;color:#e2e8f0;">Boleto Saúde</span>
                        </div>
                        <span id="boletoStatus_boleto_saude" style="font-size:.72rem;color:rgba(255,255,255,.3);">Não enviado</span>
                    </div>
                    <div id="boletoDownload_boleto_saude" style="display:none;margin-bottom:8px;"></div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="file" id="boletoFile_boleto_saude" data-tipo="boleto_saude" accept=".pdf" style="display:none;" class="boleto-doc-file-input">
                        <button type="button" data-tipo="boleto_saude" class="boleto-doc-upload-btn"
                                style="padding:6px 14px;border-radius:8px;background:#4f8ef7;border:none;color:#fff;font-size:.75rem;font-weight:600;cursor:pointer;transition:opacity .2s;">
                            Enviar PDF
                        </button>
                        <span id="boletoLoading_boleto_saude" style="display:none;font-size:.72rem;color:rgba(255,255,255,.4);">Enviando...</span>
                    </div>
                    <div id="boletoMsg_boleto_saude" style="display:none;margin-top:8px;font-size:.75rem;"></div>
                </div>

                {{-- Documento 2: Demonstrativo Saúde --}}
                <div class="boleto-doc-row" style="padding:12px;border-radius:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);margin-bottom:8px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:22px;height:22px;border-radius:50%;background:#1a2540;border:1px solid rgba(79,142,247,.4);display:inline-flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#4f8ef7;flex-shrink:0;">2</span>
                            <span style="font-size:.83rem;font-weight:600;color:#e2e8f0;">Demonstrativo Saúde</span>
                        </div>
                        <span id="boletoStatus_demonstrativo_saude" style="font-size:.72rem;color:rgba(255,255,255,.3);">Não enviado</span>
                    </div>
                    <div id="boletoDownload_demonstrativo_saude" style="display:none;margin-bottom:8px;"></div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="file" id="boletoFile_demonstrativo_saude" data-tipo="demonstrativo_saude" accept=".pdf" style="display:none;" class="boleto-doc-file-input">
                        <button type="button" data-tipo="demonstrativo_saude" class="boleto-doc-upload-btn"
                                style="padding:6px 14px;border-radius:8px;background:#4f8ef7;border:none;color:#fff;font-size:.75rem;font-weight:600;cursor:pointer;transition:opacity .2s;">
                            Enviar PDF
                        </button>
                        <span id="boletoLoading_demonstrativo_saude" style="display:none;font-size:.72rem;color:rgba(255,255,255,.4);">Enviando...</span>
                    </div>
                    <div id="boletoMsg_demonstrativo_saude" style="display:none;margin-top:8px;font-size:.75rem;"></div>
                </div>

                {{-- Documento 3: Boleto Odonto --}}
                <div class="boleto-doc-row" style="padding:12px;border-radius:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);margin-bottom:8px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:22px;height:22px;border-radius:50%;background:#1a2540;border:1px solid rgba(79,142,247,.4);display:inline-flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#4f8ef7;flex-shrink:0;">3</span>
                            <span style="font-size:.83rem;font-weight:600;color:#e2e8f0;">Boleto Odonto</span>
                        </div>
                        <span id="boletoStatus_boleto_odonto" style="font-size:.72rem;color:rgba(255,255,255,.3);">Não enviado</span>
                    </div>
                    <div id="boletoDownload_boleto_odonto" style="display:none;margin-bottom:8px;"></div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="file" id="boletoFile_boleto_odonto" data-tipo="boleto_odonto" accept=".pdf" style="display:none;" class="boleto-doc-file-input">
                        <button type="button" data-tipo="boleto_odonto" class="boleto-doc-upload-btn"
                                style="padding:6px 14px;border-radius:8px;background:#4f8ef7;border:none;color:#fff;font-size:.75rem;font-weight:600;cursor:pointer;transition:opacity .2s;">
                            Enviar PDF
                        </button>
                        <span id="boletoLoading_boleto_odonto" style="display:none;font-size:.72rem;color:rgba(255,255,255,.4);">Enviando...</span>
                    </div>
                    <div id="boletoMsg_boleto_odonto" style="display:none;margin-top:8px;font-size:.75rem;"></div>
                </div>

                {{-- Documento 4: Demonstrativo Odonto --}}
                <div class="boleto-doc-row" style="padding:12px;border-radius:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);margin-bottom:8px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:22px;height:22px;border-radius:50%;background:#1a2540;border:1px solid rgba(79,142,247,.4);display:inline-flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#4f8ef7;flex-shrink:0;">4</span>
                            <span style="font-size:.83rem;font-weight:600;color:#e2e8f0;">Demonstrativo Odonto</span>
                        </div>
                        <span id="boletoStatus_demonstrativo_odonto" style="font-size:.72rem;color:rgba(255,255,255,.3);">Não enviado</span>
                    </div>
                    <div id="boletoDownload_demonstrativo_odonto" style="display:none;margin-bottom:8px;"></div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="file" id="boletoFile_demonstrativo_odonto" data-tipo="demonstrativo_odonto" accept=".pdf" style="display:none;" class="boleto-doc-file-input">
                        <button type="button" data-tipo="demonstrativo_odonto" class="boleto-doc-upload-btn"
                                style="padding:6px 14px;border-radius:8px;background:#4f8ef7;border:none;color:#fff;font-size:.75rem;font-weight:600;cursor:pointer;transition:opacity .2s;">
                            Enviar PDF
                        </button>
                        <span id="boletoLoading_demonstrativo_odonto" style="display:none;font-size:.72rem;color:rgba(255,255,255,.4);">Enviando...</span>
                    </div>
                    <div id="boletoMsg_demonstrativo_odonto" style="display:none;margin-top:8px;font-size:.75rem;"></div>
                </div>

                <div style="margin-top:14px;text-align:right;">
                    <button type="button" id="cancelarModalPrimeiroBoleto" class="modal-colar-btn-cancel">Fechar</button>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Modal Finalizado (Etapa 8): data + PDF ── --}}
    <div id="modalFinalizado" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalFinalizado"></div>
        <div class="modal-colar-box">
            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title" id="modalFinalizadoTitulo">Finalizar Contrato</p>
                    <p class="modal-colar-sub" id="modalFinalizadoSub">Informe a data e envie o PDF de finalização</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalFinalizado">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-colar-body">
                <form id="formFinalizado" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="finalizado_contrato_id" name="id" value="">
                    <input type="hidden" id="finalizado_modo_edicao" name="modo_edicao" value="">

                    <label class="modal-colar-field-lbl">Data de Finalização <span style="color:#f87171;">*</span></label>
                    <input type="date" id="finalizadoDataInput" name="data_finalizado"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;
                                  outline:none;color-scheme:dark;">

                    <label class="modal-colar-field-lbl" style="margin-top:14px;">PDF Final <span style="color:#f87171;">*</span></label>
                    <input type="file" id="arquivoFinalizado" name="finalizado_pdf" accept=".pdf"
                           style="width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.12);
                                  border-radius:10px;padding:10px 14px;font-size:.82rem;box-sizing:border-box;cursor:pointer;">

                    <div id="finalizadoMsgErro"   class="modal-colar-msg erro"></div>
                    <div id="finalizadoMsgSucesso" class="modal-colar-msg sucesso"></div>

                    <div class="modal-colar-actions">
                        <button type="button" id="cancelarModalFinalizado" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnEnviarFinalizado" class="modal-colar-btn-submit">Finalizar Contrato</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal Guia de Etapas ── --}}
    <div id="modalGuiaEtapas" style="display:none;">
        <div class="modal-colar-overlay" id="overlayModalGuia"></div>
        <div class="modal-guia-box">

            <div class="modal-colar-header">
                <div>
                    <p class="modal-colar-title">Como funciona cada etapa?</p>
                    <p class="modal-colar-sub">Guia passo a passo para avançar os contratos empresariais</p>
                </div>
                <button type="button" class="modal-colar-close" id="fecharModalGuia">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="modal-guia-scroll">

                {{-- Etapa 1 --}}
                <div class="guia-step">
                    <div class="guia-step-badge">1</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">Planilha de Beneficiários</div>
                        <div class="guia-step-fazer">Clique no ícone de upload na coluna <strong style="color:#e2e8f0;">Planilha</strong> e envie o arquivo <strong style="color:#e2e8f0;">.xlsx</strong> no formato SIAEG com a lista completa de titulares e dependentes do contrato. O CNPJ da planilha deve corresponder ao contrato.</div>
                        <div class="guia-step-sistema">⚙️ O sistema lê cada beneficiário, calcula a <strong>quantidade de vidas</strong> e determina o <strong>valor total do plano</strong> automaticamente — aplicando os preços por faixa etária (0–18, 19–23 … 59+), tipo de acomodação (enfermaria ou apartamento) e coparticipação.</div>
                    </div>
                </div>

                {{-- Etapa 2 --}}
                <div class="guia-step">
                    <div class="guia-step-badge">2</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">Contrato — Upload do PDF</div>
                        <div class="guia-step-fazer">Clique no ícone de PDF na coluna <strong style="color:#e2e8f0;">Contrato</strong>, faça o upload do <strong style="color:#e2e8f0;">PDF do contrato/aditivo assinado</strong> pela empresa e informe a data de assinatura.</div>
                        <div class="guia-step-sistema">⚙️ O sistema armazena o documento e registra a data do contrato. O PDF fica disponível para download direto na tabela.</div>
                    </div>
                </div>

                {{-- Etapa 3 --}}
                <div class="guia-step">
                    <div class="guia-step-badge">3</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">Adesão — Data e Boleto</div>
                        <div class="guia-step-fazer">Clique no ícone na coluna <strong style="color:#e2e8f0;">Adesão</strong>, informe a <strong style="color:#e2e8f0;">data de adesão</strong> e envie o <strong style="color:#e2e8f0;">PDF do boleto de adesão</strong>.</div>
                        <div class="guia-step-sistema">⚙️ O sistema extrai o valor do boleto automaticamente do PDF e compara com o valor calculado na planilha. Se houver diferença, será necessário preencher uma <strong>justificativa</strong>. Contratos com divergência ficam marcados com ⚠️ na tabela.</div>
                    </div>
                </div>

                {{-- Etapa 4 --}}
                <div class="guia-step">
                    <div class="guia-step-badge">4</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">Vencimento do Boleto</div>
                        <div class="guia-step-fazer">Clique no ícone na coluna <strong style="color:#e2e8f0;">Vencimento</strong> e preencha a <strong style="color:#e2e8f0;">data de vencimento</strong>, a <strong style="color:#e2e8f0;">forma de pagamento</strong> (Boleto, PIX, Débito Automático ou Cartão) e o <strong style="color:#e2e8f0;">oriundo</strong> (Accert ou Vivaz).</div>
                        <div class="guia-step-sistema">⚙️ O sistema registra as informações de pagamento vinculadas ao contrato. Esses dados ficam visíveis na tabela ao passar o cursor pela coluna Vencimento.</div>
                    </div>
                </div>

                {{-- Etapa 5 --}}
                <div class="guia-step">
                    <div class="guia-step-badge">5</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">Vigência — Dados de Ativação</div>
                        <div class="guia-step-fazer">Clique no ícone na coluna <strong style="color:#e2e8f0;">Vigência</strong> e cole o texto com os dados de ativação enviados pela operadora. O texto deve conter o <strong style="color:#e2e8f0;">código Saúde</strong>, <strong style="color:#e2e8f0;">código Odonto</strong> (se houver), <strong style="color:#e2e8f0;">senha</strong> e <strong style="color:#e2e8f0;">data de vigência</strong>.</div>
                        <div class="guia-step-sistema">⚙️ O sistema interpreta o texto colado e extrai automaticamente os códigos de ativação e a data de início da vigência do plano — sem necessidade de preencher campo a campo.</div>
                    </div>
                </div>

                {{-- Etapa 6 --}}
                <div class="guia-step">
                    <div class="guia-step-badge">6</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">Carteiras — Upload das Carteirinhas</div>
                        <div class="guia-step-fazer">Clique no ícone na coluna <strong style="color:#e2e8f0;">Carteiras</strong> e envie os <strong style="color:#e2e8f0;">PDFs das carteirinhas</strong> do plano. É possível selecionar <strong style="color:#e2e8f0;">múltiplos arquivos de uma vez</strong>, e novos envios são acumulados sem apagar os anteriores.</div>
                        <div class="guia-step-sistema">⚙️ O sistema armazena todos os PDFs e registra a data de envio. Os links para download ficam disponíveis diretamente na coluna Carteiras da tabela.</div>
                    </div>
                </div>

                {{-- Etapa 7 --}}
                <div class="guia-step">
                    <div class="guia-step-badge">7</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4f8ef7" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">1º Boleto — Quatro Documentos</div>
                        <div class="guia-step-fazer">Clique no ícone na coluna <strong style="color:#e2e8f0;">1º Boleto</strong> e envie os <strong style="color:#e2e8f0;">4 documentos</strong> obrigatórios um a um: <strong style="color:#e2e8f0;">Boleto Saúde</strong>, <strong style="color:#e2e8f0;">Demonstrativo Saúde</strong>, <strong style="color:#e2e8f0;">Boleto Odonto</strong> e <strong style="color:#e2e8f0;">Demonstrativo Odonto</strong>.</div>
                        <div class="guia-step-sistema">⚙️ Uma barra de progresso mostra quantos documentos foram enviados (0 de 4). A etapa só é concluída quando <strong>todos os 4 PDFs</strong> forem enviados com sucesso.</div>
                    </div>
                </div>

                {{-- Etapa 8 --}}
                <div class="guia-step">
                    <div class="guia-step-badge" style="background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.35);color:#f59e0b;">8</div>
                    <div class="guia-step-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f59e0b" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"/></svg>
                    </div>
                    <div class="guia-step-content">
                        <div class="guia-step-title">Finalizado — Encerramento do Contrato</div>
                        <div class="guia-step-fazer">Clique no ícone na coluna <strong style="color:#e2e8f0;">Finalizado</strong>, informe a <strong style="color:#e2e8f0;">data de finalização</strong> e envie o <strong style="color:#e2e8f0;">PDF final do contrato</strong>.</div>
                        <div class="guia-step-sistema">⚙️ O contrato é marcado como <strong>Finalizado</strong> e sai da lista de pendentes. O documento final fica disponível para download e o registro completo é mantido no histórico.</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Modal Detalhe do Contrato ── --}}
    <div id="modalDetalheContrato" style="display:none;position:fixed;inset:0;z-index:9000;align-items:center;justify-content:center;">
        <div style="position:absolute;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);" id="overlayModalDetalhe"></div>
        <div style="position:relative;z-index:1;width:100%;max-width:780px;max-height:90vh;margin:auto;
                    background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:16px;
                    display:flex;flex-direction:column;overflow:hidden;box-shadow:0 24px 60px rgba(0,0,0,.6);">
            {{-- Header --}}
            <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 16px;
                        border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0;">
                <div>
                    <p id="detalheModalTitulo" style="margin:0;font-size:1rem;font-weight:700;color:#f1f5f9;"></p>
                    <p id="detalheModalSub"    style="margin:4px 0 0;font-size:.75rem;color:rgba(255,255,255,.4);"></p>
                </div>
                <button id="fecharModalDetalhe" style="background:none;border:none;color:rgba(255,255,255,.5);cursor:pointer;padding:2px;line-height:0;flex-shrink:0;margin-left:16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            {{-- Body (scrollável) --}}
            <div id="detalheModalBody" style="overflow-y:auto;padding:20px 24px 24px;flex:1;"></div>
        </div>
    </div>

    {{-- ── Modal Editar Contrato ── --}}
    <div id="modalEditarContrato" style="display:none;position:fixed;inset:0;z-index:9100;align-items:center;justify-content:center;">
        <div style="position:absolute;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);" id="overlayModalEditar"></div>
        <div style="position:relative;z-index:1;width:100%;max-width:620px;max-height:92vh;margin:auto;
                    background:#111827;border:1px solid rgba(255,255,255,.1);border-radius:16px;
                    display:flex;flex-direction:column;overflow:hidden;box-shadow:0 24px 60px rgba(0,0,0,.6);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 16px;
                        border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0;">
                <div>
                    <p style="margin:0;font-size:1rem;font-weight:700;color:#f1f5f9;">Editar Contrato</p>
                    <p id="editarContratoSub" style="margin:4px 0 0;font-size:.75rem;color:rgba(255,255,255,.4);"></p>
                </div>
                <button id="fecharModalEditar" style="background:none;border:none;color:rgba(255,255,255,.5);cursor:pointer;padding:2px;line-height:0;flex-shrink:0;margin-left:16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div style="overflow-y:auto;padding:20px 24px 24px;flex:1;">
                <form id="formEditarContrato">
                    @csrf
                    <input type="hidden" id="editar_contrato_id" name="id">
                    <input type="hidden" id="editar_tem_saude"  name="tem_saude"  value="0">
                    <input type="hidden" id="editar_tem_odonto" name="tem_odonto" value="0">

                    {{-- Dados básicos --}}
                    <p style="font-size:.65rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);font-weight:700;margin:0 0 10px;">Dados da Empresa</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
                        <div>
                            <label class="modal-colar-field-lbl">Razão Social</label>
                            <input type="text" id="editar_razao_social" name="razao_social" class="modal-colar-select" style="padding:9px 12px;">
                        </div>
                        <div>
                            <label class="modal-colar-field-lbl">CNPJ</label>
                            <input type="text" id="editar_cnpj" name="cnpj" class="modal-colar-select" style="padding:9px 12px;">
                        </div>
                        <div>
                            <label class="modal-colar-field-lbl">Responsável</label>
                            <input type="text" id="editar_responsavel" name="responsavel" class="modal-colar-select" style="padding:9px 12px;">
                        </div>
                        <div>
                            <label class="modal-colar-field-lbl">Celular</label>
                            <input type="text" id="editar_celular" name="celular" class="modal-colar-select" style="padding:9px 12px;">
                        </div>
                        <div style="grid-column:1/-1;">
                            <label class="modal-colar-field-lbl">E-mail</label>
                            <input type="text" id="editar_email" name="email" class="modal-colar-select" style="padding:9px 12px;">
                        </div>
                    </div>

                    {{-- Toggles de tipo --}}
                    <p style="font-size:.65rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);font-weight:700;margin:0 0 8px;">Tipo de Plano</p>
                    <div style="display:flex;gap:8px;margin-bottom:4px;">
                        <button type="button" id="toggleSaudeBtn"
                            style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;
                                   border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.06);
                                   color:rgba(52,211,153,.5);font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px;"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>
                            Saúde
                        </button>
                        <button type="button" id="toggleOdontoBtn"
                            style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;
                                   border:1px solid rgba(147,197,253,.3);background:rgba(147,197,253,.06);
                                   color:rgba(147,197,253,.5);font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px;"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/></svg>
                            Odonto
                        </button>
                    </div>
                    <p style="font-size:.7rem;color:rgba(255,255,255,.3);margin:0 0 4px;">Clique para ativar/desativar cada plano.</p>

                    {{-- Saúde --}}
                    <div id="editarSaudeSection" style="display:none;margin-top:14px;padding:14px;border-radius:12px;background:rgba(52,211,153,.04);border:1px solid rgba(52,211,153,.15);">
                        <p style="font-size:.65rem;text-transform:uppercase;letter-spacing:.08em;
                                  color:#34d399;font-weight:700;margin:0 0 10px;">Plano de Saúde</p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div style="grid-column:1/-1;">
                                <label class="modal-colar-field-lbl">Plano</label>
                                <select name="plano_saude_id" id="editar_saude_plano_id" class="modal-colar-select">
                                    <option value="">Selecione o plano de saúde...</option>
                                    @foreach($planos as $p)
                                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="modal-colar-field-lbl">Coparticipação</label>
                                <select name="saude_coparticipacao" id="editar_saude_coparticipacao" class="modal-colar-select">
                                    <option value="">Selecione...</option>
                                    <option value="com">Com Coparticipação</option>
                                    <option value="sem">Sem Coparticipação</option>
                                </select>
                            </div>
                            <div>
                                <label class="modal-colar-field-lbl">UF</label>
                                <select id="editar_saude_uf" name="saude_uf" class="modal-colar-select">
                                    <option value="">UF...</option>
                                </select>
                            </div>
                            <div>
                                <label class="modal-colar-field-lbl">Cidade</label>
                                <select id="editar_saude_cidade" name="saude_cidade" class="modal-colar-select" disabled>
                                    <option value="">Selecione a UF primeiro...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Odonto --}}
                    <div id="editarOdontoSection" style="display:none;margin-top:10px;padding:14px;border-radius:12px;background:rgba(147,197,253,.04);border:1px solid rgba(147,197,253,.15);">
                        <p style="font-size:.65rem;text-transform:uppercase;letter-spacing:.08em;
                                  color:#93c5fd;font-weight:700;margin:0 0 10px;">Plano Odontológico</p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div style="grid-column:1/-1;">
                                <label class="modal-colar-field-lbl">Plano</label>
                                <select name="plano_odonto_id" id="editar_odonto_plano_id" class="modal-colar-select">
                                    <option value="">Selecione o plano odontológico...</option>
                                    @foreach($planos as $p)
                                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="modal-colar-field-lbl">UF</label>
                                <select id="editar_odonto_uf" name="odonto_uf" class="modal-colar-select">
                                    <option value="">UF...</option>
                                </select>
                            </div>
                            <div>
                                <label class="modal-colar-field-lbl">Cidade</label>
                                <select id="editar_odonto_cidade" name="odonto_cidade" class="modal-colar-select" disabled>
                                    <option value="">Selecione a UF primeiro...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="editarMsgErro"   class="modal-colar-msg erro"    style="margin-top:14px;"></div>
                    <div id="editarMsgSucesso" class="modal-colar-msg sucesso" style="margin-top:14px;"></div>

                    <div class="modal-colar-actions" style="margin-top:18px;">
                        <button type="button" id="cancelarModalEditar" class="modal-colar-btn-cancel">Cancelar</button>
                        <button type="submit" id="btnSalvarEditar" class="modal-colar-btn-submit">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Overlay de Transição entre Etapas ── --}}
    <div id="et-overlay">
        <div class="et-box">
            <button id="et-fechar" title="Fechar" style="position:absolute;top:12px;right:14px;background:none;border:none;cursor:pointer;color:rgba(255,255,255,.35);line-height:0;padding:4px;transition:color .2s;" onmouseover="this.style.color='rgba(255,255,255,.8)'" onmouseout="this.style.color='rgba(255,255,255,.35)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="et-check">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:28px;height:28px;">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.06-1.06l-3.31 3.31-1.48-1.48a.75.75 0 0 0-1.06 1.06l2.01 2.01a.75.75 0 0 0 1.06 0l3.84-3.84Z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="et-titulo">Etapa <span id="et-nome-conc"></span> concluída!</div>
            <div class="et-proxima-lbl" id="et-proxima"></div>
        </div>
    </div>

    {{-- ── Page ── --}}
    <div class="fin-page">
        <div class="fin-inner">

            {{-- Header --}}
            <div class="fin-header">
                <div>
                    <h1 class="fin-title">Financeiro</h1>
                    <p class="fin-sub">Gestão de contratos empresariais</p>
                </div>
                <button type="button" id="btnNovoContrato" class="fin-btn-new">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Novo Contrato
                </button>
            </div>

            {{-- Conteúdo principal --}}
            <section>
                <x-aba-empresarial></x-aba-empresarial>
            </section>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#valor').mask('#.##0,00', {reverse: true});

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // ── Modal Guia de Etapas ──
            $(document).on('click', '#btnGuiaEtapas', function () { $('#modalGuiaEtapas').show(); });
            $('#fecharModalGuia, #overlayModalGuia').on('click', function () { $('#modalGuiaEtapas').hide(); });

            // ── Modal Detalhe do Contrato ─────────────────────────────────────
            function fecharModalDetalhe() {
                $('#modalDetalheContrato').css('display', 'none');
            }
            $('#fecharModalDetalhe, #overlayModalDetalhe').on('click', fecharModalDetalhe);

            function fmtMoeda(v) {
                return 'R$ ' + parseFloat(v || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function dlLink(path, label) {
                if (!path) return '';
                var base = typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/';
                var svgDl = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f87171" style="width:13px;height:13px;flex-shrink:0;">'
                    + '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>';
                return '<a href="' + base + path + '" target="_blank" '
                    + 'style="display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;'
                    + 'background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.22);'
                    + 'color:#f87171;font-size:.72rem;text-decoration:none;white-space:nowrap;">'
                    + svgDl + label + '</a>';
            }

            function infoChip(label, value, color) {
                if (!value) return '';
                return '<div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:10px 14px;">'
                    + '<p style="margin:0 0 2px;font-size:.65rem;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35);font-weight:700;">' + label + '</p>'
                    + '<p style="margin:0;font-size:.82rem;font-weight:600;color:' + (color || '#e2e8f0') + ';">' + value + '</p>'
                    + '</div>';
            }

            function etapaRow(num, nome, etapaAtual, dateStr, arquivos, extra) {
                var done    = etapaAtual >= num;
                var current = etapaAtual === num - 1;
                var dotColor = done ? '#34d399' : (current ? '#4f8ef7' : 'rgba(255,255,255,.15)');
                var badge = '<span style="width:22px;height:22px;border-radius:50%;background:' + (done ? 'rgba(52,211,153,.15)' : 'rgba(255,255,255,.05)')
                    + ';border:1px solid ' + dotColor + ';display:inline-flex;align-items:center;justify-content:center;'
                    + 'font-size:.62rem;font-weight:700;color:' + dotColor + ';flex-shrink:0;">' + num + '</span>';

                var status = done
                    ? '<span style="font-size:.7rem;color:#34d399;font-weight:600;">✓ Concluída</span>'
                    : (current
                        ? '<span style="font-size:.7rem;color:#4f8ef7;font-weight:600;">→ Em andamento</span>'
                        : '<span style="font-size:.7rem;color:rgba(255,255,255,.25);">Bloqueada</span>');

                var dateHtml = dateStr ? '<span style="font-size:.72rem;color:rgba(255,255,255,.4);">' + dateStr + '</span>' : '';

                var arquivosHtml = '';
                if (arquivos && arquivos.length) {
                    arquivosHtml = '<div style="display:flex;flex-wrap:wrap;gap:5px;margin-top:6px;">';
                    arquivos.forEach(function (a) { arquivosHtml += dlLink(a.path, a.label); });
                    arquivosHtml += '</div>';
                }

                var extraHtml = extra ? '<div style="margin-top:5px;font-size:.75rem;color:rgba(255,255,255,.5);">' + extra + '</div>' : '';

                return '<div style="display:flex;gap:12px;padding:12px 14px;border-radius:10px;background:'
                    + (done ? 'rgba(52,211,153,.04)' : 'rgba(255,255,255,.02)')
                    + ';border:1px solid rgba(255,255,255,.06);margin-bottom:6px;">'
                    + '<div style="display:flex;flex-direction:column;align-items:center;gap:4px;flex-shrink:0;">'
                    + badge
                    + (num < 8 ? '<div style="width:1px;flex:1;background:rgba(255,255,255,.08);min-height:10px;"></div>' : '')
                    + '</div>'
                    + '<div style="flex:1;min-width:0;">'
                    + '<div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;">'
                    + '<span style="font-size:.82rem;font-weight:600;color:' + (done ? '#e2e8f0' : 'rgba(255,255,255,.35)') + ';">' + nome + '</span>'
                    + '<div style="display:flex;align-items:center;gap:8px;">' + dateHtml + status + '</div>'
                    + '</div>'
                    + arquivosHtml + extraHtml
                    + '</div>'
                    + '</div>';
            }

            $(document).on('click', '.btn-detalhe-contrato', function () {
                var id  = $(this).data('id');
                var row = tableempresarial.row($(this).closest('tr')).data();
                if (!row) return;

                var etapa = parseInt(row.etapa_atual) || 0;
                var tipo  = row.tipo_contrato || '';

                // Tipo label
                var tipoLabel = tipo === 'ambos' ? 'Saúde + Odonto' : (tipo === 'saude' ? 'Saúde' : 'Odonto');
                var tipoColor = tipo === 'saude' ? '#34d399' : (tipo === 'odonto' ? '#93c5fd' : '#a78bfa');

                // Valor
                var valorHtml = '';
                if (tipo === 'ambos') {
                    valorHtml = '<span style="color:#34d399;">' + fmtMoeda(row.valor_saude) + '</span>'
                        + ' <span style="color:rgba(255,255,255,.3);">+</span> '
                        + '<span style="color:#93c5fd;">' + fmtMoeda(row.valor_odonto) + '</span>';
                } else if (tipo === 'saude') {
                    valorHtml = '<span style="color:#34d399;">' + fmtMoeda(row.valor_saude) + '</span>';
                } else {
                    valorHtml = '<span style="color:#93c5fd;">' + fmtMoeda(row.valor_odonto) + '</span>';
                }

                // Código(s)
                var codigos = [];
                if (row.codigo_saude)  codigos.push('<span style="color:#34d399;">' + row.codigo_saude + '</span>');
                if (row.codigo_odonto) codigos.push('<span style="color:#93c5fd;">' + row.codigo_odonto + '</span>');

                // ── Seção Resumo ──
                var resumo = '<p style="margin:0 0 10px;font-size:.65rem;text-transform:uppercase;letter-spacing:.08em;'
                    + 'color:rgba(255,255,255,.3);font-weight:700;">Resumo do Contrato</p>'
                    + '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:20px;">'
                    + infoChip('CNPJ',           row.cnpj)
                    + infoChip('Tipo',            tipoLabel, tipoColor)
                    + infoChip('Plano',           row.plano)
                    + infoChip('Cidade / UF',     (row.cidade || '') + (row.uf ? ' / ' + row.uf : ''))
                    + infoChip('Vidas',           row.quantidade_vidas || '-')
                    + infoChip('Valor',           '', '') // placeholder — vamos substituir abaixo
                    + infoChip('Corretor',        row.usuario)
                    + infoChip('Cadastrado por',  row.cadastrado_por_nome)
                    + infoChip('Data Cadastro',   row.created_at)
                    + (codigos.length ? infoChip('Código(s)', codigos.join(' / '), '') : '')
                    + '</div>';

                // Substituir chip de Valor vazio com o HTML formatado
                resumo = resumo.replace(
                    infoChip('Valor', '', ''),
                    '<div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:10px 14px;">'
                    + '<p style="margin:0 0 2px;font-size:.65rem;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35);font-weight:700;">Valor</p>'
                    + '<p style="margin:0;font-size:.82rem;font-weight:600;">' + valorHtml + '</p>'
                    + '</div>'
                );

                // ── Etapas ──
                var carteirinhas = [];
                try { carteirinhas = JSON.parse(row.carteirinha_paths || '[]'); } catch(e) {}

                var etapasHtml = '<p style="margin:0 0 10px;font-size:.65rem;text-transform:uppercase;letter-spacing:.08em;'
                    + 'color:rgba(255,255,255,.3);font-weight:700;">Etapas</p>';

                // 1 — Planilha
                etapasHtml += etapaRow(1, 'Planilha de Beneficiários', etapa, '',
                    row.planilha_path ? [{ path: row.planilha_path, label: 'Planilha' }] : [],
                    row.quantidade_vidas ? row.quantidade_vidas + ' vidas' : '');

                // 2 — Aditivo
                etapasHtml += etapaRow(2, 'Aditivo / Contrato PDF', etapa, row.data_aditivo || '',
                    row.aditivo_path ? [{ path: row.aditivo_path, label: 'Aditivo PDF' }] : []);

                // 3 — Adesão
                var extraAdesao = '';
                if (row.boleto_adesao_valor) extraAdesao += 'Boleto: ' + fmtMoeda(row.boleto_adesao_valor);
                if (parseInt(row.tem_diferenca_valor) === 1 && row.justificativa_diferenca)
                    extraAdesao += (extraAdesao ? ' — ' : '') + '⚠ ' + row.justificativa_diferenca;
                etapasHtml += etapaRow(3, 'Adesão — Boleto', etapa, row.data_adesao || '',
                    row.boleto_adesao_path ? [{ path: row.boleto_adesao_path, label: 'Boleto Adesão' }] : [],
                    extraAdesao);

                // 4 — PG Boleto
                var extraPgto = [row.forma_pagamento, row.oriundo].filter(Boolean).join(' · ');
                etapasHtml += etapaRow(4, 'Pagamento do Boleto', etapa, row.data_pgto || '', [], extraPgto);

                // 5 — Vigência
                var extraVig = [];
                if (row.codigo_saude)  extraVig.push('<span style="color:#34d399;">Saúde: ' + row.codigo_saude + '</span>');
                if (row.codigo_odonto) extraVig.push('<span style="color:#93c5fd;">Odonto: ' + row.codigo_odonto + '</span>');
                etapasHtml += etapaRow(5, 'Vigência — Código(s)', etapa, row.data_vigencia || '', [], extraVig.join(' &nbsp;·&nbsp; '));

                // 6 — Carteirinhas
                var cartArquivos = carteirinhas.map(function (p, i) { return { path: p, label: 'Cart. ' + (i + 1) }; });
                etapasHtml += etapaRow(6, 'Carteirinhas', etapa, row.data_carteirinha || '', cartArquivos,
                    carteirinhas.length ? carteirinhas.length + ' arquivo(s)' : '');

                // 7 — 1º Boleto
                var boleto7 = [];
                if (row.boleto_saude_path)        boleto7.push({ path: row.boleto_saude_path,        label: 'Boleto Saúde' });
                if (row.demonstrativo_saude_path)  boleto7.push({ path: row.demonstrativo_saude_path,  label: 'Dem. Saúde' });
                if (row.boleto_odonto_path)        boleto7.push({ path: row.boleto_odonto_path,        label: 'Boleto Odonto' });
                if (row.demonstrativo_odonto_path) boleto7.push({ path: row.demonstrativo_odonto_path, label: 'Dem. Odonto' });
                var extraBol7 = [];
                if (row.primeiro_boleto_valor)      extraBol7.push(fmtMoeda(row.primeiro_boleto_valor));
                if (row.primeiro_boleto_vencimento) extraBol7.push('Venc. ' + row.primeiro_boleto_vencimento);
                etapasHtml += etapaRow(7, '1º Boleto', etapa, row.data_primeiro_boleto || '', boleto7, extraBol7.join(' · '));

                // 8 — Finalizado
                etapasHtml += etapaRow(8, 'Finalizado', etapa, row.data_baixa_finalizado || '',
                    row.finalizado_pdf_path ? [{ path: row.finalizado_pdf_path, label: 'PDF Final' }] : []);

                // Monta tudo
                $('#detalheModalTitulo').text(row.razao_social || 'Contrato #' + id);
                $('#detalheModalSub').text('CNPJ ' + (row.cnpj || '') + '  ·  Etapa ' + etapa + ' de 8');
                $('#detalheModalBody').html(resumo + etapasHtml);
                $('#modalDetalheContrato').css('display', 'flex');
            });

            // ── Modal Editar Contrato ─────────────────────────────────────────
            function fecharModalEditar() {
                $('#modalEditarContrato').css('display', 'none');
            }
            $('#fecharModalEditar, #cancelarModalEditar, #overlayModalEditar').on('click', fecharModalEditar);

            function setToggle(tipo, ativo) {
                var isS = (tipo === 'saude');
                var $btn = isS ? $('#toggleSaudeBtn') : $('#toggleOdontoBtn');
                var $sec = isS ? $('#editarSaudeSection') : $('#editarOdontoSection');
                var $hid = isS ? $('#editar_tem_saude')  : $('#editar_tem_odonto');
                var corA = isS ? '#34d399' : '#93c5fd';
                var corI = isS ? 'rgba(52,211,153,.5)' : 'rgba(147,197,253,.5)';
                var bgA  = isS ? 'rgba(52,211,153,.15)' : 'rgba(147,197,253,.15)';
                var bgI  = isS ? 'rgba(52,211,153,.06)' : 'rgba(147,197,253,.06)';
                var bdA  = isS ? 'rgba(52,211,153,.5)' : 'rgba(147,197,253,.5)';
                var bdI  = isS ? 'rgba(52,211,153,.3)' : 'rgba(147,197,253,.3)';
                $btn.css({ color: ativo ? corA : corI, background: ativo ? bgA : bgI, border: '1px solid ' + (ativo ? bdA : bdI) });
                $sec.toggle(ativo);
                $hid.val(ativo ? '1' : '0');
            }

            function popularUFsCidade(ufSelectId, cidadeSelectId, ufVal, cidadeVal) {
                if (window._estadosCidades) {
                    var opts = '<option value="">UF...</option>';
                    $.each(window._estadosCidades, function (i, e) {
                        opts += '<option value="' + e.sigla + '">' + e.sigla + '</option>';
                    });
                    $(ufSelectId).html(opts).val(ufVal || '');
                    if (ufVal && window._carregarCidades) {
                        window._carregarCidades($(ufSelectId), $(cidadeSelectId), cidadeVal || '');
                    }
                } else {
                    $(ufSelectId).val(ufVal || '');
                }
            }

            function abrirModalEditar(row) {
                $('#editar_contrato_id').val(row.id);
                $('#editarContratoSub').text(row.cnpj || '');
                $('#editar_razao_social').val(row.razao_social || '');
                $('#editar_cnpj').val(row.cnpj || '');
                $('#editar_responsavel').val(row.responsavel || '');
                $('#editar_celular').val(row.fone || row.celular || '');
                $('#editar_email').val(row.email || '');
                $('#editarMsgErro').hide().text('');
                $('#editarMsgSucesso').hide().text('');
                $('#btnSalvarEditar').prop('disabled', false).text('Salvar Alterações');

                var tipo      = row.tipo_contrato || '';
                // Se tipo_contrato é null/vazio (contrato importado sem tipo definido): ativa ambos
                var temSaude  = !tipo || tipo === 'saude'  || tipo === 'ambos';
                var temOdonto = !tipo || tipo === 'odonto' || tipo === 'ambos';

                setToggle('saude',  temSaude);
                setToggle('odonto', temOdonto);

                // Pré-popular campos Saúde
                $('#editar_saude_plano_id').val(row.plano_saude_id || '');
                $('#editar_saude_coparticipacao').val(row.saude_coparticipacao || '');
                popularUFsCidade('#editar_saude_uf', '#editar_saude_cidade',
                    row.saude_uf || row.uf || '', row.saude_cidade || row.cidade || '');

                // Pré-popular campos Odonto
                $('#editar_odonto_plano_id').val(row.plano_odonto_id || '');
                popularUFsCidade('#editar_odonto_uf', '#editar_odonto_cidade',
                    row.odonto_uf || row.uf || '', row.odonto_cidade || row.cidade || '');

                $('#modalEditarContrato').css('display', 'flex');
            }

            // Toggles Saúde / Odonto
            $('#toggleSaudeBtn').on('click', function () {
                var ativo = $('#editar_tem_saude').val() !== '1';
                setToggle('saude', ativo);
            });
            $('#toggleOdontoBtn').on('click', function () {
                var ativo = $('#editar_tem_odonto').val() !== '1';
                setToggle('odonto', ativo);
            });

            $(document).on('click', '.btn-editar-contrato', function () {
                var row = tableempresarial.row($(this).closest('tr')).data();
                if (row) abrirModalEditar(row);
            });

            $('#formEditarContrato').on('submit', function (e) {
                e.preventDefault();
                $('#editarMsgErro').hide().text('');
                if ($('#editar_tem_saude').val() !== '1' && $('#editar_tem_odonto').val() !== '1') {
                    $('#editarMsgErro').text('Selecione ao menos um tipo de plano (Saúde ou Odonto).').show();
                    return;
                }
                var fd = new FormData(this);
                $('#btnSalvarEditar').prop('disabled', true).text('Salvando...');
                $.ajax({
                    url: urlAtualizarContrato,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function () {
                        $('#editarMsgSucesso').text('Contrato atualizado com sucesso!').show();
                        tableempresarial && tableempresarial.ajax.reload(null, false);
                        setTimeout(fecharModalEditar, 1200);
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Erro ao salvar.';
                        $('#editarMsgErro').text(msg).show();
                        $('#btnSalvarEditar').prop('disabled', false).text('Salvar Alterações');
                    }
                });
            });

            // ── Excluir contrato ──
            $("body").on('click', '.excluir_contrato', function () {
                let id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Você não poderá reverter esta ação!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('contratos.excluir') }}",
                            data: { id: id },
                            method: "POST",
                            success: function () {
                                Swal.fire('Excluído!', 'O contrato foi excluído com sucesso.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function () {
                                Swal.fire('Erro!', 'Houve um problema ao excluir o contrato.', 'error');
                            }
                        });
                    }
                });
            });

            // ── Modal Colar Dados ──
            var urlColarEmpresarial = "{{ route('contratos.colar.empresarial') }}";

            function abrirModalColar() {
                $('#modalColarDados').show();
            }

            function fecharModalColar() {
                $('#modalColarDados').hide();
                $('#textoColar').val('');
                $('#colar_user_id').val('');
                // Saúde
                $('#colar_saude_plano_id').val('');
                $('#colar_saude_coparticipacao').val('');
                $('#colar_saude_uf').val('');
                $('#colar_saude_cidade').html('<option value="">Selecione a UF primeiro...</option>').prop('disabled', true);
                $('#dot-saude').removeClass('modal-tab-dot-ativo');
                // Odonto
                $('#colar_odonto_plano_id').val('');
                $('#colar_odonto_uf').val('');
                $('#colar_odonto_cidade').html('<option value="">Selecione a UF primeiro...</option>').prop('disabled', true);
                $('#dot-odonto').removeClass('modal-tab-dot-ativo');
                // Reset to saúde tab
                $('.modal-tab-btn').removeClass('modal-tab-ativo');
                $('.modal-tab-btn[data-tab="saude"]').addClass('modal-tab-ativo');
                $('#painel-saude').show();
                $('#painel-odonto').hide();
                $('#colarMsgErro').hide().text('');
                $('#colarMsgSucesso').hide().text('');
                $('#btnCadastrarColar').prop('disabled', false).text('Cadastrar Contrato');
            }

            $('#btnNovoContrato').on('click', abrirModalColar);
            $('#fecharModalColar').on('click', fecharModalColar);
            $('#cancelarModalColar').on('click', fecharModalColar);
            $('#overlayModalColar').on('click', fecharModalColar);

            // ── Cidades restritas por UF ────────────────────────────────────
            // Para limitar as cidades de uma UF, adicione a sigla e o array aqui.
            // Deixe a UF fora do objeto para exibir todas as cidades do JSON.
            var cidadesRestritas = {
                'GO': ['Anápolis', 'Goiânia', 'Rio Verde']
                // 'SP': ['São Paulo', 'Campinas'],  ← exemplo para adicionar outra UF
            };

            // UF → Cidade cascade (carrega JSON uma vez, reutilizável para todos os modals)
            $.getJSON("{{ asset('js/estados_cidades.json') }}", function (estadosCidades) {
                window._estadosCidades = estadosCidades;

                var ufOptions = '<option value="">UF...</option>';
                $.each(estadosCidades, function (i, estado) {
                    ufOptions += '<option value="' + estado.sigla + '">' + estado.sigla + '</option>';
                });
                $('#colar_saude_uf, #colar_odonto_uf, #editar_saude_uf, #editar_odonto_uf').html(ufOptions);

                window._carregarCidades = function (ufSelect, cidadeSelect, cidadeAtual) {
                    var uf = ufSelect.val();
                    if (!uf) {
                        cidadeSelect.html('<option value="">Selecione a UF primeiro...</option>').prop('disabled', true);
                        return;
                    }
                    $.each(estadosCidades, function (i, estado) {
                        if (estado.sigla === uf) {
                            var lista = cidadesRestritas[uf] || estado.cidades;
                            var opts = '<option value="">Selecione a cidade...</option>';
                            $.each(lista, function (j, cidade) {
                                var sel = (cidadeAtual && cidade === cidadeAtual) ? ' selected' : '';
                                opts += '<option value="' + cidade + '"' + sel + '>' + cidade + '</option>';
                            });
                            cidadeSelect.html(opts).prop('disabled', false);
                            return false;
                        }
                    });
                };

                $('#colar_saude_uf').on('change', function () {
                    window._carregarCidades($(this), $('#colar_saude_cidade'));
                });
                $('#colar_odonto_uf').on('change', function () {
                    window._carregarCidades($(this), $('#colar_odonto_cidade'));
                });
                $('#editar_saude_uf').on('change', function () {
                    window._carregarCidades($(this), $('#editar_saude_cidade'));
                });
                $('#editar_odonto_uf').on('change', function () {
                    window._carregarCidades($(this), $('#editar_odonto_cidade'));
                });
            });

            // Troca de abas Saúde / Odonto
            $(document).on('click', '.modal-tab-btn', function () {
                var tab = $(this).data('tab');
                $('.modal-tab-btn').removeClass('modal-tab-ativo');
                $(this).addClass('modal-tab-ativo');
                $('.modal-tab-painel').hide();
                $('#painel-' + tab).show();
            });

            // Dot indicator: verde quando plano selecionado
            $(document).on('change', '#colar_saude_plano_id', function () {
                $('#dot-saude').toggleClass('modal-tab-dot-ativo', !!$(this).val());
            });
            $(document).on('change', '#colar_odonto_plano_id', function () {
                $('#dot-odonto').toggleClass('modal-tab-dot-ativo', !!$(this).val());
            });

            // ── Modal Importar Planilha ──
            window.abrirModalPlanilha = function (contratoId, modoEdicao) {
                $('#planilha_contrato_id').val(contratoId);
                $('#planilha_modo_edicao').val(modoEdicao ? '1' : '');
                $('#planilha_justificativa').val('');
                $('#arquivoPlanilha').val('');
                $('#planilhaMsgErro').hide().text('');
                $('#planilhaMsgSucesso').hide().text('');
                $('#btnImportarPlanilha').prop('disabled', false).text('Importar Planilha');
                if (modoEdicao) {
                    $('#modalPlanilhaTitulo').text('Re-importar Planilha');
                    $('#modalPlanilhaSub').text('Recalcula vidas e valor sem avançar etapa');
                } else {
                    $('#modalPlanilhaTitulo').text('Importar Planilha de Beneficiários');
                    $('#modalPlanilhaSub').text('Selecione o arquivo .xlsx no formato SIAEG');
                }
                $('#modalImportarPlanilha').show();
            };

            function fecharModalPlanilha() {
                $('#modalImportarPlanilha').hide();
            }

            $('#fecharModalPlanilha, #cancelarModalPlanilha').on('click', fecharModalPlanilha);
            $('#overlayModalPlanilha').on('click', fecharModalPlanilha);

            // ── Modal Aditivo PDF ──
            window.abrirModalAditivo = function (contratoId, modoEdicao) {
                $('#aditivo_contrato_id').val(contratoId);
                $('#aditivo_modo_edicao').val(modoEdicao ? '1' : '');
                $('#arquivoAditivo').val('');
                $('#dataAditivoInput').val('');
                $('#aditivoMsgErro').hide().text('');
                $('#aditivoMsgSucesso').hide().text('');
                $('#btnEnviarAditivo').prop('disabled', false).text('Enviar PDF');
                if (modoEdicao) {
                    $('#modalAditivoTitulo').text('Contrato — Re-enviar PDF');
                    $('#modalAditivoSub').text('Substitui o PDF e a data sem avançar etapa');
                } else {
                    $('#modalAditivoTitulo').text('Contrato — Upload PDF');
                    $('#modalAditivoSub').text('Selecione o arquivo PDF e informe a data do contrato');
                }
                $('#modalAditivoPdf').show();
            };

            function fecharModalAditivo() {
                $('#modalAditivoPdf').hide();
            }

            $('#fecharModalAditivo, #cancelarModalAditivo').on('click', fecharModalAditivo);
            $('#overlayModalAditivo').on('click', fecharModalAditivo);

            // ── Modal Adesão (Etapa 3) ──
            var temDiferencaAdesao = false;

            function resetarAdesaoModal() {
                $('#adesaoLendoPdf').hide();
                $('#adesaoValorExtraidoWrap').hide();
                $('#adesaoValorExtraidoBox').text('');
                $('#adesaoValorManualWrap').hide();
                $('#adesaoBoletoValorManual').val('');
                $('#adesaoAlertaDiferenca').hide();
                $('#adesaoJustificativaWrap').hide();
                $('#adesaoJustificativa').val('');
                $('#adesaoMsgErro').hide().text('');
                $('#adesaoMsgSucesso').hide().text('');
                temDiferencaAdesao = false;
            }

            window.abrirModalAdesao = function (contratoId, valorPlanilha, modoEdicao) {
                $('#adesao_contrato_id').val(contratoId);
                $('#adesao_valor_planilha').val(valorPlanilha || 0);
                $('#adesao_modo_edicao').val(modoEdicao ? '1' : '');
                $('#adesaoDataInput').val('');
                $('#arquivoAdesao').val('');
                $('#btnEnviarAdesao').prop('disabled', false).text('Confirmar Adesão');
                resetarAdesaoModal();
                if (modoEdicao) {
                    $('#modalAdesaoTitulo').text('Adesão — Re-enviar Boleto');
                    $('#modalAdesaoSub').text('Substitui o PDF e a data sem avançar etapa');
                } else {
                    $('#modalAdesaoTitulo').text('Adesão');
                    $('#modalAdesaoSub').text('O valor do boleto será lido automaticamente do PDF');
                }
                $('#modalAdesao').show();
            };

            function fecharModalAdesao() { $('#modalAdesao').hide(); }

            $('#fecharModalAdesao, #cancelarModalAdesao').on('click', fecharModalAdesao);
            $('#overlayModalAdesao').on('click', fecharModalAdesao);

            function mostrarCampoManual() {
                $('#adesaoValorManualWrap').show();
                if (typeof $.fn.mask === 'function') {
                    $('#adesaoBoletoValorManual').mask('#.##0,00', { reverse: true });
                }
            }

            function verificarDiferencaAdesao(valorBoleto) {
                var valorPlanilha = parseFloat($('#adesao_valor_planilha').val()) || 0;
                if (valorPlanilha > 0 && Math.abs(valorBoleto - valorPlanilha) > 0.01) {
                    temDiferencaAdesao = true;
                    var fmtP = valorPlanilha.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                    $('#adesaoValorPlanilhaTexto').text('R$ ' + fmtP);
                    $('#adesaoAlertaDiferenca').show();
                    $('#adesaoJustificativaWrap').show();
                } else {
                    temDiferencaAdesao = false;
                    $('#adesaoAlertaDiferenca').hide();
                    $('#adesaoJustificativaWrap').hide();
                }
            }

            // Ao selecionar o PDF: tenta extrair o valor automaticamente
            $('#arquivoAdesao').on('change', function () {
                var file = this.files[0];
                if (!file) return;

                resetarAdesaoModal();
                $('#adesaoLendoPdf').show();

                var fd = new FormData();
                fd.append('boleto', file);
                fd.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: urlExtrairValorBoleto,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        $('#adesaoLendoPdf').hide();
                        var valorExtraido = parseFloat(res.valor) || 0;
                        var fmt = valorExtraido.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                        $('#adesaoValorExtraidoBox').text('R$ ' + fmt);
                        $('#adesaoValorExtraidoWrap').show();
                        verificarDiferencaAdesao(valorExtraido);
                    },
                    error: function (xhr) {
                        // Qualquer falha na leitura → mostra campo manual
                        $('#adesaoLendoPdf').hide();
                        var resp = xhr.responseJSON || {};
                        if (resp.preview)  console.log('[PDF texto original]', resp.preview);
                        if (resp.dekerned) console.log('[PDF dekerned]',       resp.dekerned);
                        if (resp.compact)  console.log('[PDF compact]',        resp.compact);
                        mostrarCampoManual();
                    }
                });
            });

            // Campo manual: verifica diferença ao sair do campo
            $(document).on('blur', '#adesaoBoletoValorManual', function () {
                var raw = $(this).val().replace(/\./g, '').replace(',', '.');
                var v   = parseFloat(raw) || 0;
                if (v > 0) verificarDiferencaAdesao(v);
            });

            $('#formAdesao').on('submit', function (e) {
                e.preventDefault();
                $('#adesaoMsgErro').hide().text('');
                $('#adesaoMsgSucesso').hide().text('');

                var data = $('#adesaoDataInput').val();
                var file = $('#arquivoAdesao')[0].files[0];
                var just = $.trim($('#adesaoJustificativa').val());

                if (!data) { $('#adesaoMsgErro').text('Informe a data de adesão.').show(); return; }
                if (!file) { $('#adesaoMsgErro').text('Selecione o arquivo PDF do boleto.').show(); return; }
                if ($('#adesaoLendoPdf').is(':visible')) { $('#adesaoMsgErro').text('Aguarde a leitura do PDF.').show(); return; }

                var autoOk   = $('#adesaoValorExtraidoWrap').is(':visible');
                var manualOk = $('#adesaoValorManualWrap').is(':visible');

                if (!autoOk && !manualOk) {
                    // PDF selecionado mas leitura não terminou — mostrar manual
                    mostrarCampoManual();
                    $('#adesaoMsgErro').text('Informe o valor do boleto manualmente.').show();
                    return;
                }

                if (manualOk) {
                    var rawManual = $('#adesaoBoletoValorManual').val().replace(/\./g, '').replace(',', '.');
                    if (!rawManual || parseFloat(rawManual) <= 0) {
                        $('#adesaoMsgErro').text('Informe o valor do boleto.').show();
                        $('#adesaoBoletoValorManual').focus();
                        return;
                    }
                }

                if (temDiferencaAdesao && !just) {
                    $('#adesaoMsgErro').text('Informe a justificativa — os valores são diferentes.').show();
                    $('#adesaoJustificativa').focus();
                    return;
                }

                var formData = new FormData(this);
                var modoEd   = $('#adesao_modo_edicao').val() === '1';
                $('#btnEnviarAdesao').prop('disabled', true).text('Enviando...');

                $.ajax({
                    url: urlUploadAdesao,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        var cid = $('#adesao_contrato_id').val();
                        $('#adesaoMsgSucesso').text(res.message || 'Adesão registrada com sucesso!').show();
                        setTimeout(function () {
                            if (modoEd) {
                                fecharModalAdesao();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            } else if (window.transicaoEtapa) {
                                window.transicaoEtapa(3, cid, fecharModalAdesao);
                            } else {
                                fecharModalAdesao();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            }
                        }, 500);
                    },
                    error: function (xhr) {
                        console.error('[uploadAdesao] status=' + xhr.status, xhr.responseText);
                        var msg;
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            msg = xhr.responseJSON.error;
                        } else if (xhr.status === 419) {
                            msg = 'Sessão expirada. Recarregue a página e tente novamente.';
                        } else if (xhr.status === 0) {
                            msg = 'Sem conexão com o servidor.';
                        } else {
                            msg = 'Erro ' + xhr.status + ' ao enviar. Veja o console (F12) para detalhes.';
                        }
                        $('#adesaoMsgErro').text(msg).show();
                        $('#btnEnviarAdesao').prop('disabled', false).text('Confirmar Adesão');
                    }
                });
            });

            // ── Modal Carteirinha ──
            function resetarConfirmacaoCarteirinha() {
                $('#carteirinhaConfirmacao').hide();
                $('#carteirinhaConfirmacaoCheck').html('');
                $('#carteirinhaConfirmacaoLinks').html('');
                $('#carteirinhaConfirmacaoData').text('');
                $('#formCarteirinha').show();
                $('#carteirinhaListaArquivos').text('');
                $('#carteirinhaMsgErro').hide().text('');
            }

            function renderizarCarteirinhsExistentes(paths, contratoId) {
                var base = typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/';
                var svgTrash = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width:14px;height:14px;">'
                    + '<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>'
                    + '</svg>';

                if (!paths || !paths.length) {
                    $('#carteirinhaExistentesWrap').hide();
                    return;
                }

                var html = '';
                paths.forEach(function (p, i) {
                    var url = base + p;
                    html += '<div class="carteirinha-existente-row" data-path="' + p.replace(/"/g, '&quot;') + '" '
                        + 'style="display:flex;align-items:center;justify-content:space-between;'
                        + 'padding:7px 10px;border-radius:8px;background:rgba(255,255,255,.04);'
                        + 'border:1px solid rgba(255,255,255,.07);margin-bottom:5px;">'
                        + '<a href="' + url + '" target="_blank" '
                        + 'style="font-size:.78rem;color:#93c5fd;text-decoration:none;display:flex;align-items:center;gap:6px;">'
                        + '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f87171" style="width:14px;height:14px;flex-shrink:0;">'
                        + '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>'
                        + 'Carteirinha ' + (i + 1) + '</a>'
                        + '<button type="button" class="carteirinha-deletar-btn" '
                        + 'data-path="' + p.replace(/"/g, '&quot;') + '" data-contrato-id="' + contratoId + '" '
                        + 'style="display:inline-flex;align-items:center;gap:4px;padding:4px 8px;'
                        + 'border-radius:6px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.25);'
                        + 'color:#f87171;font-size:.72rem;cursor:pointer;transition:opacity .2s;">'
                        + svgTrash + ' Excluir</button>'
                        + '</div>';
                });
                $('#carteirinhaExistentesList').html(html);
                $('#carteirinhaExistentesWrap').show();
            }

            window.abrirModalCarteirinha = function (contratoId, modoEdicao, rowData) {
                $('#carteirinha_contrato_id').val(contratoId);
                $('#carteirinha_modo_edicao').val(modoEdicao ? '1' : '');
                if (modoEdicao) {
                    $('#modalCarteirinhaTitulo').text('Carteirinha — Adicionar PDF(s)');
                    $('#modalCarteirinhaSub').text('Novos arquivos são adicionados aos existentes');

                    var paths = [];
                    try { paths = JSON.parse((rowData && rowData.carteirinha_paths) || '[]'); } catch (e) {}
                    renderizarCarteirinhsExistentes(paths, contratoId);
                } else {
                    $('#modalCarteirinhaTitulo').text('Carteirinha — Upload PDF(s)');
                    $('#modalCarteirinhaSub').text('Selecione um ou vários arquivos PDF');
                    $('#carteirinhaExistentesWrap').hide();
                    $('#carteirinhaExistentesList').html('');
                }

                // Recria o input para garantir estado limpo e atributo multiple preservado
                var $oldInput = $('#arquivosCarteirinha');
                var $newInput = $('<input type="file" id="arquivosCarteirinha" name="carteirinhas[]" accept="application/pdf,.pdf" multiple="multiple">')
                    .css({ width:'100%', background:'#1a2540', color:'#e2e8f0',
                           border:'1px solid rgba(255,255,255,.12)', borderRadius:'10px',
                           padding:'10px 14px', fontSize:'.82rem', boxSizing:'border-box', cursor:'pointer' });
                $oldInput.replaceWith($newInput);
                $newInput.on('change', function () {
                    var files = this.files;
                    if (!files.length) { $('#carteirinhaListaArquivos').text(''); return; }
                    var nomes = [];
                    for (var i = 0; i < files.length; i++) nomes.push('• ' + files[i].name);
                    $('#carteirinhaListaArquivos').html(nomes.join('<br>'));
                });

                $('#btnEnviarCarteirinha').prop('disabled', false).text('Enviar PDF(s)');
                resetarConfirmacaoCarteirinha();
                $('#modalCarteirinha').show();
            };

            function fecharModalCarteirinha() {
                $('#modalCarteirinha').hide();
                resetarConfirmacaoCarteirinha();
            }

            $('#fecharModalCarteirinha, #cancelarModalCarteirinha').on('click', fecharModalCarteirinha);
            $('#overlayModalCarteirinha').on('click', fecharModalCarteirinha);

            // handler de change do arquivosCarteirinha é registrado dentro de abrirModalCarteirinha

            $('#formCarteirinha').on('submit', function (e) {
                e.preventDefault();
                $('#carteirinhaMsgErro').hide().text('');

                var files = $('#arquivosCarteirinha')[0].files;
                if (!files.length) {
                    $('#carteirinhaMsgErro').text('Selecione ao menos um arquivo PDF.').show();
                    return;
                }

                var formData = new FormData(this);
                var modoEd   = $('#carteirinha_modo_edicao').val() === '1';
                $('#btnEnviarCarteirinha').prop('disabled', true).text('Enviando...');

                $.ajax({
                    url: urlUploadCarteirinha,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        // Oculta o formulário e exibe confirmação visual
                        $('#formCarteirinha').hide();

                        var svgCheck = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#34d399" style="width:40px;height:40px;">'
                            + '<path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.06-1.06l-3.31 3.31-1.48-1.48a.75.75 0 0 0-1.06 1.06l2.01 2.01a.75.75 0 0 0 1.06 0l3.84-3.84Z" clip-rule="evenodd"/></svg>';

                        var svgPdf = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f87171" style="width:20px;height:20px;vertical-align:middle;">'
                            + '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>';

                        $('#carteirinhaConfirmacaoCheck').html(svgCheck);

                        var linksHtml = '';
                        var paths = res.paths || [];
                        paths.forEach(function (p, i) {
                            var url = (typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/') + p;
                            linksHtml += '<a href="' + url + '" target="_blank" '
                                + 'style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;'
                                + 'border-radius:8px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.25);'
                                + 'color:#f87171;font-size:.75rem;text-decoration:none;">'
                                + svgPdf + ' Carteirinha ' + (i + 1)
                                + '</a>';
                        });
                        $('#carteirinhaConfirmacaoLinks').html(linksHtml);
                        $('#carteirinhaConfirmacaoData').text(res.data || '');
                        $('#carteirinhaConfirmacao').show();

                        var cidCart = $('#carteirinha_contrato_id').val();
                        setTimeout(function () {
                            if (modoEd) {
                                fecharModalCarteirinha();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            } else if (window.transicaoEtapa) {
                                window.transicaoEtapa(6, cidCart, fecharModalCarteirinha);
                            } else {
                                fecharModalCarteirinha();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            }
                        }, 1200);
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : 'Erro ao enviar. Tente novamente.';
                        $('#carteirinhaMsgErro').text(msg).show();
                        $('#btnEnviarCarteirinha').prop('disabled', false).text('Enviar PDF(s)');
                    }
                });
            });

            // ── Excluir carteirinha individual ─────────────────────────────────
            $(document).on('click', '.carteirinha-deletar-btn', function () {
                var $btn       = $(this);
                var path       = $btn.data('path');
                var contratoId = $btn.data('contrato-id');

                Swal.fire({
                    title: 'Excluir este arquivo?',
                    text: 'O PDF será removido permanentemente.',
                    icon: 'warning',
                    background: '#1a2540',
                    color: '#e2e8f0',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: 'rgba(255,255,255,.1)',
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Cancelar',
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    $btn.prop('disabled', true).css('opacity', '.5');

                    $.ajax({
                        url: urlDeletarCarteirinha,
                        method: 'POST',
                        data: {
                            _token:      $('meta[name="csrf-token"]').attr('content'),
                            contrato_id: contratoId,
                            path:        path,
                        },
                        success: function (res) {
                            $btn.closest('.carteirinha-existente-row').fadeOut(250, function () {
                                $(this).remove();
                                // Renumera labels
                                $('#carteirinhaExistentesList .carteirinha-existente-row a').each(function (i) {
                                    $(this).contents().last().replaceWith(' Carteirinha ' + (i + 1));
                                });
                                if (!$('#carteirinhaExistentesList .carteirinha-existente-row').length) {
                                    $('#carteirinhaExistentesWrap').hide();
                                }
                            });
                            tableempresarial && tableempresarial.ajax.reload(null, false);
                        },
                        error: function () {
                            $btn.prop('disabled', false).css('opacity', '1');
                            Swal.fire({ title: 'Erro', text: 'Não foi possível excluir. Tente novamente.', icon: 'error', background: '#1a2540', color: '#e2e8f0' });
                        }
                    });
                });
            });

            $('#formAditivoPdf').on('submit', function (e) {
                e.preventDefault();
                $('#aditivoMsgErro').hide().text('');
                $('#aditivoMsgSucesso').hide().text('');

                var file = $('#arquivoAditivo')[0].files[0];
                var data = $('#dataAditivoInput').val();
                if (!file) {
                    $('#aditivoMsgErro').text('Selecione o arquivo PDF antes de enviar.').show();
                    return;
                }
                if (!data) {
                    $('#aditivoMsgErro').text('Informe a data do aditivo.').show();
                    return;
                }

                var formData = new FormData(this);
                var modoEd   = $('#aditivo_modo_edicao').val() === '1';
                $('#btnEnviarAditivo').prop('disabled', true).text('Enviando...');

                $.ajax({
                    url: urlUploadAditivoPdf,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        var cid = $('#aditivo_contrato_id').val();
                        $('#aditivoMsgSucesso').text(res.message || 'Aditivo enviado com sucesso!').show();
                        setTimeout(function () {
                            if (modoEd) {
                                fecharModalAditivo();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            } else if (window.transicaoEtapa) {
                                window.transicaoEtapa(2, cid, fecharModalAditivo);
                            } else {
                                fecharModalAditivo();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            }
                        }, 500);
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : 'Erro ao enviar. Tente novamente.';
                        $('#aditivoMsgErro').text(msg).show();
                        $('#btnEnviarAditivo').prop('disabled', false).text('Enviar PDF');
                    }
                });
            });

            function submeterPlanilha() {
                $('#planilhaMsgErro').hide().text('');
                $('#planilhaMsgSucesso').hide().text('');

                var file = $('#arquivoPlanilha')[0].files[0];
                if (!file) {
                    $('#planilhaMsgErro').text('Selecione um arquivo .xlsx antes de importar.').show();
                    return;
                }

                var formData = new FormData(document.getElementById('formImportarPlanilha'));
                $('#btnImportarPlanilha').prop('disabled', true).text('Importando...');

                $.ajax({
                    url: urlImportarPlanilha,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.divergencia) {
                            $('#btnImportarPlanilha').prop('disabled', false).text('Importar Planilha');
                            var fmtV = function (v) {
                                return 'R$ ' + parseFloat(v).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            };
                            var inputStyle = 'width:100%;background:#1a2540;color:#e2e8f0;border:1px solid rgba(255,255,255,.15);'
                                           + 'border-radius:8px;padding:8px 12px;font-size:.84rem;outline:none;box-sizing:border-box;margin-top:6px;';
                            Swal.fire({
                                title: '<span style="font-size:.92rem;font-weight:700;color:#fbbf24;">Divergência de Valor</span>',
                                html: '<p style="font-size:.82rem;color:rgba(255,255,255,.7);margin-bottom:10px;">'
                                    + 'Valor anterior (boleto): <strong style="color:#f87171;">' + fmtV(res.valor_anterior) + '</strong><br>'
                                    + 'Novo valor (planilha): <strong style="color:#34d399;">' + fmtV(res.valor_novo) + '</strong>'
                                    + '</p>'
                                    + '<textarea id="swal-just-planilha" rows="3" placeholder="Informe a justificativa da diferença de valor..." style="' + inputStyle + '"></textarea>',
                                background: '#0f1e38',
                                color: '#e2e8f0',
                                confirmButtonColor: '#4f8ef7',
                                confirmButtonText: 'Salvar com Justificativa',
                                showCancelButton: true,
                                cancelButtonText: 'Cancelar',
                                preConfirm: function () {
                                    var just = document.getElementById('swal-just-planilha').value.trim();
                                    if (!just) { Swal.showValidationMessage('A justificativa é obrigatória.'); return false; }
                                    return just;
                                }
                            }).then(function (result) {
                                if (!result.isConfirmed) return;
                                $('#planilha_justificativa').val(result.value);
                                submeterPlanilha();
                            });
                            return;
                        }
                        var cid = $('#planilha_contrato_id').val();
                        var modoEd = $('#planilha_modo_edicao').val() === '1';
                        $('#planilhaMsgSucesso').text(res.message || 'Importado com sucesso!').show();
                        setTimeout(function () {
                            if (modoEd) {
                                fecharModalPlanilha();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            } else if (window.transicaoEtapa) {
                                window.transicaoEtapa(1, cid, fecharModalPlanilha);
                            } else {
                                fecharModalPlanilha();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            }
                        }, 500);
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : 'Erro ao importar. Tente novamente.';
                        $('#planilhaMsgErro').text(msg).show();
                        $('#btnImportarPlanilha').prop('disabled', false).text('Importar Planilha');
                    }
                });
            }

            $('#formImportarPlanilha').on('submit', function (e) {
                e.preventDefault();
                submeterPlanilha();
            });

            // ── Modal Finalizado (Etapa 8) ──
            window.abrirModalFinalizado = function (contratoId, modoEdicao) {
                $('#finalizado_contrato_id').val(contratoId);
                $('#finalizado_modo_edicao').val(modoEdicao ? '1' : '');
                $('#finalizadoDataInput').val('');
                $('#arquivoFinalizado').val('');
                $('#finalizadoMsgErro').hide().text('');
                $('#finalizadoMsgSucesso').hide().text('');
                $('#btnEnviarFinalizado').prop('disabled', false).text(modoEdicao ? 'Salvar' : 'Finalizar Contrato');

                if (modoEdicao) {
                    $('#modalFinalizadoTitulo').text('Finalizado — Atualizar');
                    $('#modalFinalizadoSub').text('Substitui data/PDF sem avançar etapa');
                } else {
                    $('#modalFinalizadoTitulo').text('Finalizar Contrato');
                    $('#modalFinalizadoSub').text('Informe a data e envie o PDF de finalização');
                }

                $('#modalFinalizado').show();
            };

            function fecharModalFinalizado() { $('#modalFinalizado').hide(); }

            $('#fecharModalFinalizado, #cancelarModalFinalizado').on('click', fecharModalFinalizado);
            $('#overlayModalFinalizado').on('click', fecharModalFinalizado);

            $('#formFinalizado').on('submit', function (e) {
                e.preventDefault();
                $('#finalizadoMsgErro').hide().text('');
                $('#finalizadoMsgSucesso').hide().text('');

                var modoEd = $('#finalizado_modo_edicao').val() === '1';
                var data   = $('#finalizadoDataInput').val();
                var file   = $('#arquivoFinalizado')[0].files[0];

                if (!data) { $('#finalizadoMsgErro').text('Informe a data de finalização.').show(); return; }
                if (!modoEd && !file) { $('#finalizadoMsgErro').text('Selecione o arquivo PDF final.').show(); return; }

                var fd = new FormData(this);
                $('#btnEnviarFinalizado').prop('disabled', true).text('Salvando...');

                $.ajax({
                    url: urlSalvarFinalizado,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function () {
                        var cid = $('#finalizado_contrato_id').val();
                        $('#finalizadoMsgSucesso').text(modoEd ? 'Dados atualizados!' : 'Contrato finalizado com sucesso!').show();
                        setTimeout(function () {
                            if (!modoEd && window.transicaoEtapa) {
                                window.transicaoEtapa(8, cid, fecharModalFinalizado);
                            } else {
                                fecharModalFinalizado();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            }
                        }, 500);
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error : 'Erro ao finalizar. Tente novamente.';
                        $('#finalizadoMsgErro').text(msg).show();
                        $('#btnEnviarFinalizado').prop('disabled', false).text(modoEd ? 'Salvar' : 'Finalizar Contrato');
                    }
                });
            });

            // ── Modal Vigência (Etapa 5) ──
            window.abrirModalVigencia = function (contratoId, tipoContrato, modoEdicao) {
                var tipo = tipoContrato || null;
                $('#vigencia_contrato_id').val(contratoId);
                $('#vigencia_tipo_contrato').val(tipo || '');
                $('#vigencia_modo_edicao').val(modoEdicao ? '1' : '');
                $('#textoVigencia').val('');
                $('#vigenciaMsgErro').hide().text('');
                $('#vigenciaMsgSucesso').hide().text('');
                $('#btnSalvarVigencia').prop('disabled', false).text('Salvar Vigência');

                if (tipo === 'ambos') {
                    $('#vigenciaFormatoOdonto').css('color', '#93c5fd').text('ODONTO: XXXXX (obrigatório)');
                    $('#vigenciaAvisoOdonto').show();
                } else {
                    $('#vigenciaFormatoOdonto').css('color', 'rgba(255,255,255,.4)').text('[ODONTO: XXXXX]');
                    $('#vigenciaAvisoOdonto').hide();
                }

                if (modoEdicao) {
                    $('#modalVigenciaTitulo').text('Vigência — Atualizar Dados');
                    $('#modalVigenciaSub').text('Substitui os dados sem avançar etapa');
                } else {
                    $('#modalVigenciaTitulo').text('Vigência — Colar Dados');
                    $('#modalVigenciaSub').text('Cole o texto com os dados de ativação');
                }

                $('#modalVigencia').show();
            };

            function fecharModalVigencia() {
                $('#modalVigencia').hide();
            }

            $('#fecharModalVigencia, #cancelarModalVigencia').on('click', fecharModalVigencia);
            $('#overlayModalVigencia').on('click', fecharModalVigencia);

            $('#formVigencia').on('submit', function (e) {
                e.preventDefault();
                $('#vigenciaMsgErro').hide().text('');
                $('#vigenciaMsgSucesso').hide().text('');

                var texto = $('#textoVigencia').val().trim();
                if (!texto) {
                    $('#vigenciaMsgErro').text('Cole o texto no campo.').show();
                    return;
                }

                var tipoV = $('#vigencia_tipo_contrato').val();
                if (tipoV === 'ambos' && !/ODONTO\s*:\s*[A-Z0-9]+/i.test(texto)) {
                    $('#vigenciaMsgErro').text('Contrato Saúde + Odonto: informe o código ODONTO (ex: ODONTO: SJATL).').show();
                    return;
                }

                var modoEd = $('#vigencia_modo_edicao').val() === '1';
                $('#btnSalvarVigencia').prop('disabled', true).text('Salvando...');

                $.ajax({
                    url: urlSalvarVigenciaColar,
                    method: 'POST',
                    data: {
                        contrato_id: $('#vigencia_contrato_id').val(),
                        texto_colar: texto,
                    },
                    success: function (res) {
                        var cid = $('#vigencia_contrato_id').val();
                        $('#vigenciaMsgSucesso').text(res.message || 'Vigência registrada com sucesso!').show();
                        setTimeout(function () {
                            if (modoEd) {
                                fecharModalVigencia();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            } else if (window.transicaoEtapa) {
                                window.transicaoEtapa(5, cid, fecharModalVigencia);
                            } else {
                                fecharModalVigencia();
                                tableempresarial && tableempresarial.ajax.reload(null, false);
                            }
                        }, 500);
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : 'Erro ao salvar. Tente novamente.';
                        $('#vigenciaMsgErro').text(msg).show();
                        $('#btnSalvarVigencia').prop('disabled', false).text('Salvar Vigência');
                    }
                });
            });

            // ── Modal 1º Boleto (Etapa 7) — 4 documentos individuais ──
            var BOLETO_TIPOS = [
                'boleto_saude',
                'demonstrativo_saude',
                'boleto_odonto',
                'demonstrativo_odonto',
            ];

            var currentBoleto = {};
            var primeiroBoletoModoEdicao = false;

            function atualizarDocBoleto(tipo, path) {
                var $status   = $('#boletoStatus_' + tipo);
                var $download = $('#boletoDownload_' + tipo);
                var $btn      = $('#boletoBtn_' + tipo); // não existe — usamos .boleto-doc-upload-btn
                $btn = $('[data-tipo="' + tipo + '"].boleto-doc-upload-btn');

                if (path) {
                    var url = (typeof appAssetUrl !== 'undefined' ? appAssetUrl : '/') + path;
                    $status.text('✓ Enviado').css('color', '#34d399');
                    $download.html(
                        '<a href="' + url + '" target="_blank" '
                        + 'style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;'
                        + 'border-radius:6px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.25);'
                        + 'color:#f87171;font-size:.73rem;text-decoration:none;">'
                        + '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f87171" style="width:13px;height:13px;">'
                        + '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>'
                        + '</svg> Baixar PDF</a>'
                    ).show();
                    $btn.text('Substituir PDF').css('background', 'rgba(79,142,247,.35)');
                } else {
                    $status.text('Não enviado').css('color', 'rgba(255,255,255,.3)');
                    $download.hide().html('');
                    $btn.text('Enviar PDF').css('background', '#4f8ef7');
                }
            }

            function atualizarProgressoBoleto() {
                var count = BOLETO_TIPOS.filter(function (t) { return currentBoleto[t]; }).length;
                $('#boletoProgressoText').text(count + ' de 4 enviados');
                $('#boletoProgressoFill').css('width', (count * 25) + '%');
                if (count === 4) {
                    $('#boletoProgressoFill').css('background', '#34d399');
                    $('#boletoProgressoText').css('color', '#34d399');
                } else {
                    $('#boletoProgressoFill').css('background', '#4f8ef7');
                    $('#boletoProgressoText').css('color', 'rgba(255,255,255,.5)');
                }
            }

            window.abrirModalPrimeiroBoleto = function (contratoId, rowData, modoEdicao) {
                $('#primeiroBoleto_contrato_id').val(contratoId);
                primeiroBoletoModoEdicao = !!modoEdicao;

                currentBoleto = {
                    boleto_saude:        (rowData && rowData.boleto_saude_path)        || null,
                    demonstrativo_saude: (rowData && rowData.demonstrativo_saude_path) || null,
                    boleto_odonto:       (rowData && rowData.boleto_odonto_path)        || null,
                    demonstrativo_odonto:(rowData && rowData.demonstrativo_odonto_path) || null,
                };

                BOLETO_TIPOS.forEach(function (tipo) {
                    $('#boletoMsg_' + tipo).hide().text('').css('color', '');
                    $('#boletoFile_' + tipo).val('');
                    $('#boletoLoading_' + tipo).hide();
                    $('[data-tipo="' + tipo + '"].boleto-doc-upload-btn').prop('disabled', false);
                    atualizarDocBoleto(tipo, currentBoleto[tipo]);
                });

                atualizarProgressoBoleto();

                if (modoEdicao) {
                    $('#modalPrimeiroBoletoTitulo').text('1º Boleto — Substituir Documentos');
                    $('#modalPrimeiroBoletoSub').text('Substitui arquivos sem avançar etapa');
                } else {
                    $('#modalPrimeiroBoletoTitulo').text('1º Boleto — Documentos');
                    $('#modalPrimeiroBoletoSub').text('Envie os 4 documentos para concluir esta etapa');
                }

                $('#modalPrimeiroBoleto').show();
            };

            function fecharModalPrimeiroBoleto() {
                $('#modalPrimeiroBoleto').hide();
            }

            $('#fecharModalPrimeiroBoleto, #cancelarModalPrimeiroBoleto').on('click', fecharModalPrimeiroBoleto);
            $('#overlayModalPrimeiroBoleto').on('click', fecharModalPrimeiroBoleto);

            // Clique no botão → abre o file input correspondente
            $(document).on('click', '.boleto-doc-upload-btn', function () {
                var tipo = $(this).data('tipo');
                $('#boletoFile_' + tipo).trigger('click');
            });

            // Ao selecionar arquivo → envia imediatamente via AJAX
            $(document).on('change', '.boleto-doc-file-input', function () {
                var tipo = $(this).data('tipo');
                var file = this.files[0];
                if (!file) return;

                var contratoId = $('#primeiroBoleto_contrato_id').val();
                $('#boletoMsg_' + tipo).hide().text('');
                $('#boletoLoading_' + tipo).show();
                $('[data-tipo="' + tipo + '"].boleto-doc-upload-btn').prop('disabled', true);

                var fd = new FormData();
                fd.append('contrato_id', contratoId);
                fd.append('tipo', tipo);
                fd.append('arquivo', file);
                fd.append('_token', $('meta[name="csrf-token"]').attr('content'));

                // Limpa o input para permitir reenvio do mesmo arquivo
                $(this).val('');

                $.ajax({
                    url: urlUploadDocumentoBoleto,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        $('#boletoLoading_' + tipo).hide();
                        $('[data-tipo="' + tipo + '"].boleto-doc-upload-btn').prop('disabled', false);

                        currentBoleto[tipo] = res.path;
                        atualizarDocBoleto(tipo, res.path);
                        atualizarProgressoBoleto();

                        // Recarrega a tabela silenciosamente após cada upload para que
                        // rowData tenha os paths atualizados quando o modal for reaberto
                        if (typeof tableempresarial !== 'undefined' && tableempresarial) {
                            tableempresarial.ajax.reload(null, false);
                        }

                        if (res.todos_enviados) {
                            $('#boletoMsg_' + tipo).text('Todos os documentos enviados!').css('color', '#34d399').show();
                            setTimeout(function () {
                                var cid = $('#primeiroBoleto_contrato_id').val();
                                if (!primeiroBoletoModoEdicao && window.transicaoEtapa) {
                                    window.transicaoEtapa(7, cid, fecharModalPrimeiroBoleto);
                                } else {
                                    fecharModalPrimeiroBoleto();
                                    tableempresarial && tableempresarial.ajax.reload(null, false);
                                }
                            }, 800);
                        } else {
                            $('#boletoMsg_' + tipo).text('Enviado!').css('color', '#34d399').show();
                            setTimeout(function () { $('#boletoMsg_' + tipo).fadeOut(); }, 2000);
                        }
                    },
                    error: function (xhr) {
                        $('#boletoLoading_' + tipo).hide();
                        $('[data-tipo="' + tipo + '"].boleto-doc-upload-btn').prop('disabled', false);
                        var msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error : 'Erro ao enviar. Tente novamente.';
                        $('#boletoMsg_' + tipo).text(msg).css('color', '#f87171').show();
                    }
                });
            });

            // ── Ícone de alerta na etapa 3: abre modal com justificativa ──
            $(document).on('click', '.etapa3-warn-icon', function (e) {
                e.stopPropagation();
                var just = $(this).data('justificativa') || '(sem justificativa)';
                var bval = $(this).data('boleto-valor') || '';
                var pval = $(this).data('planilha-valor') || '';
                Swal.fire({
                    title: 'Diferença de Valor — Adesão',
                    html: '<div style="text-align:left;font-size:.85rem;line-height:1.7;">'
                        + '<p style="color:rgba(255,255,255,.45);font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;margin:0 0 4px;">Valor da Planilha</p>'
                        + '<p style="color:#34d399;font-weight:700;margin:0 0 14px;">' + (pval ? 'R$ ' + parseFloat(pval).toLocaleString('pt-BR', {minimumFractionDigits:2}) : '-') + '</p>'
                        + '<p style="color:rgba(255,255,255,.45);font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;margin:0 0 4px;">Valor do Boleto</p>'
                        + '<p style="color:#fbbf24;font-weight:700;margin:0 0 14px;">' + (bval ? 'R$ ' + parseFloat(bval).toLocaleString('pt-BR', {minimumFractionDigits:2}) : '-') + '</p>'
                        + '<p style="color:rgba(255,255,255,.45);font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;margin:0 0 4px;">Justificativa</p>'
                        + '<p style="color:#e2e8f0;background:#1a2540;padding:10px 14px;border-radius:8px;margin:0;">' + just + '</p>'
                        + '</div>',
                    background: '#151e30',
                    color: '#e2e8f0',
                    confirmButtonColor: '#4f8ef7',
                    confirmButtonText: 'Fechar',
                    showCancelButton: false,
                });
            });

            $('#formColarDados').on('submit', function (e) {
                e.preventDefault();

                $('#colarMsgErro').hide().text('');
                $('#colarMsgSucesso').hide().text('');

                var texto = $('#textoColar').val().trim();
                if (!texto) { $('#colarMsgErro').text('Cole os dados no campo de texto.').show(); return; }

                var corritorId = $('#colar_user_id').val();
                var saude = {
                    plano_id:       $('#colar_saude_plano_id').val(),
                    coparticipacao: $('#colar_saude_coparticipacao').val(),
                    uf:             $('#colar_saude_uf').val(),
                    cidade:         $('#colar_saude_cidade').val(),
                    user_id:        corritorId
                };
                var odonto = {
                    plano_id: $('#colar_odonto_plano_id').val(),
                    uf:       $('#colar_odonto_uf').val(),
                    cidade:   $('#colar_odonto_cidade').val(),
                    user_id:  corritorId
                };

                var temSaude  = saude.plano_id || saude.uf || saude.cidade;
                var temOdonto = odonto.plano_id || odonto.uf || odonto.cidade;

                if (!temSaude && !temOdonto) {
                    $('#colarMsgErro').text('Preencha pelo menos uma aba (Saúde ou Odonto).').show(); return;
                }
                if (!corritorId) {
                    $('#colarMsgErro').text('Selecione o corretor.').show(); return;
                }
                if (temSaude) {
                    if (!saude.plano_id)       { $('.modal-tab-btn[data-tab="saude"]').click(); $('#colarMsgErro').text('Selecione o plano de Saúde.').show(); return; }
                    if (!saude.coparticipacao) { $('.modal-tab-btn[data-tab="saude"]').click(); $('#colarMsgErro').text('Selecione a Coparticipação do plano de Saúde.').show(); return; }
                    if (!saude.uf)             { $('.modal-tab-btn[data-tab="saude"]').click(); $('#colarMsgErro').text('Selecione a UF do plano de Saúde.').show(); return; }
                    if (!saude.cidade)         { $('.modal-tab-btn[data-tab="saude"]').click(); $('#colarMsgErro').text('Selecione a cidade do plano de Saúde.').show(); return; }
                }
                if (temOdonto) {
                    if (!odonto.plano_id) { $('.modal-tab-btn[data-tab="odonto"]').click(); $('#colarMsgErro').text('Selecione o plano Odontológico.').show(); return; }
                    if (!odonto.uf)       { $('.modal-tab-btn[data-tab="odonto"]').click(); $('#colarMsgErro').text('Selecione a UF do plano Odontológico.').show(); return; }
                    if (!odonto.cidade)   { $('.modal-tab-btn[data-tab="odonto"]').click(); $('#colarMsgErro').text('Selecione a cidade do plano Odontológico.').show(); return; }
                }

                $('#btnCadastrarColar').prop('disabled', true).text('Cadastrando...');

                $.ajax({
                    url: urlColarEmpresarial,
                    method: 'POST',
                    data: {
                        texto_colado:          texto,
                        saude_plano_id:        saude.plano_id       || '',
                        saude_coparticipacao:  saude.coparticipacao || '',
                        saude_uf:              saude.uf             || '',
                        saude_cidade:          saude.cidade         || '',
                        saude_user_id:         corritorId           || '',
                        odonto_plano_id:       odonto.plano_id      || '',
                        odonto_uf:             odonto.uf            || '',
                        odonto_cidade:         odonto.cidade        || '',
                        odonto_user_id:        corritorId           || '',
                    },
                    success: function (res) {
                        $('#colarMsgSucesso').text(res.message || 'Contrato cadastrado com sucesso!').show();
                        setTimeout(function () {
                            fecharModalColar();
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : 'Erro ao cadastrar. Verifique os dados e tente novamente.';
                        $('#colarMsgErro').text(msg).show();
                        $('#btnCadastrarColar').prop('disabled', false).text('Cadastrar Contrato');
                    }
                });
            });
        });
    </script>

    @section('scripts')
        <script src="{{ asset('js/financeiro-arquivo.js') }}"></script>
        <script src="{{ asset('js/financeiro-inicializar-empresarial.js') }}"></script>
        <script src="{{ asset('js/financeiro-parametro-url.js') }}"></script>
    @endsection

</x-app-layout>
