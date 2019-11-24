<?php

namespace App\Http\Controllers;

use App\Alteracoes;
use Request;
use File;
use Auth;

class AlterarController extends Controller {

    public function atualizar() {
        if(verifyAuth()) {
            return view('atualizarDados');
        }
    }

    public function atualizarImagem() {
        if(verifyAuth()) {
            if(Request::hasFile('foto')) {
               $extension = File::extension(Request::file('foto')->getClientOriginalName());
               if ($extension == "png") {
                    
                    $aluno = Auth::user();

                    $file = Request::file('foto');
                    $fileName = $aluno->matricula.'_foto.'.$file->getClientOriginalExtension();
                    $savePath = public_path('/upload/fotos/');
                    $file->move($savePath, $fileName);

                    $aluno->foto = $fileName;
                    $aluno->save();

                    return view('messageboxAluno')->with('tipo', 'alert alert-success')
                        ->with('titulo', 'FOTO SALVA!')
                        ->with('msg', 'Arquivo de imagem foi salvo com sucesso!')
                        ->with('acao', action('AlterarController@atualizar'))
                        ->with('name', 'atualizar')
                        ->with('value', "A");
                }
                return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ARQUIVO INVÁLIDO!')
                    ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG!')
                    ->with('acao', action('AlterarController@atualizar'))
                    ->with('name', 'atualizar')
                    ->with('value', "A");
            } else {
                return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UMA IMAGEM!')
                    ->with('msg', 'Arquivo de imagem não selecionado!')
                    ->with('acao', action('AlterarController@atualizar'))
                    ->with('name', 'atualizar')
                    ->with('value', "A");
            }
        }
    }

    public function solicitarAlteracao() {
        if(verifyAuth()) {

            $aluno = Auth::user();

            if(Alteracoes::where([['aluno_matricula', '=', Auth::user()->matricula],['status', '=', 1]])->first() == null) {
                
                $alteracoes = new Alteracoes();
                $alteracoes->aluno_matricula = Auth::user()->matricula;
                $alteracoes->nome = Auth::user()->nome;
                $alteracoes->curso = Auth::user()->curso;
                $alteracoes->ano = Auth::user()->ano;
                $alteracoes->campus = Auth::user()->campus;
                $alteracoes->modalidade = Auth::user()->modalidade;
                $alteracoes->cpf = Auth::user()->cpf;
                $alteracoes->password = Auth::user()->password;
                $alteracoes->rg = Auth::user()->rg;
                $alteracoes->naturalidade = Auth::user()->naturalidade;
                $alteracoes->nascimento = Auth::user()->nascimento;
                $alteracoes->foto = Auth::user()->foto;
                
                $att = 0;
                if(isset($_POST['nome']) && Request::input('nome') != $aluno->nome) {
                    $alteracoes->nome = Request::input('nome');
                    $att = 1;
                } 
                if(isset($_POST['curso']) && Request::input('curso') != $aluno->curso) {
                    $alteracoes->curso = Request::input('curso');
                    $att = 1;
                }
                if(isset($_POST['ano']) && Request::input('ano') != $aluno->ano) {
                    $alteracoes->ano = Request::input('ano');
                    $att = 1;
                }
                if(isset($_POST['campus']) && Request::input('campus') != $aluno->campus) {
                    $alteracoes->campus = Request::input('campus');
                    $att = 1;
                }
                if(isset($_POST['modalidade']) && Request::input('modalidade') != $aluno->modalidade) {
                    $alteracoes->modalidade = Request::input('modalidade');
                    $att = 1;
                }
                if(isset($_POST['cpf']) && Request::input('cpf') != $aluno->cpf) {
                    $alteracoes->cpf = Request::input('cpf');
                    $pwd = preg_replace("/[^0-9]/", "", $alteracoes->cpf);
                    $pwd = str_pad($pwd, 11, '0', STR_PAD_LEFT);
                    $alteracoes->password = bcrypt($pwd);
                    $att = 1;
                }
                if(isset($_POST['rg']) && Request::input('rg') != $aluno->rg) {
                    $alteracoes->rg = Request::input('rg');
                    $att = 1;
                }
                if(isset($_POST['naturalidade']) && Request::input('naturalidade') != $aluno->naturalidade) {
                    $alteracoes->naturalidade = Request::input('naturalidade');
                    $att = 1;
                }
                if(!is_null(($_POST['nascimento'])) && isRealDate($_POST['nascimento']) && Request::input('nascimento') != $aluno->nascimento) {
                    $date = date_create(Request::input('nascimento'));
                    $alteracoes->nascimento = date_format($date,"d/m/Y");
                    $att = 1;
                }
                if(Request::hasFile('foto')) {
                    $extension = File::extension(Request::file('foto')->getClientOriginalName());
                    if ($extension == "png") {
                        
                        $file = Request::file('foto');
                        $fileName = $alteracoes->aluno_matricula.'_alteracao.'.date('Ymd_His.').$file->getClientOriginalExtension();
                        $savePath = public_path('/upload/fotos/');
                        $file->move($savePath, $fileName);

                        $alteracoes->foto = $fileName;
                        $att = 1;

                    } else {
                        return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                            ->with('titulo', 'IMAGEM INVÁLIDA!')
                            ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG')
                            ->with('acao', action('AlterarController@atualizar'))
                            ->with('name', 'atualizar')
                            ->with('value', "A");
                    }
                }

                if($att == 1) {
                    $alteracoes->data = date('d/m/Y');
                    $alteracoes->save();
                    return view('messageboxAluno')->with('tipo', 'alert alert-success')
                        ->with('titulo', 'SOLICITAÇÃO ENVIADA!')
                        ->with('msg', 'Sua solicitação foi enviada e será análisada por um membro da secretaria!')
                        ->with('acao', action('AlterarController@atualizar'))
                        ->with('name', 'atualizar')
                        ->with('value', "A");
                } else {
                    return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                        ->with('titulo', 'SOLICITAÇÃO NÃO ENVIADA!')
                        ->with('msg', 'Solicitação não apresenta alteração!')
                        ->with('acao', action('AlterarController@atualizar'))
                        ->with('name', 'atualizar')
                        ->with('value', "A");
                }
            } else {
                    return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                        ->with('titulo', 'SOLICITAÇÃO NÃO ENVIADA!')
                        ->with('msg', 'Sua conta ja possui uma solicitação esperando por validação!')
                        ->with('acao', action('AlterarController@atualizar'))
                        ->with('name', 'atualizar')
                        ->with('value', "A");
            }
        }
    }
}

function verifyAuth() {
    if(Auth::check()) {
        if(Auth::user()->tipo() == 'aluno') { 
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