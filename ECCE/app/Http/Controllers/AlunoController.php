<?php

namespace App\Http\Controllers;


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

    public function registrar() {
        return view('registrarAluno');
    }

    public function registrarAluno() {

        $validator = Validator::make(Request::all(), [
            'matricula' => 'required|string|max:255|unique:alunos',
            'nome' => 'required|string|max:255',
            'curso' => 'required|string|max:255',
            'ano' => 'required|string|max:255',
            'cpf' => 'required|string|min:11|max:11|unique:alunos',
            'rg' => 'required|string|max:255',
            'nascimento' => 'required|string|max:255',
            'naturalidade' => 'required|string|max:255',
            'campus' => 'required|string|max:255',
            'modalidade' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()) {
            return view('registrarAluno')->with('errors', $validator->errors());
        }
        else {
            Aluno::create([
                'matricula' => Request::input('matricula'),
                'nome' => Request::input('nome'),
                'curso' => Request::input('curso'),
                'ano' => Request::input('ano'),
                'cpf' => Request::input('cpf'),
                'rg' => Request::input('rg'),
                'nascimento' => Request::input('nascimento'),
                'naturalidade' => Request::input('naturalidade'),
                'campus' => Request::input('campus'),
                'modalidade' => Request::input('modalidade'),
                'password' => bcrypt(Request::input('password')),
            ]);
            Carteirinha::create([
                'emissao' => date('d/m/Y'),
                'validade' => '29/02/2020',
            ]);
            return view('entrar')->with('aluno_criado', "Aluno registrado com sucesso!");
        }
    }

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
               if ($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
                    
                    $aluno = Auth::user();

                    $file = Request::file('foto');
                    $fileName = $aluno->matricula.'_foto.'.$file->getClientOriginalExtension();
                    $savePath = public_path('/upload/fotos/');
                    $file->move($savePath, $fileName);

                    $aluno->foto = $fileName;
                    $aluno->save();

                    //Request::session()->forget('aluno');
                    //Request::session()->put('aluno', $aluno);

                    return view('messageboxAluno')->with('tipo', 'alert alert-success')
                            ->with('titulo', 'FOTO SALVA!')
                            ->with('msg', 'Arquivo de imagem foi salvo com sucesso!')
                            ->with('acao', action('AlunoController@atualizar'))
                            ->with('name', 'atualizar')
                            ->with('value', "A");
                }
                return view('messageboxAluno')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ARQUIVO INVÁLIDO!')
                    ->with('msg', 'Arquivo de imagem inválido!')
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
            if(isset($_POST['mensagem'])) {
                $msg = Request::input('mensagem');

                $funcionarios = Funcionario::all();

                $dados['msg'] = $msg;
                $dados['aluno'] = Auth::user()->nome;

                foreach($funcionarios as $objFuncionario) {
                    $email = mb_strtolower($objFuncionario->email, 'UTF-8');
                    \Mail::to($email)->send(new SolicitarAlteracaoEmail("emailSolicitarAlteracao", $dados, "ECCE - Alteração de dados solicitada - ".Auth::user()->nome));
                    sleep(1);
                }

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