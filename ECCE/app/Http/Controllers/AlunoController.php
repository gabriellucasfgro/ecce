<?php

namespace App\Http\Controllers;

use Request;
use Auth;
use App\Carteirinha;

class AlunoController extends Controller {

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