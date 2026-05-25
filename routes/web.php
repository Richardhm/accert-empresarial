<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfiguracoesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return redirect('/login');
    });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/financeiro',[FinanceiroController::class,'index'])->name('financeiro.index');
    Route::get('/listar/empresarial',[FinanceiroController::class,'listar'])->name('contratos.listarEmpresarial.listarContratoEmpresaPendentes');
    Route::post('/excluir/contrato',[FinanceiroController::class,'excluir'])->name('contratos.excluir');

    Route::get('/contratos/cadastrar/empresarial',[FinanceiroController::class,'formCreateEmpresarial'])->name('contratos.create.empresarial');

    Route::post('/contratos/empresarial/financeiro',[FinanceiroController::class,'storeEmpresarialFinanceiro'])->name('contratos.storeEmpresarial.financeiro');
    Route::post('/contratos/colar/empresarial',[FinanceiroController::class,'storeColarEmpresarial'])->name('contratos.colar.empresarial');
    Route::post('/contratos/empresarial/atualizar-campo',[FinanceiroController::class,'atualizarCampoEmpresarial'])->name('contratos.empresarial.atualizar_campo');
    Route::post('/contratos/empresarial/avancar-etapa',[FinanceiroController::class,'avancarEtapa'])->name('contratos.empresarial.avancar_etapa');
    Route::post('/contratos/empresarial/atualizar',[FinanceiroController::class,'atualizarContrato'])->name('contratos.empresarial.atualizar');
    Route::post('/contratos/empresarial/importar-planilha',[FinanceiroController::class,'importarPlanilha'])->name('contratos.empresarial.importar_planilha');
    Route::post('/contratos/empresarial/upload-aditivo',[FinanceiroController::class,'uploadAditivoPdf'])->name('contratos.empresarial.upload_aditivo');
    Route::post('/contratos/empresarial/salvar-adesao',[FinanceiroController::class,'salvarDataAdesao'])->name('contratos.empresarial.salvar_adesao');
    Route::post('/contratos/empresarial/upload-adesao',[FinanceiroController::class,'uploadAdesao'])->name('contratos.empresarial.upload_adesao');
    Route::post('/contratos/empresarial/extrair-valor-boleto',[FinanceiroController::class,'extrairValorBoletoPdf'])->name('contratos.empresarial.extrair_valor_boleto');
    Route::post('/contratos/empresarial/debug-pdf',[FinanceiroController::class,'debugExtrairPdf'])->name('contratos.empresarial.debug_pdf');
    Route::post('/contratos/empresarial/salvar-boleto',[FinanceiroController::class,'salvarBoleto'])->name('contratos.empresarial.salvar_boleto');
    Route::post('/contratos/empresarial/salvar-vigencia',[FinanceiroController::class,'salvarVigencia'])->name('contratos.empresarial.salvar_vigencia');
    Route::post('/contratos/empresarial/salvar-vigencia-colar',[FinanceiroController::class,'salvarVigenciaColar'])->name('contratos.empresarial.salvar_vigencia_colar');
    Route::post('/contratos/empresarial/upload-carteirinha',[FinanceiroController::class,'uploadCarteirinha'])->name('contratos.empresarial.upload_carteirinha');
    Route::post('/contratos/empresarial/deletar-carteirinha',[FinanceiroController::class,'deletarCarteirinha'])->name('contratos.empresarial.deletar_carteirinha');
    Route::post('/contratos/empresarial/salvar-primeiro-boleto',[FinanceiroController::class,'salvarPrimeiroBoleto'])->name('contratos.empresarial.salvar_primeiro_boleto');
    Route::post('/contratos/empresarial/upload-documento-boleto',[FinanceiroController::class,'uploadDocumentoBoleto'])->name('contratos.empresarial.upload_documento_boleto');
    Route::post('/contratos/empresarial/salvar-finalizado',[FinanceiroController::class,'salvarFinalizado'])->name('contratos.empresarial.salvar_finalizado');
    Route::post('/financeiro/modal/empresarial',[FinanceiroController::class,'modalEmpresarial'])->name('financeiro.modal.contrato.empresarial');
    Route::post('/financeiro/status-pagamento',[FinanceiroController::class,'atualizarStatusPagamento'])->name('financeiro.status.pagamento');
    Route::get('/financeiro/resumo-valor/{id}',[FinanceiroController::class,'resumoValor'])->name('financeiro.resumo_valor');
    Route::get('/financeiro/beneficiarios/{id}',[FinanceiroController::class,'listarBeneficiarios'])->name('financeiro.beneficiarios');

    // Rotas exclusivas para administradores
    Route::middleware('admin')->group(function () {
        Route::get("/gerente",[GerenteController::class,'index'])->name('gerente.index');
        Route::post('/gerente/folha_mes/inserir',[GerenteController::class,'cadastrarFolhaMes'])->name('gerente.cadastrar.folha_mes');
        Route::post('/gerente/informacoes/corretor',[GerenteController::class,'infoCorretor'])->name('gerente.informacoes.quantidade.corretor');
        Route::get('/gerente/listagem/comissao_mes_atual/{id}',[GerenteController::class,'comissaoMesAtual'])->name('gerente.listagem.comissao_mes_atual');
        Route::post('/gerente/mudar/para_a_nao_pago',[GerenteController::class,'mudarStatusParaNaoPago'])->name('gerente.mudar.para_a_nao_pago');
        Route::post('/gerente/mes/especifico/comissao/confirmadas',[GerenteController::class,'comissaoListagemConfirmadasMesEspecifico'])->name('gerente.listagem.confirmadas.especifica');
        Route::get('/gerente/empresarial/listar/{id}',[GerenteController::class,'empresarialAReceber'])->name('gerente.listagem.empresarial.areceber');
        Route::get('/gerente/finalizar/criarpdf',[GerenteController::class,'criarPDFUser'])->name('gerente.finalizar.criarpdf');
        Route::get('/gerente/folha/preview',[GerenteController::class,'previsualizarFolha'])->name('gerente.folha.preview');
        Route::get('/gerente/exportar/excel',[GerenteController::class,'exportarExcel'])->name('gerente.exportar.excel');
        Route::post('/gerente/mudarcomisao/corretor/gerente',[GerenteController::class,'mudarComissaoCorretor'])->name('gerente.mudar.valor.corretor');
        Route::post('/gerente/antecipar/parcela',[GerenteController::class,'aptarPagamento'])->name('gerente.aptar.pagamento');
        Route::get('/gerente/comissao/empresarial/confirmadas/{id}/{mes?}/{ano?}',[GerenteController::class,'comissaoListagemConfirmadasEmpresarial'])->name('gerente.listagem.empresarial.confirmadas');

        // Vale
        Route::post('/gerente/vale/salvar', [GerenteController::class,'salvarVale'])->name('gerente.vale.salvar');
        Route::get('/gerente/vale/listar',  [GerenteController::class,'listarValesMes'])->name('gerente.vale.listar');
        Route::post('/gerente/vale/excluir',[GerenteController::class,'excluirVale'])->name('gerente.vale.excluir');

        // Fechar mês
        Route::get('/gerente/fechamento/resumo', [GerenteController::class,'resumoFechamento'])->name('gerente.fechamento.resumo');
        Route::post('/gerente/fechamento/fechar', [GerenteController::class,'fecharMes'])->name('gerente.fechamento.fechar');

        // Configurações — Faixas Etárias (Saúde)
        Route::get('/configuracoes/faixas', [ConfiguracoesController::class, 'faixas'])->name('configuracoes.faixas');
        Route::get('/configuracoes/faixas/cidades', [ConfiguracoesController::class, 'cidadesPorUf'])->name('configuracoes.cidades');
        Route::get('/configuracoes/faixas/carregar', [ConfiguracoesController::class, 'carregar'])->name('configuracoes.faixas.carregar');
        Route::post('/configuracoes/faixas/salvar', [ConfiguracoesController::class, 'salvar'])->name('configuracoes.faixas.salvar');

        // Configurações — Valores Odonto
        Route::get('/configuracoes/odonto', [ConfiguracoesController::class, 'odonto'])->name('configuracoes.odonto');
        Route::get('/configuracoes/odonto/carregar', [ConfiguracoesController::class, 'odontoCarregar'])->name('configuracoes.odonto.carregar');
        Route::post('/configuracoes/odonto/salvar', [ConfiguracoesController::class, 'odontoSalvar'])->name('configuracoes.odonto.salvar');

        // Configurações — Cadastro unificado
        Route::get('/configuracoes/cadastro', [ConfiguracoesController::class, 'cadastro'])->name('configuracoes.cadastro');

        // Configurações — Gerenciar Planos
        Route::get('/configuracoes/planos',                [ConfiguracoesController::class, 'listarPlanos'])->name('configuracoes.planos.listar');
        Route::post('/configuracoes/planos/salvar',        [ConfiguracoesController::class, 'salvarPlano'])->name('configuracoes.planos.salvar');
        Route::post('/configuracoes/planos/{id}/atualizar',[ConfiguracoesController::class, 'atualizarPlano'])->name('configuracoes.planos.atualizar');
        Route::post('/configuracoes/planos/{id}/excluir',  [ConfiguracoesController::class, 'excluirPlano'])->name('configuracoes.planos.excluir');

        Route::get('/pagamento', [PagamentoController::class, 'index'])->name('pagamento.index');
        Route::get('/listar/pagamento', [PagamentoController::class, 'listar'])->name('pagamento.listar');
        Route::post('/pagamento/upload-planilha', [PagamentoController::class, 'uploadPlanilha'])->name('pagamento.upload_planilha');
        Route::get('/pagamento/detalhe/{id}', [PagamentoController::class, 'detalheContrato'])->name('pagamento.detalhe');
        Route::get('/pagamento/nao-vinculados', [PagamentoController::class, 'naoVinculados'])->name('pagamento.nao_vinculados');
        Route::get('/pagamento/buscar-contratos', [PagamentoController::class, 'buscarContratos'])->name('pagamento.buscar_contratos');
        Route::post('/pagamento/vincular/{id}', [PagamentoController::class, 'vincular'])->name('pagamento.vincular');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/empresas-vendedor', [DashboardController::class, 'empresasPorVendedor'])->name('dashboard.empresas.vendedor');

        Route::get('/cadastrar_usuario', [AdminController::class, 'formCadastrarUsuario'])->name('admin.cadastrar_usuario');
        Route::post('/cadastrar_usuario', [AdminController::class, 'storeCadastrarUsuario'])->name('admin.store_usuario');
        Route::post('/cadastrar_usuario/comissao', [AdminController::class, 'atualizarComissao'])->name('admin.atualizar_comissao');
        Route::post('/cadastrar_usuario/excluir', [AdminController::class, 'excluirUsuario'])->name('admin.excluir_usuario');
    });

});

require __DIR__.'/auth.php';
