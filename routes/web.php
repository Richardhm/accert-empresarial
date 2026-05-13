<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\GerenteController;
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
    Route::post('/financeiro/modal/empresarial',[FinanceiroController::class,'modalEmpresarial'])->name('financeiro.modal.contrato.empresarial');
    Route::post('/financeiro/status-pagamento',[FinanceiroController::class,'atualizarStatusPagamento'])->name('financeiro.status.pagamento');

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

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/empresas-vendedor', [DashboardController::class, 'empresasPorVendedor'])->name('dashboard.empresas.vendedor');

        Route::get('/cadastrar_usuario', [AdminController::class, 'formCadastrarUsuario'])->name('admin.cadastrar_usuario');
        Route::post('/cadastrar_usuario', [AdminController::class, 'storeCadastrarUsuario'])->name('admin.store_usuario');
        Route::post('/cadastrar_usuario/comissao', [AdminController::class, 'atualizarComissao'])->name('admin.atualizar_comissao');
        Route::post('/cadastrar_usuario/excluir', [AdminController::class, 'excluirUsuario'])->name('admin.excluir_usuario');
    });

});

require __DIR__.'/auth.php';
