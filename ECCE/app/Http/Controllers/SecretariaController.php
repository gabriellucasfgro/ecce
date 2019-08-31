<?php

namespace App\Http\Controllers;

use Request;
use Importer;
use Exporter;
use Validator;
use File;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;
use App\Aluno;
use App\Funcionario;
use App\Carteirinha;
use App\Alteracoes;
use PHPJasper\PHPJasper;
use Khill\Lavacharts\Lavacharts;

class SecretariaController extends Controller {

    public function manterAlunos() {
        if(verifyAuth()) {
            $cursos = DB::select("CALL listarCursos()");
            $anos = DB::select("CALL listarAnos()");
        	$alunos = Aluno::all();
        	return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
        }
    }

    public function manterCarteirinhas() {
        if(verifyAuth()) {
            $cursos = DB::select("CALL listarCursos()");
            $anos = DB::select("CALL listarAnos()");
        	$alunos_carteirinhas = DB::select("CALL listarAlunosCarteirinhas()");
        	return view('manterCarteirinhas')->with('alunos_carteirinhas', $alunos_carteirinhas)->with('cursos', $cursos)->with('anos', $anos);
        }
    }

    public function importar() {
        if(verifyAuth()) {
            return view('importarAlunos');
        }
    }

    public function solicitacao() {
        if(verifyAuth()) {
            $alteracoes = Alteracoes::all();
            return view('solicitacoesAlteracao')->with('alteracoes', $alteracoes);
        }
    }

    public function funcionarios() {
        if(verifyAuth()) {
            $funcionarios = Funcionario::all();
            return view('administrarFuncionarios')->with('funcionarios', $funcionarios);
        }
    }

    public function sair() {
        if(verifyAuth()) {
            Auth::guard("secretaria")->logout();
            return view('entrar');
        }
    }

    public function autenticar() {

        Request::merge([
            'matricula' => Request::input('matricula_funcionario'),
            'password' => Request::input('password_funcionario'),
        ]);

        $credentials = Request::only('matricula', 'password');

        if (Auth::guard('secretaria')->attempt($credentials)) {
            $alunos = Aluno::all();
            $cursos = DB::select("CALL listarCursos()");
            $anos = DB::select("CALL listarAnos()");
            return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
        } else {
            return view('entrar')->with('erro_secretaria', 'Credenciais inválidas!');
        }
    }

    public function importarTurma() {
        if(verifyAuth()) {
            if(Request::hasFile('arquivo')) {
               $extension = File::extension(Request::file('arquivo')->getClientOriginalName());
               if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                    set_time_limit(3600);

                    $dateTime = date('Ymd_His');
                    $file = Request::file('arquivo');
                    $fileName = $dateTime . '-' . $file->getClientOriginalName();
                    $savePath = public_path('/upload/alunos/');
                    $file->move($savePath, $fileName);

                    $excel = Importer::make('Excel');
                    $excel->load($savePath.$fileName);
                    $collection = $excel->getCollection();

                    for($col=0; $col<sizeof($collection[0]); $col++) {
                        if($collection[0][$col] == 'MATRICULA' ) {
                            $colunaMatricula = $col;
                        } else if($collection[0][$col] == 'NOME') {
                            $colunaNome = $col;
                        } else if($collection[0][$col] == 'CPF') {
                            $colunaCpf = $col;
                        } else if($collection[0][$col] == 'DATA NASCIMENTO') {
                            $colunaNascimento = $col;
                        } else if($collection[0][$col] == 'CURSO') {
                            $colunaCurso = $col;
                        } else if($collection[0][$col] == 'ANO') {
                            $colunaAno = $col;
                        } else if($collection[0][$col] == 'RG') {
                            $colunaRg = $col;
                        } else if($collection[0][$col] == 'CAMPUS') {
                            $colunaCampus = $col;
                        } else if($collection[0][$col] == 'MODALIDADE') {
                            $colunaModalidade = $col;
                        } else if($collection[0][$col] == 'NATURALIDADE') {
                            $colunaNaturalidade = $col;
                        } else if($collection[0][$col] == 'VALIDADE') {
                            $colunaValidade = $col;
                        } else if($collection[0][$col] == 'EMISSÃO') {
                            $colunaEmissao = $col;
                        }
                    }

                    for($row=1; $row<sizeof($collection); $row++) {
                        if( $collection[$row][$colunaMatricula] != ''
                            && $collection[$row][$colunaNome] != ''
                            && $collection[$row][$colunaCpf] != ''
                            && $collection[$row][$colunaRg] != ''
                            && $collection[$row][$colunaNascimento] != ''
                            && $collection[$row][$colunaCurso] != '' 
                            && $collection[$row][$colunaAno] != ''
                            && $collection[$row][$colunaValidade] != ''
                            && $collection[$row][$colunaEmissao] != '') {

                            if(Aluno::where('matricula', $collection[$row][$colunaMatricula])->first() == null) {
                                $objAluno = new Aluno();
                            }else {
                                $objAluno = Aluno::find($collection[$row][$colunaMatricula]);
                            }

                            $objAluno->matricula = $collection[$row][$colunaMatricula];
                            $objAluno->nome = $collection[$row][$colunaNome];
                            $objAluno->cpf = $collection[$row][$colunaCpf];
                            $objAluno->rg = $collection[$row][$colunaRg];
                            $objAluno->nascimento = $collection[$row][$colunaNascimento];
                            $objAluno->curso = $collection[$row][$colunaCurso];
                            $objAluno->ano = $collection[$row][$colunaAno];
                            $objAluno->campus = $collection[$row][$colunaCampus];
                            $objAluno->modalidade = $collection[$row][$colunaModalidade];
                            $objAluno->naturalidade = $collection[$row][$colunaNaturalidade];
                            $pwd = preg_replace("/[^0-9]/", "", $collection[$row][$colunaCpf]);
                            $pwd = str_pad($pwd, 11, '0', STR_PAD_LEFT);
                            $objAluno->password = bcrypt($pwd);
                            $objAluno->save();

                            $objCarteirinha = new Carteirinha();
                            $objCarteirinha->aluno_matricula = $collection[$row][$colunaMatricula];
                            $objCarteirinha->validade = $collection[$row][$colunaValidade];
                            $objCarteirinha->emissao = $collection[$row][$colunaEmissao];
                            $objCarteirinha->save();

                        }
                    }
                    
                    return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                        ->with('titulo', 'ARQUIVO IMPORTADO!')
                        ->with('msg', 'Arquivo foi importado com sucesso!')
                        ->with('acao', action('SecretariaController@importar'))
                        ->with('name', 'importar')
                        ->with('value', "I");
                } else {
                    return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                        ->with('titulo', 'ARQUIVO INVÁLIDO!')
                        ->with('msg', 'Arquivo não foi importado!')
                        ->with('acao', action('SecretariaController@importar'))
                        ->with('name', 'importar')
                        ->with('value', "I");
                }
            } else {
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UM ARQUIVO!')
                    ->with('msg', 'Arquivo não foi selecionado!')
                    ->with('acao', action('SecretariaController@importar'))
                    ->with('name', 'importar')
                    ->with('value', "I");
            }
        }
    }

    public function exportar() {
        if(verifyAuth()) {
            $turmas = DB::select('CALL listarTurmas()');
            return view('exportarAlunos')->with('turmas', $turmas);
        }
    }

    public function exportarTurma($curso, $ano) {
        if(verifyAuth()) {
            $alunos = Aluno::where('curso', $curso)->where('ano', $ano)->get();
            $excel = Exporter::make('Excel');
            $excel->load($alunos);
            $excel->stream($curso.'_'.$ano.'.xlsx');
        }
    }

    public function visualisarAluno($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::where('matricula', $matricula)->first();
            $carteirinha = Carteirinha::where('aluno_matricula', $matricula)->first();
            return view('visualisarAluno')->with('aluno', $aluno)->with('carteirinha', $carteirinha);
        }
    }

    public function visualisarAlteracao($matricula) {
        if(verifyAuth()) {
            $alteracao = Alteracoes::where('matricula', $matricula)->first();
            return view('visualisarAlteracao')->with('alteracao', $alteracao);
        }
    }

    public function validarAlteracao() {
        if(verifyAuth()) {
            $aluno = Aluno::find(Request::input('matricula'));
            if(isset($_POST['nome']) && Request::input('nome') != $aluno->nome) {
                $aluno->nome = Request::input('nome');
                $aluno->save();
            } 
            if(isset($_POST['curso']) && Request::input('curso') != $aluno->curso) {
                $aluno->curso = Request::input('curso');
                $aluno->save();
            }
            if(isset($_POST['ano']) && Request::input('ano') != $aluno->ano) {
                $aluno->ano = Request::input('ano');
                $aluno->save();
            }
            if(isset($_POST['campus']) && Request::input('campus') != $aluno->campus) {
                $aluno->campus = Request::input('campus');
                $aluno->save();
            }
            if(isset($_POST['modalidade']) && Request::input('modalidade') != $aluno->modalidade) {
                $aluno->modalidade = Request::input('modalidade');
                $aluno->save();
            }
            if(isset($_POST['cpf']) && Request::input('cpf') != $aluno->cpf) {
                $aluno->cpf = Request::input('cpf');
                $pwd = preg_replace("/[^0-9]/", "", $aluno->cpf);
                $pwd = str_pad($pwd, 11, '0', STR_PAD_LEFT);
                $aluno->password = bcrypt($pwd);
                $aluno->save();
            }
            if(isset($_POST['rg']) && Request::input('rg') != $aluno->rg) {
                $aluno->rg = Request::input('rg');
                $aluno->save();
            }
            if(isset($_POST['naturalidade']) && Request::input('naturalidade') != $aluno->naturalidade) {
                $aluno->naturalidade = Request::input('naturalidade');
                $aluno->save();
            }
            if(!is_null(($_POST['nascimento'])) && isRealDate($_POST['nascimento']) && Request::input('nascimento') != $aluno->nascimento) {
                $date = date_create(Request::input('nascimento'));
                $aluno->nascimento = date_format($date,"d/m/Y");
                $aluno->save();
            }
            if(Request::hasFile('foto')) {
                $extension = File::extension(Request::file('foto')->getClientOriginalName());
                if ($extension == "png") {
                    if(file_exists(public_path('/upload/fotos/').$aluno->matricula.'_alteracao.png')) {
                        unlink(public_path('/upload/fotos/').$aluno->matricula.'_alteracao.png');
                    }

                    $file = Request::file('foto');
                    $fileName = $aluno->matricula.'_foto.'.$file->getClientOriginalExtension();
                    $savePath = public_path('/upload/fotos/');
                    $file->move($savePath, $fileName);

                    $aluno->foto = $fileName;
                    $aluno->save();

                } else {
                    return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                        ->with('titulo', 'IMAGEM INVÁLIDA!')
                        ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG')
                        ->with('acao', action('SecretariaController@solicitacao'))
                        ->with('name', 'solicitacao')
                        ->with('value', "S");
                }
            } else if(file_exists(public_path('/upload/fotos/').$aluno->matricula.'_alteracao.png')) {
                rename(public_path('/upload/fotos/').$aluno->matricula.'_alteracao.png', public_path('/upload/fotos/').$aluno->matricula.'_foto.'.'png');
                $aluno->foto = $aluno->matricula.'_foto.'.'png';
                $aluno->save();
            }
            
            Alteracoes::destroy($aluno->matricula);

            return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                    ->with('titulo', 'ALTERAÇÃO VALIDADA!')
                    ->with('msg', 'Os dados do aluno foram atualizados!')
                    ->with('acao', action('SecretariaController@solicitacao'))
                    ->with('name', 'solicitacao')
                    ->with('value', "S");
        } 

    }

    public function recusarAlteracao($matricula) {
        Alteracoes::destroy($matricula);
        if(file_exists(public_path('/upload/fotos/').$matricula.'_alteracao.png')) {
            unlink(public_path('/upload/fotos/').$matricula.'_alteracao.png');
        }
        return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                    ->with('titulo', 'ALTERAÇÃO INVALIDADA!')
                    ->with('msg', 'Os dados do aluno foram mantidos!')
                    ->with('acao', action('SecretariaController@solicitacao'))
                    ->with('name', 'solicitacao')
                    ->with('value', "S");
    }

    public function filtrarAlunos() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_geral"])) {
                $alunos = DB::select('CALL pesquisarAlunos(?)', array($_POST["filtro_geral"]));
                $cursos = DB::select("CALL listarCursos()");
                $anos = DB::select("CALL listarAnos()");
                return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
            }
        }
    }

    public function filtrarTurma() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_curso"]) && isset($_POST["filtro_ano"])) {
                $alunos = Aluno::where('curso', $_POST["filtro_curso"])->where('ano', $_POST["filtro_ano"])->get();
                $cursos = DB::select("CALL listarCursos()");
                $anos = DB::select("CALL listarAnos()");
                return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
            }
        }
    }

    public function filtrarCarteirinhas() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_geral"])) {
                $alunos_carteirinhas = DB::select('CALL pesquisarCarteirinhas(?)', array($_POST["filtro_geral"]));
                $cursos = DB::select("CALL listarCursos()");
                $anos = DB::select("CALL listarAnos()");
                return view('manterCarteirinhas')->with('alunos_carteirinhas', $alunos_carteirinhas)->with('cursos', $cursos)->with('anos', $anos);
            }
        }
    }

    public function filtrarCarteirinhasTurma() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_curso"]) && isset($_POST["filtro_ano"])) {
                $alunos_carteirinhas = DB::select('CALL listarAlunosCarteirinhasTurma(?, ?)', array($_POST["filtro_curso"], $_POST["filtro_ano"]));
                $cursos = DB::select("CALL listarCursos()");
                $anos = DB::select("CALL listarAnos()");
                return view('manterCarteirinhas')->with('alunos_carteirinhas', $alunos_carteirinhas)->with('cursos', $cursos)->with('anos', $anos)->with('filtro_ano', $_POST["filtro_ano"])->with('filtro_curso', $_POST["filtro_curso"]);
            }
        }
    }

    public function imprimir($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::find($matricula);

            $frente = public_path() . '/reports/frente.png';
            $verso = public_path() . '/reports/verso.png';
            $assinatura = public_path() . '/upload/assinatura/assinatura.png';

            if($aluno->foto != null) {
                $foto = public_path() . '/upload/fotos/' . $aluno->foto;
            } else {
                $foto = public_path() . '/img/default.png';
            }

            $input = public_path() . '/reports/carteirinhaIFPR.jasper';   
            $output = public_path() . '/reports/' . time() . '_carteirinha';
            $options = [
                'format' => ['pdf'],
                'params' => [
                                'mat' => $matricula,
                                'frente' => $frente,
                                'verso' => $verso,
                                'foto' => $foto,
                                'assinatura' => $assinatura
                            ],
                'db_connection' => [
                    'driver' => env('DB_CONNECTION'),
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'host' => env('DB_HOST'),
                    'database' => env('DB_DATABASE'),
                    'port' => env('DB_PORT'),
                ]
            ];

            $report = new PHPJasper;

            $report->process(
                $input,
                $output,
                $options
            )->execute();
            
            $file = $output.'.pdf';
            $path = $file;

            if (!file_exists($file)) {
                abort(404);
            }
            $file = file_get_contents($file);

            return response()->file($path)->deleteFileAfterSend(true);
        }
    }

    public function imprimirTurma($curso, $ano) {
        if(verifyAuth()) {
            $frente = public_path() . '/reports/frente.png';
            $verso = public_path() . '/reports/verso.png';
            $caminho = public_path() . '/upload/fotos/';
            $assinatura = public_path() . '/upload/assinatura/assinatura.png';

            $input = public_path() . '/reports/carteirinhaIFPR_turma.jasper';   
            $output = public_path() . '/reports/' . time() . '_carteirinhas_' . $curso . '_' . $ano;
            $options = [
                'format' => ['pdf'],
                'params' => [
                                'curso' => $curso,
                                'ano' => $ano,
                                'frente' => $frente,
                                'verso' => $verso,
                                'caminho' => $caminho,
                                'assinatura' => $assinatura
                            ],
                'db_connection' => [
                    'driver' => env('DB_CONNECTION'),
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'host' => env('DB_HOST'),
                    'database' => env('DB_DATABASE'),
                    'port' => env('DB_PORT'),
                ]
            ];

            $report = new PHPJasper;

            $report->process(
                $input,
                $output,
                $options
            )->execute();
            
            $file = $output.'.pdf';
            $path = $file;

            if (!file_exists($file)) {
                abort(404);
            }
            $file = file_get_contents($file);

            return response()->file($path)->deleteFileAfterSend(true);
        } 
    }

    public function alterarAluno($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::find($matricula);
            return view('alterarAluno')->with('aluno', $aluno);
        }
    }

    public function atualizarImagem($matricula) {
        if(verifyAuth()) {
            if(Request::hasFile('foto')) {
               $extension = File::extension(Request::file('foto')->getClientOriginalName());
               if ($extension == "png") {
                    
                    $aluno = Aluno::find($matricula);

                    $file = Request::file('foto');
                    $fileName = $aluno->matricula.'_foto.'.$file->getClientOriginalExtension();
                    $savePath = public_path('/upload/fotos/');
                    $file->move($savePath, $fileName);

                    $aluno->foto = $fileName;
                    $aluno->save();

                    return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                            ->with('titulo', 'FOTO SALVA!')
                            ->with('msg', 'Arquivo de imagem foi salvo com sucesso!')
                            ->with('acao', action('SecretariaController@alterarAluno', ['matricula' => $matricula]))
                            ->with('name', 'alunos')
                            ->with('value', "A");
                }
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ARQUIVO INVÁLIDO!')
                    ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG')
                    ->with('acao', action('SecretariaController@manterAlunos'))
                    ->with('name', 'alunos')
                    ->with('value', "A");
            } else {
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UMA IMAGEM!')
                    ->with('msg', 'Arquivo de imagem não selecionado!')
                    ->with('acao', action('SecretariaController@manterAlunos'))
                    ->with('name', 'alunos')
                    ->with('value', "A");
            }
        }
    }

    public function atualizarDados($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::find($matricula);

            if(isset($_POST['nome']) && Request::input('nome') != $aluno->nome) {
                $aluno->nome = Request::input('nome');
                $aluno->save();
            } 
            if(isset($_POST['curso']) && Request::input('curso') != $aluno->curso) {
                $aluno->curso = Request::input('curso');
                $aluno->save();
            }
            if(isset($_POST['ano']) && Request::input('ano') != $aluno->ano) {
                $aluno->ano = Request::input('ano');
                $aluno->save();
            }
            if(isset($_POST['campus']) && Request::input('campus') != $aluno->campus) {
                $aluno->campus = Request::input('campus');
                $aluno->save();
            }
            if(isset($_POST['modalidade']) && Request::input('modalidade') != $aluno->modalidade) {
                $aluno->modalidade = Request::input('modalidade');
                $aluno->save();
            }
            if(isset($_POST['cpf']) && Request::input('cpf') != $aluno->cpf) {
                $aluno->cpf = Request::input('cpf');
                $aluno->save();
            }
            if(isset($_POST['rg']) && Request::input('rg') != $aluno->rg) {
                $aluno->rg = Request::input('rg');
                $aluno->save();
            }
            if(isset($_POST['naturalidade']) && Request::input('naturalidade') != $aluno->naturalidade) {
                $aluno->naturalidade = Request::input('naturalidade');
                $aluno->save();
            }
            if(!is_null(($_POST['nascimento'])) && isRealDate($_POST['nascimento']) && Request::input('nascimento') != $aluno->nascimento) {
                $aluno->nascimento = Request::input('nascimento');
                $aluno->save();
            }

            return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                ->with('titulo', 'DADOS ALTERADOS!')
                ->with('msg', 'Os dados alterados foram salvos!')
                ->with('acao', action('SecretariaController@alterarAluno', ['matricula' => $matricula]))
                ->with('name', 'alunos')
                ->with('value', "A");
        }
    }

    public function assinatura() {
        if(verifyAuth()) {
            return view('alterarAssinatura');
        }
    }

    public function atualizarAssinatura() {
        if(verifyAuth()) {
            if(Request::hasFile('assinatura')) {
               $extension = File::extension(Request::file('assinatura')->getClientOriginalName());
               if ($extension == "png") {

                    $file = Request::file('assinatura');
                    $fileName = 'assinatura.'.$file->getClientOriginalExtension();
                    $savePath = public_path('/upload/assinatura/');
                    $file->move($savePath, $fileName);

                    return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                            ->with('titulo', 'ASSINATURA ATUALIZADA!')
                            ->with('msg', 'Arquivo de assinatura foi salvo com sucesso!')
                            ->with('acao', action('SecretariaController@assinatura'))
                            ->with('name', 'assinatura')
                            ->with('value', "A");
                }
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ARQUIVO INVÁLIDO!')
                    ->with('msg', 'Arquivo de assinatura deve ser no formato PNG!')
                    ->with('acao', action('SecretariaController@assinatura'))
                    ->with('name', 'assinatura')
                    ->with('value', "A");
            } else {
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UMA IMAGEM!')
                    ->with('msg', 'Arquivo de assinatura não selecionado!')
                    ->with('acao', action('SecretariaController@assinatura'))
                    ->with('name', 'assinatura')
                    ->with('value', "A");
            }
        }
    }

    public function alterarFuncionario($id) {
        $funcionario = Funcionario::find($id);
        return view('alterarFuncionario')->with('funcionario', $funcionario);
    }

    public function atualizarFuncionario($id) {
        $funcionario = Funcionario::find($id);
        if(isset($_POST['nome']) && Request::input('nome') != $funcionario->nome) {
            $funcionario->nome = Request::input('nome');
            $funcionario->save();
        } 
        if(isset($_POST['matricula']) && Request::input('matricula') != $funcionario->matricula) {
            if(Funcionario::where('matricula', $_POST['matricula'])->first() == null) {
                $funcionario->matricula = Request::input('matricula');
                $funcionario->save();
            }
        }
        if(isset($_POST['password']) && Request::input('password') != $funcionario->password) {
            $funcionario->password = bcrypt(Request::input('password'));
            $funcionario->save();
        }
        return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                ->with('titulo', 'DADOS ALTERADOS!')
                ->with('msg', 'Os dados alterados foram salvos!')
                ->with('acao', action('SecretariaController@alterarFuncionario', ['id' => $id]))
                ->with('name', 'funcionario')
                ->with('value', "F");;
    }
    
}
function verifyAuth() {
    if(Auth::guard('secretaria')->check()) {
        if(Auth::guard('secretaria')->user()->tipo() == 'secretaria') { 
            return true; 
        }
    }
    
    abort(403, "Acesso não autorizado!");
}

function isRealDate($date) { 
    if (false === strtotime($date)) { 
        return false;
    } 
    list($year, $month, $day) = explode('-', $date); 
    return checkdate($month, $day, $year);
}