<?php

namespace App\Http\Controllers;

use Request;
use File;
use Auth;
use App\Aluno;
use App\Alteracoes;
use Illuminate\Support\Facades\DB;

class ValidarController extends Controller {

    public function solicitacoesPendentes() {
        if(verifyAuth()) {
            $alteracoes = DB::select("SELECT * FROM alteracoes WHERE status = 1");
            return view('solicitacoesAlteracao')->with('alteracoes', $alteracoes);
        }
    }

    public function solicitacoesHistorico() {
        if(verifyAuth()) {
            $alteracoes = DB::select("SELECT * FROM alteracoes WHERE status = 0");
            return view('solicitacoesHistorico')->with('alteracoes', $alteracoes);
        }
    }

    public function visualizarAlteracao($id) {
        if(verifyAuth()) {
            $alteracao = Alteracoes::find($id);
            $aluno = Aluno::find($alteracao->aluno_matricula);
            return view('visualizarAlteracao')->with('alteracao', $alteracao)->with('aluno', $aluno);
        }
    }

    public function visualizarAlteracaoHistorico($id) {
        if(verifyAuth()) {
            $alteracao = Alteracoes::find($id);
            return view('visualizarAlteracaoHistorico')->with('alteracao', $alteracao);
        }
    }

    public function validarAlteracao($id) {
        if(verifyAuth()) {
            $alteracoes = Alteracoes::find($id);
            $aluno = Aluno::find($alteracoes->aluno_matricula);
            if(Request::hasFile('foto')) {
                $extension = File::extension(Request::file('foto')->getClientOriginalName());
                if ($extension == "png") {
                    $file = Request::file('foto');
                    $fileName = $aluno->matricula.'_foto.'.$file->getClientOriginalExtension();
                    $savePath = public_path('/upload/fotos/');
                    $file->move($savePath, $fileName);
                    $aluno->foto = $fileName;
                    $aluno->save();
                    copy(public_path('/upload/fotos/').$aluno->matricula.'_foto.'.'png' ,public_path('/upload/fotos/').$alteracoes->foto);
                } else {
                    return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                        ->with('titulo', 'IMAGEM INVÁLIDA!')
                        ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG')
                        ->with('acao', action('ValidarController@solicitacoesPendentes'))
                        ->with('name', 'pendentes')
                        ->with('value', "P");
                }
            } else if($alteracoes->foto != $aluno->foto) {
                copy(public_path('/upload/fotos/').$alteracoes->foto, public_path('/upload/fotos/').$aluno->matricula.'_foto.'.'png');
                $aluno->foto = $aluno->matricula.'_foto.'.'png';
                $aluno->save();
            }
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
            
            $alteracoes->status = 0;
            $alteracoes->aprovado = 1;
            $alteracoes->save();

            return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                    ->with('titulo', 'ALTERAÇÃO VALIDADA!')
                    ->with('msg', 'Os dados do aluno foram atualizados!')
                    ->with('acao', action('ValidarController@solicitacoesPendentes'))
                    ->with('name', 'pendentes')
                    ->with('value', "P");
        } 
    }

    public function recusarAlteracao($id) {
        $alteracoes = Alteracoes::find($id);
        $alteracoes->status = 0;
        $alteracoes->aprovado = 0;
        $alteracoes->save();

        return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                    ->with('titulo', 'ALTERAÇÃO INVALIDADA!')
                    ->with('msg', 'Os dados do aluno foram mantidos!')
                    ->with('acao', action('ValidarController@solicitacoesPendentes'))
                    ->with('name', 'pendentes')
                    ->with('value', "P");
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