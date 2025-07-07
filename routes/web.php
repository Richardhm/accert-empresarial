<?php

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


    Route::get("/gerente",[GerenteController::class,'index'])->name('gerente.index');

    Route::post('/gerente/folha_mes/inserir',[GerenteController::class,'cadastrarFolhaMes'])->name('gerente.cadastrar.folha_mes');
    Route::post('/gerente/informacoes/corretor',[GerenteController::class,'infoCorretor'])->name('gerente.informacoes.quantidade.corretor');
    Route::get('/gerente/listagem/comissao_mes_atual/{id}',[GerenteController::class,'comissaoMesAtual'])->name('gerente.listagem.comissao_mes_atual');
    Route::post('/gerente/mudar/para_a_nao_pago',[GerenteController::class,'mudarStatusParaNaoPago'])->name('gerente.mudar.para_a_nao_pago');
    Route::post('/gerente/mes/especifico/comissao/confirmadas',[GerenteController::class,'comissaoListagemConfirmadasMesEspecifico'])->name('gerente.listagem.confirmadas.especifica');
    Route::get('/gerente/empresarial/listar/{id}',[GerenteController::class,'empresarialAReceber'])->name('gerente.listagem.empresarial.areceber');
    Route::get('/gerente/finalizar/criarpdf',[GerenteController::class,'criarPDFUser'])->name('gerente.finalizar.criarpdf');
    Route::post('/gerente/mudarcomisao/corretor/gerente',[GerenteController::class,'mudarComissaoCorretor'])->name('gerente.mudar.valor.corretor');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/gerente/antecipar/parcela',[GerenteController::class,'aptarPagamento'])->name('gerente.aptar.pagamento');
    Route::get('/gerente/comissao/empresarial/confirmadas/{id}/{mes?}/{ano?}',[GerenteController::class,'comissaoListagemConfirmadasEmpresarial'])->name('gerente.listagem.empresarial.confirmadas');

});

require __DIR__.'/auth.php';
