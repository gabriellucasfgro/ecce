<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Carteirinha;
use DateTime;

class ConsultaController extends Controller {

    public function consultarCarteirinha($matricula) {
        $aluno = Aluno::find($matricula);
        $carteirinha = Carteirinha::find($matricula);
        if($aluno != null && $carteirinha != null) {
            if(DateTime::createFromFormat('d/m/Y', $carteirinha->validade)->format('Y-m-d') < date("Y-m-d")) {
                return view('consultaCarteirinha')->with('aluno', $aluno)->with('carteirinha', $carteirinha)->with('status', 'invalido');
            }
            else {
                return view('consultaCarteirinha')->with('aluno', $aluno)->with('carteirinha', $carteirinha)->with('status', 'valido');
            }
        } else {
            return view('messagebox')->with('tipo', 'alert alert-danger')
                ->with('titulo', 'CARTEIRINHA NÃO ENCONTRADA!')
                ->with('msg', 'A matricula informada não foi encontrada!');
        }
    }
}

