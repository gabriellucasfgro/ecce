<?php

namespace App\Http\Controllers;

use Request;
use File;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Aluno;
use App\Carteirinha;

class ManterController extends Controller {

    public function manterAlunos() {
        if(verifyAuth()) {
            $cursos = DB::select("SELECT curso FROM alunos GROUP BY curso");
            $anos = DB::select("SELECT ano FROM alunos GROUP BY ano");
        	$alunos = Aluno::all();
        	return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
        }
    }

    public function manterCarteirinhas() {
        if(verifyAuth()) {
            $cursos = DB::select("SELECT curso FROM alunos GROUP BY curso");
            $anos = DB::select("SELECT ano FROM alunos GROUP BY ano");
        	$alunos_carteirinhas = DB::select("SELECT matricula, nome, curso, ano, validade, emissao 
                                                FROM alunos, carteirinhas 
                                                WHERE matricula = aluno_matricula");
        	return view('manterCarteirinhas')->with('alunos_carteirinhas', $alunos_carteirinhas)->with('cursos', $cursos)->with('anos', $anos);
        }
    }

    public function visualizarAluno($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::where('matricula', $matricula)->first();
            $carteirinha = Carteirinha::where('aluno_matricula', $matricula)->first();
            return view('visualizarAluno')->with('aluno', $aluno)->with('carteirinha', $carteirinha);
        }
    }

    public function filtrarAlunos() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_geral"])) {
                $alunos = DB::select('SELECT * FROM alunos
                                    WHERE nome LIKE CONCAT("%", ?, "%")
                                    OR matricula LIKE CONCAT("%", ?, "%")
                                    OR curso LIKE CONCAT("%", ?, "%")
                                    OR ano LIKE CONCAT("%", ?, "%")'
                , array($_POST["filtro_geral"], $_POST["filtro_geral"], $_POST["filtro_geral"], $_POST["filtro_geral"]));

                $cursos = DB::select("SELECT curso FROM alunos GROUP BY curso");
                $anos = DB::select("SELECT ano FROM alunos GROUP BY ano");
                return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
            }
        }
    }

    public function filtrarTurma() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_curso"]) && isset($_POST["filtro_ano"])) {
                $alunos = Aluno::where('curso', $_POST["filtro_curso"])->where('ano', $_POST["filtro_ano"])->get();
                $cursos = DB::select("SELECT curso FROM alunos GROUP BY curso");
                $anos = DB::select("SELECT ano FROM alunos GROUP BY ano");
                return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
            }
        }
    }

    public function filtrarCarteirinhas() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_geral"])) {
                $alunos_carteirinhas = DB::select('SELECT matricula, nome, curso, ano, validade, emissao
                                                    FROM alunos, carteirinhas
                                                    WHERE matricula = aluno_matricula
                                                    AND (
                                                        nome LIKE CONCAT("%", ?, "%")
                                                        OR matricula LIKE CONCAT("%", ?, "%")
                                                        OR curso LIKE CONCAT("%", ?, "%")
                                                        OR ano LIKE CONCAT("%", ?, "%")
                                                        )'
                , array($_POST["filtro_geral"], $_POST["filtro_geral"], $_POST["filtro_geral"], $_POST["filtro_geral"]));

                $cursos = DB::select("SELECT curso FROM alunos GROUP BY curso");
                $anos = DB::select("SELECT ano FROM alunos GROUP BY ano");
                return view('manterCarteirinhas')->with('alunos_carteirinhas', $alunos_carteirinhas)->with('cursos', $cursos)->with('anos', $anos);
            }
        }
    }

    public function filtrarCarteirinhasTurma() {
        if(verifyAuth()) {
            if(isset($_POST["filtro_curso"]) && isset($_POST["filtro_ano"])) {
                $alunos_carteirinhas = DB::select('SELECT matricula, nome, curso, ano, validade, emissao 
                                                FROM alunos, carteirinhas 
                                                WHERE matricula = aluno_matricula
                                                AND curso = ?
                                                AND ano = ?'
                , array($_POST["filtro_curso"], $_POST["filtro_ano"]));

                $cursos = DB::select("SELECT curso FROM alunos GROUP BY curso");
                $anos = DB::select("SELECT ano FROM alunos GROUP BY ano");
                return view('manterCarteirinhas')->with('alunos_carteirinhas', $alunos_carteirinhas)->with('cursos', $cursos)->with('anos', $anos)->with('filtro_ano', $_POST["filtro_ano"])->with('filtro_curso', $_POST["filtro_curso"]);
            }
        }
    }

    public function alterarAluno($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::find($matricula);
            return view('alterarAluno')->with('aluno', $aluno);
        }
    }

    public function alterarCarteirinha($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::find($matricula);
            $carteirinha = Carteirinha::find($matricula);
            return view('alterarCarteirinha')->with('aluno', $aluno)->with('carteirinha', $carteirinha);
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
                            ->with('acao', action('ManterController@alterarAluno', ['matricula' => $matricula]))
                            ->with('name', 'alunos')
                            ->with('value', "A");
                }
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ARQUIVO INVÁLIDO!')
                    ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG')
                    ->with('acao', action('ManterController@alterarAluno', ['matricula' => $matricula]))
                    ->with('name', 'alunos')
                    ->with('value', "A");
            } else {
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UMA IMAGEM!')
                    ->with('msg', 'Arquivo de imagem não selecionado!')
                    ->with('acao', action('ManterController@alterarAluno', ['matricula' => $matricula]))
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
                $date = date_create(Request::input('nascimento'));
                $aluno->nascimento = date_format($date,"d/m/Y");
                $aluno->save();
            }

            return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                ->with('titulo', 'DADOS ALTERADOS!')
                ->with('msg', 'Os dados alterados foram salvos!')
                ->with('acao', action('ManterController@alterarAluno', ['matricula' => $matricula]))
                ->with('name', 'alunos')
                ->with('value', "A");
        }
    }

    public function atualizarCarteirinha($matricula) {
        if(verifyAuth()) {
            $carteirinha = Carteirinha::find($matricula);

            if(!is_null(($_POST['emissao'])) && isRealDate($_POST['emissao']) && Request::input('emissao') != $carteirinha->emissao) {
                $date = date_create(Request::input('emissao'));
                $carteirinha->emissao = date_format($date,"d/m/Y");
                $carteirinha->save();
            }
            if(!is_null(($_POST['validade'])) && isRealDate($_POST['validade']) && Request::input('validade') != $carteirinha->validade) {
                $date = date_create(Request::input('validade'));
                $carteirinha->validade = date_format($date,"d/m/Y");
                $carteirinha->save();
            }

            return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                ->with('titulo', 'DADOS ALTERADOS!')
                ->with('msg', 'Os dados alterados foram salvos!')
                ->with('acao', action('ManterController@alterarCarteirinha', ['matricula' => $matricula]))
                ->with('name', 'carteirinhas')
                ->with('value', "C");
        }
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