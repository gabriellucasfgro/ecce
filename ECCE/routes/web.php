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

Route::get('/', function () {
    return view('entrar');
});
Route::get('/carteirinha/{matricula}', 'ConsultaController@consultarCarteirinha');
Route::get('/carteirinha/', 'ConsultaController@consultarCarteirinha');

//AUTENTICAR
Route::post('/secretaria/autenticar', 'SecretariaController@autenticar');
Route::post('/aluno/autenticar', 'AlunoController@autenticar');

//Rotas autenticadas // secretaria
//Route::group(['middleware' => 'auth:secretaria'], function() {
	Route::post('/secretaria/alunos/filtro_geral', 'SecretariaController@filtrarAlunos');
	Route::post('/secretaria/alunos/filtro_turma', 'SecretariaController@filtrarTurma');
	Route::post('/secretaria/carteirinhas/filtro_geral', 'SecretariaController@filtrarCarteirinhas');
	Route::post('/secretaria/carteirinhas/filtro_turma', 'SecretariaController@filtrarCarteirinhasTurma');
	Route::post('/secretaria/carteirinha/imprimirTurma/{curso}/{ano}', 'SecretariaController@imprimirTurma');
	Route::post('/secretaria/carteirinha/imprimir/{matricula}', 'SecretariaController@imprimir');
	Route::post('/secretaria/importar/alunos', 'SecretariaController@importarTurma');
	Route::post('/secretaria/exportar/alunos/{curso}/{turma}', 'SecretariaController@exportarTurma');
	Route::post('/secretaria/aluno/visualisar/{matricula}', 'SecretariaController@visualisarAluno');
	Route::post('/secretaria/aluno/alterar/{matricula}', 'SecretariaController@alterarAluno');
	Route::post('/secretaria/aluno/atualizar/imagem/{matricula}', 'SecretariaController@atualizarImagem');
	Route::post('/secretaria/aluno/atualizar/dados/{matricula}', 'SecretariaController@atualizarDados');
	Route::post('/secretaria/alunos', 'SecretariaController@manterAlunos');
	Route::post('/secretaria/carteirinhas', 'SecretariaController@manterCarteirinhas');
	Route::post('/secretaria/importar', 'SecretariaController@importar');
	Route::post('/secretaria/exportar', 'SecretariaController@exportar');
	Route::post('/secretaria/assinatura', 'SecretariaController@assinatura');
	Route::post('/secretaria/assinatura/alterar', 'SecretariaController@atualizarAssinatura');
	Route::post('/secretaria/solicitacoes', 'SecretariaController@solicitacao');
	Route::post('/secretaria/visualisar/alteracao/{matricula}', 'SecretariaController@visualisarAlteracao');
	Route::post('/secretaria/alteracao/validar', 'SecretariaController@validarAlteracao');
	Route::post('/secretaria/alteracao/recusar/{matricula}', 'SecretariaController@recusarAlteracao');
	Route::post('/secretaria/sair', 'SecretariaController@sair');
//});

//Rotas autenticadas // aluno
//Route::group(['middleware' => 'auth'], function() {
	Route::post('/aluno/perfil', 'AlunoController@perfil');
	Route::post('/aluno/atualizar', 'AlunoController@atualizar');
	Route::post('/aluno/atualizar/imagem', 'AlunoController@atualizarImagem');
	Route::post('/aluno/atualizar/solicitar', 'AlunoController@solicitarAlteracao');
	Route::post('/aluno/imprimir', 'AlunoController@imprimir');
	Route::post('/aluno/sair', 'AlunoController@sair');
//});