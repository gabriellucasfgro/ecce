<?php

namespace App\Http\Controllers;

use Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Aluno;

class SecretariaController extends Controller {

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
            $cursos = DB::select("SELECT curso FROM alunos GROUP BY curso");
            $anos = DB::select("SELECT ano FROM alunos GROUP BY ano");
            return view('manterAlunos')->with('alunos', $alunos)->with('cursos', $cursos)->with('anos', $anos);
        } else {
            return view('entrar')->with('erro_secretaria', 'Credenciais inválidas!');
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