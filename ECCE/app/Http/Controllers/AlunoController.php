<?php

namespace App\Http\Controllers;

use App\Alteracoes;
use Response;
use Request;
use Validator;
use File;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;
use App\Mail\SolicitarAlteracaoEmail;
use App\Mail;
use App\Aluno;
use App\Carteirinha;
use App\Funcionario;
use PHPJasper\PHPJasper;

class AlunoController extends Controller {

    public function atualizar() {
        if(verifyAuth()) {
            return view('atualizarDados');
        }
    }

    public function perfil() {
        if(verifyAuth()) {
            return view('home');
        }
    }

    public function sair() {
        if(verifyAuth()) {
            Auth::guard("aluno")->logout();
            return view('entrar');
        }
    }

    public function imprimir() {
        if(verifyAuth()) {
            $matricula = Auth::user()->matricula;

            $frente = public_path() . '/reports/frente.png';
            $verso = public_path() . '/reports/verso.png';
            $assinatura = public_path() . '/upload/assinatura/assinatura.png';
            $foto = public_path() . '/upload/fotos/' . Auth::user()->foto;
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
                        ->with('acao', action('AlunoController@atualizar'))
                        ->with('name', 'atualizar')
                        ->with('value', "A");
                }
                return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ARQUIVO INVÁLIDO!')
                    ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG!')
                    ->with('acao', action('AlunoController@atualizar'))
                    ->with('name', 'atualizar')
                    ->with('value', "A");
            } else {
                return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UMA IMAGEM!')
                    ->with('msg', 'Arquivo de imagem não selecionado!')
                    ->with('acao', action('AlunoController@atualizar'))
                    ->with('name', 'atualizar')
                    ->with('value', "A");
            }
        }
    }

    public function solicitarAlteracao() {
        if(verifyAuth()) {

            $aluno = Auth::user();

            if(Alteracoes::where('matricula', Auth::user()->matricula)->first() == null) {
                $alteracoes = new Alteracoes();
            } else {
                $alteracoes = Alteracoes::find(Auth::user()->matricula);
            }

            $alteracoes->matricula = Auth::user()->matricula;
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
                //$date = date_create(Request::input('nascimento'));
                $alteracoes->nascimento = Request::input('nascimento'); //date_format($date,"d/m/Y");
                $att = 1;
            }
            if(Request::hasFile('foto')) {
                $extension = File::extension(Request::file('foto')->getClientOriginalName());
                if ($extension == "png") {
                     
                    $file = Request::file('foto');
                    $fileName = $alteracoes->matricula.'_alteracao.'.$file->getClientOriginalExtension();
                    $savePath = public_path('/upload/fotos/');
                    $file->move($savePath, $fileName);

                    $alteracoes->foto = $fileName;
                    $att = 1;

                } else {
                    return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                        ->with('titulo', 'IMAGEM INVÁLIDA!')
                        ->with('msg', 'Arquivo de imagem inválido! Formato deve ser .PNG')
                        ->with('acao', action('AlunoController@atualizar'))
                        ->with('name', 'atualizar')
                        ->with('value', "A");
                }
            }

            if($att == 1) {
                $alteracoes->save();
                return view('messageboxAluno')->with('tipo', 'alert alert-success')
                    ->with('titulo', 'SOLICITAÇÃO ENVIADA!')
                    ->with('msg', 'Sua solicitação foi enviada e será análisada por um membro da secretaria!')
                    ->with('acao', action('AlunoController@atualizar'))
                    ->with('name', 'atualizar')
                    ->with('value', "A");
            } else {
                return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SOLICITAÇÃO NÃO ENVIADA!')
                    ->with('msg', 'Sua solicitação deve conter a mensagem!')
                    ->with('acao', action('AlunoController@atualizar'))
                    ->with('name', 'atualizar')
                    ->with('value', "A");
            }
        }
    }

    public function autenticar() {
        Request::merge([
            'matricula' => Request::input('matricula_aluno'),
            'password' => Request::input('cpf_aluno'),
        ]);

        $credentials = Request::only('matricula', 'password');

        if (Auth::guard('aluno')->attempt($credentials)) {
            $carteirinha = Carteirinha::where('aluno_matricula', Request::input('matricula'))->first();
            Request::session()->put('carteirinha', $carteirinha);
            return view('home');
        } else {
            return view('entrar')->with('erro_aluno', 'Credenciais inválidas!');
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