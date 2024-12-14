<?php

use App\Http\Controllers\SYS\AutocompleteController;
use App\Http\Controllers\SYS\BancosController;
use App\Http\Controllers\SYS\CategoriasController;
use App\Http\Controllers\SYS\CentroCustoController;
use App\Http\Controllers\SYS\ClientesController;
use App\Http\Controllers\SYS\ContratoController;
use App\Http\Controllers\SYS\DepartamentoController;
use App\Http\Controllers\SYS\DREController;
use App\Http\Controllers\SYS\EmpresaController;
use App\Http\Controllers\SYS\FilialController;
use App\Http\Controllers\SYS\FinanceiroController;
use App\Http\Controllers\SYS\FormaPagamentoController;
use App\Http\Controllers\SYS\FornecedorController;
use App\Http\Controllers\SYS\GrupoProcessoController;
use App\Http\Controllers\SYS\MessagesController;
use App\Http\Controllers\SYS\PdfController;
use App\Http\Controllers\SYS\ProcessoController;
use App\Http\Controllers\SYS\ProdutosController;
use App\Http\Controllers\SYS\RateioController;
use App\Http\Controllers\SYS\ReportController;
use App\Http\Controllers\SYS\SetupController;
use App\Http\Controllers\SYS\TipoCobrancaController;
use App\Http\Controllers\SYS\UsuariosController;
use App\Http\Controllers\SYS\VendasController;
use App\Http\Controllers\SYS\WorkFlowController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SYS\R2Controller;
use \App\Livewire\{
    AccountsPayable,
    PaymentRequest,
    Approvals,
    AccountsReceivable
};

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::get(
    '/',
    function () {
        return redirect('dashboard');
    }
);

Route::get('/payable', AccountsPayable::class)->name('contas-pagar.index')->middleware('auth');
Route::get('/payment-request', PaymentRequest::class)->name('payment-request.index')->middleware('auth');
Route::get('/approvals', Approvals::class)->name('approvals.index')->middleware('auth');
Route::get('/receivable', AccountsReceivable::class)->name('acccounts-receivable.index')->middleware('auth');

Route::view('inicio', 'inicio.inicio')->name('inicio')->middleware('auth');
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom');
Route::get('custom-login', [AuthController::class, 'index'])->name('relogin.custom');
Route::get('registration', [AuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [AuthController::class, 'customRegistration'])->name('register.custom');
Route::get('post-registration/{email}', [AuthController::class, 'postRegistration'])->name('post-registration');
Route::get('valida-cadastro/{token}', [AuthController::class, 'validaCadastro'])->name('valida-cadastro');
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');
Route::post('validate-token', [AuthController::class, 'tokenValidator'])->name('validate-token');
Route::post('revalidar-token', [AuthController::class, 'revalidarToken'])->name('revalidar-token');
Route::get('esqueceu-senha', [AuthController::class, 'esqueceuSenha'])->name('esqueceu-senha');
Route::post('recuperar-senha', [AuthController::class, 'recuperarSenha'])->name('recuperar-senha');
Route::get('nova-senha/{token}', [AuthController::class, 'novaSenha'])->name('nova-senha');
Route::post('enviar-nova-senha', [AuthController::class, 'enviarNovaSenha'])->name('enviar-nova-senha');
Route::post('revalidar-token-usuario', [AuthController::class, 'revalidarTokenUsuario'])->name('revalidar-token-usuario');
Route::resource('faturamento', ProcessoController::class);
Route::resource('usuarios', UsuariosController::class);
Route::get('usuarios/{id}/profile', [UsuariosController::class, 'profile'])->name('usuarios.profile');
Route::post('usuarios/upload-img', [UsuariosController::class, 'uploadImgPerfil'])->name('usuarios.upload-img');
Route::post('usuarios/update-pass', [UsuariosController::class, 'updatePass'])->name('usuarios.update-pass');
Route::get('usuarios/{usuario}/{tipo}/disable', [UsuariosController::class, 'disable'])->name('usuarios.disable');
Route::post('usuarios/linesGrid', [UsuariosController::class, 'gridLines'])->name('usuarios.grid-lines');
Route::resource('centrocusto', CentroCustoController::class);
Route::resource('processo', ProcessoController::class);
Route::post('processo-observacao', [ProcessoController::class, 'observacao'])->name('processo.observacao');
Route::get('processo/{id}/{vencimento}', [ProcessoController::class, 'showAprovacao'])->name('processo.aprovacao');
Route::post('processo-upload', [ProcessoController::class, 'upload'])->name('processo.upload');
Route::post('processo-valida-documento', [ProcessoController::class, 'validaDocumento'])->name('processo.valida.documento');
Route::post('processo-pendencia', [ProcessoController::class, 'pendenciaProcesso'])->name('processo.pendencia.documento');
Route::get('processo-pendentes', [ProcessoController::class, 'pendentes'])->name('processo.pendentes');
Route::post('processo-retira-pendencia', [ProcessoController::class, 'retiraPendenciaProcesso'])->name('processo.retira.pendencia');
Route::post('processo-pagamento', [ProcessoController::class, 'aprovaPagamento'])->name('processo.pagamento');
Route::get('processo-completo', [ProcessoController::class, 'processoCompleto'])->name('processo.completo');
Route::get('processo-busca', [ProcessoController::class, 'processoSearch'])->name('processo.busca');
Route::get('processo-consulta', [ProcessoController::class, 'consultaProcesso'])->name('processo.consulta');
Route::post('processo-update-pvv', [ProcessoController::class, 'updateProcessoVencimentoValor'])->name('processo.update-pvv');
Route::get('processo-protocolo/{processo}', [ProcessoController::class, 'processoProtocolo'])->name('processo.protocolo');
Route::post('processo-excluir-arquivo', [ProcessoController::class, 'processoExcluiArquivo'])->name('processo.excluir-arquivo');
Route::post('processo-baixar-arquivo', [ProcessoController::class, 'processoBaixarArquivo'])->name('processo.baixar-arquivo');
Route::put('processo-edit-pagamento/{id_processo}/{id_pvv}', [ProcessoController::class, 'processoEditPagamento'])->name('processo.edit-pagamento');
Route::get('processo-edit/{processo}/{data}', [ProcessoController::class, 'processoEdit'])->name('processo.editar-processo');
Route::get('processo-file/destroy-file', [ProcessoController::class, 'destroyFile'])->name('processo.destroyFile');
Route::resource('fornecedor', FornecedorController::class);
Route::resource('empresa', EmpresaController::class);
Route::post('complete-empresa', [AutocompleteController::class, 'completeEmpresa'])->name('autocomplete.empresa');
Route::post('complete-filial', [AutocompleteController::class, 'completeFilial'])->name('autocomplete.filial');
Route::post('complete-grupo', [AutocompleteController::class, 'completeGrupo'])->name('autocomplete.grupo');
Route::post('search-by-id', [AutocompleteController::class, 'searchGrupoById'])->name('autocomplete.grupo.byid');
Route::post('complete-fornecedor', [AutocompleteController::class, 'completeFornecedor'])->name('autocomplete.fornecedor');
Route::post('complete-usuario', [AutocompleteController::class, 'completeUsuario'])->name('autocomplete.usuario');
Route::post('consulta-cliente', [AutocompleteController::class, 'consultaCliente'])->name('autocomplete.cliente');
Route::post('consulta-cliente-typeahead', [AutocompleteController::class, 'clientesTypeAhead'])->name('autocomplete.cliente-typeahead');
Route::post('consulta-contrato-typeahead', [AutocompleteController::class, 'contratoTypeAhead'])->name('autocomplete.contrato-typeahead');
Route::post('consulta-produto-typeahead', [AutocompleteController::class, 'produtoTypeAhead'])->name('autocomplete.produto-typeahead');
Route::get('usuario-grupo', [AutocompleteController::class, 'usuarioPorGrupo'])->name('autocomplete.usuario-grupo');
Route::post('consulta-cnpj', [AutocompleteController::class, 'consultaCnpjRegistro'])->name('autocomplete.consulta-cnpj');
Route::post('get-sub-dre-name', [AutocompleteController::class, 'getSubDreName'])->name('autocomplete.get-sub-dre-name');
Route::post('get-centro-custo', [AutocompleteController::class, 'getCentroCusto'])->name('autocomplete.get-centro-custo');
Route::post('get-client', [AutocompleteController::class, 'getClient'])->name('autocomplete.get-client');
Route::post('get-products', [AutocompleteController::class, 'getProducts'])->name('autocomplete.get-products');
Route::post('get-method-of-payment', [AutocompleteController::class, 'getMethodOfPayment'])->name('autocomplete.get-method-of-payment');
Route::resource('filial', FilialController::class);
Route::get('filial/{filial}', [FilialController::class, 'createFilial'])->name('filial-create');
Route::resource('departamento', DepartamentoController::class);
Route::get('departamento/{departamento}', [DepartamentoController::class, 'createDepartamento'])->name('departamento-create');
Route::resource('grupoprocesso', GrupoProcessoController::class);
Route::resource('workflow', WorkFlowController::class);
Route::get('pdf/{pdf}', [PdfController::class, 'showPdfThumbnail'])->name('pdf-thumbnail');
Route::get('pdf-converter/{pdf}', [PdfController::class, 'pdf2Png'])->name('pdf-converter');
Route::get('pdf-dre/{periodo}/{startYear}/{endYear}', [PdfController::class, 'pdfDre'])->name('pdf-dre');
Route::get('pdf-fluxo-caixa/{date?}', [PdfController::class, 'pdfFluxoCaixa'])->name('pdf-fluxo-caixa');
Route::get('pdf-contas-receber', [PdfController::class, 'pdfContasReceber'])->name('pdf-contas-receber');
Route::get('pdf-contas-pagar', [PdfController::class, 'pdfContasPagar'])->name('pdf-contas-pagar');
Route::get('excel-fluxo-caixa/{date?}', [FinanceiroController::class, 'excelFluxoCaixa'])->name('excel-fluxo-caixa');
Route::get('excel-dre/{periodo}/{startYear}/{endYear}', [DREController::class, 'excelDre'])->name('excel-dre');
Route::get('excel-contas-receber', [ReportController::class, 'excelContasReceber'])->name('excel-contas-receber');
Route::get('excel-contas-pagar', [ReportController::class, 'excelContasPagar'])->name('excel-contas-pagar');
Route::get('financeiro-destroy-files', [FinanceiroController::class, 'destroyFile'])->name('financeiro.destroyFile');
Route::get('financeiro/fluxo-caixa/{mes?}', [FinanceiroController::class, 'fluxoCaixa'])->name('financeiro.fluxo-caixa');
Route::resource('financeiro', FinanceiroController::class);
Route::get('financeiro-controle', [FinanceiroController::class, 'controleFinanceiro'])->name('financeiro.controle');
Route::get('financeiro-busca', [FinanceiroController::class, 'buscaFinanceiro'])->name('financeiro.busca');
Route::get('financeiro-receber', [FinanceiroController::class, 'receberFinanceiro'])->name('financeiro.receber');
Route::get('financeiro-add-receber', [FinanceiroController::class, 'receberCadastar'])->name('financeiro.add-receber');
Route::get('financeiro-dashboard', [FinanceiroController::class, 'dashboardFinanceiro'])->name('financeiro.dashboard');
Route::get('financeiro-baixar-receber', [FinanceiroController::class, 'baixarReceber'])->name('financeiro.baixar-receber');
Route::get('financeiro-pegar-abas', [FinanceiroController::class, 'pegarAbas'])->name('financeiro.pegar-abas');
Route::post('financeiro/receber-lote', [FinanceiroController::class, 'receberLote'])->name('financeiro.receber-lote');
Route::resource('tipocobranca', TipoCobrancaController::class);
Route::resource('bancos', BancosController::class);
Route::post('bancos/select', [BancosController::class, 'select'])->name('bancos.select');
Route::resource('setup', SetupController::class);
Route::post('messages-alerts', [MessagesController::class, 'getAlerts'])->name('messages.alerts');
Route::post('messages-read', [MessagesController::class, 'setRead'])->name('messages.read');
Route::resource('forma-pagamento', FormaPagamentoController::class);
Route::resource('clientes', ClientesController::class);
Route::resource('categorias', CategoriasController::class);
Route::resource('rateio', RateioController::class);
Route::resource('contrato', ContratoController::class);
Route::post('contrato/baixar-arquivo', [ContratoController::class, 'baixarAquivo'])->name('contrato.baixar-arquivo');
Route::resource('produto', ProdutosController::class);
Route::get('relatorio/contas-receber', [ReportController::class, 'contasReceber'])->name('relatorio.contas-receber');
Route::get('relatorio/contas-receber-relatorio', [ReportController::class, 'contasReceberRelatorio'])->name('relatorio.contas-receber-relatorio');
Route::get('relatorio/grid-contas-receber', [ReportController::class, 'gridContasReceber'])->name('relatorio.grid-contas-receber');
Route::get('relatorio/index-contas-pagar', [ReportController::class, 'indexContasPagar'])->name('relatorio.index-contas-pagar');
Route::get('relatorio/relatorio-contas-pagar', [ReportController::class, 'gridContasPagar'])->name('relatorio.grid-contas-pagar');
Route::get('relatorio/contas-pagar', [ReportController::class, 'relatorioContasPagar'])->name('relatorio.contas-pagar');
Route::get('relatorio/dre/{periodo?}/{startYear?}/{endYear?}', [ReportController::class, 'indexReportDre'])->name('relatorio.dre');
Route::resource('relatorio', ReportController::class);
Route::resource('dre', DREController::class);
Route::resource('vendas', VendasController::class);
Route::get('vendas-imprimir/{venda}', [VendasController::class, 'imprimir'])->name('vendas.imprimir');
Route::get('/image/{any}', [R2Controller::class, 'fetch'])->where('any', '.*')->name('r2.img');
Route::get('/image/base64/{any}', [R2Controller::class, 'fetchBase64'])->where('any', '.*')->name('r2.img.base64');
Route::get('/teste/{id?}', [PdfController::class, 'desktopMiniatures'])->name('pdf.miniatures');
Route::get('/pdf/{pdfName}', [PdfController::class, 'viewerPdf'])->where('pdfName', '.*')->name('pdf.viewer');

Route::get('testr2', [R2Controller::class, 'teste'])->name('testr2');