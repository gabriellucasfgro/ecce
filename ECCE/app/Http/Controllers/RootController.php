<?php

namespace App\Http\Controllers;

use Request;
use Auth;
use App\Funcionario;

class RootController extends Controller {
    
    public function funcionarios() {
        if(verifyAuth()) {
            $funcionarios = Funcionario::all();
            return view('administrarFuncionarios')->with('funcionarios', $funcionarios);
        }
    }

    public function alterarFuncionario($id) {
        if(verifyAuth()) {
            $funcionario = Funcionario::find($id);
            return view('alterarFuncionario')->with('funcionario', $funcionario);
        }
    }

    public function atualizarFuncionario($id) {
        if(verifyAuth()) {
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
                ->with('name', 'root')
                ->with('value', "R");
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