<?php

use App\Aluno;
use App\Carteirinha;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Inicio
Route::get('/', function () {
    return view('entrar');
});

//Consulta Controller
Route::get('/carteirinha/{matricula}', 'ConsultaController@consultarCarteirinha');
Route::get('/carteirinha/', 'ConsultaController@consultarCarteirinha');

// Secretaria Controller
Route::post('/secretaria/autenticar', 'SecretariaController@autenticar');
Route::post('/secretaria/sair', 'SecretariaController@sair');

// Aluno Controller
Route::post('/aluno/autenticar', 'AlunoController@autenticar');
Route::post('/aluno/perfil', 'AlunoController@perfil');
Route::post('/aluno/sair', 'AlunoController@sair');

//Rotas autenticadas - Secretaria
	//Manter Controller
	Route::post('/secretaria/alunos/filtro_geral', 'ManterController@filtrarAlunos');
	Route::post('/secretaria/alunos/filtro_turma', 'ManterController@filtrarTurma');
	Route::post('/secretaria/carteirinhas/filtro_geral', 'ManterController@filtrarCarteirinhas');
	Route::post('/secretaria/carteirinhas/filtro_turma', 'ManterController@filtrarCarteirinhasTurma');
	Route::post('/secretaria/aluno/visualizar/{matricula}', 'ManterController@visualizarAluno');
	Route::post('/secretaria/aluno/alterar/{matricula}', 'ManterController@alterarAluno');
	Route::post('/secretaria/aluno/atualizar/imagem/{matricula}', 'ManterController@atualizarImagem');
	Route::post('/secretaria/aluno/atualizar/dados/{matricula}', 'ManterController@atualizarDados');
	Route::post('/secretaria/alunos', 'ManterController@manterAlunos');
	Route::post('/secretaria/carteirinhas', 'ManterController@manterCarteirinhas');
	Route::post('/secretaria/carteirinha/alterar/{matricula}', 'ManterController@alterarCarteirinha');
	Route::post('/secretaria/carteirinha/atualizar/dados/{matricula}', 'ManterController@atualizarCarteirinha');
	//Imprimir Controller
	Route::post('/secretaria/carteirinha/imprimirTurma/{curso}/{ano}', 'ImprimirController@imprimirTurma');
	Route::post('/secretaria/carteirinha/imprimir/{matricula}', 'ImprimirController@imprimirAluno');
	Route::post('/aluno/imprimir', 'ImprimirController@imprimir');
	//Importar Controller
	Route::post('/secretaria/importar', 'ImportarController@importar');
	Route::post('/secretaria/importar/alunos', 'ImportarController@importarTurma');
	//Exportar Controller
	Route::post('/secretaria/exportar/alunos/{curso}/{turma}', 'ExportarController@exportarTurma');
	Route::post('/secretaria/exportar', 'ExportarController@exportar');
	//Assinatura Controller
	Route::post('/secretaria/assinatura', 'AssinaturaController@assinatura');
	Route::post('/secretaria/assinatura/alterar', 'AssinaturaController@atualizarAssinatura');
	//Alteracoes Controller
	Route::post('/secretaria/solicitacoes/pendentes', 'ValidarController@solicitacoesPendentes');
	Route::post('/secretaria/solicitacoes/historico', 'ValidarController@solicitacoesHistorico');
	Route::post('/secretaria/visualizar/alteracao/historico/{id}', 'ValidarController@visualizarAlteracaoHistorico');
	Route::post('/secretaria/visualizar/alteracao/{id}', 'ValidarController@visualizarAlteracao');
	Route::post('/secretaria/alteracao/validar/{id}', 'ValidarController@validarAlteracao');
	Route::post('/secretaria/alteracao/recusar/{id}', 'ValidarController@recusarAlteracao');
		//Root Controller
		Route::post('/root/funcionarios', 'RootController@funcionarios');
		Route::post('/root/funcionarios/alterar/{id}', 'RootController@alterarFuncionario');
		Route::post('/root/funcionario/alterar/salvar/{id}', 'RootController@atualizarFuncionario');

//Rotas autenticadas - Aluno
	//Alterar Controller
	Route::post('/aluno/atualizar', 'AlterarController@atualizar');
	Route::post('/aluno/atualizar/imagem', 'AlterarController@atualizarImagem');
	Route::post('/aluno/atualizar/solicitar', 'AlterarController@solicitarAlteracao');