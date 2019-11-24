<?php

namespace App\Http\Controllers;

use Request;
use File;
use Auth;

class AssinaturaController extends Controller {

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
                            ->with('acao', action('AssinaturaController@assinatura'))
                            ->with('name', 'assinatura')
                            ->with('value', "A");
                }
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'ARQUIVO INVÁLIDO!')
                    ->with('msg', 'Arquivo de assinatura deve ser no formato PNG!')
                    ->with('acao', action('AssinaturaController@assinatura'))
                    ->with('name', 'assinatura')
                    ->with('value', "A");
            } else {
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UMA IMAGEM!')
                    ->with('msg', 'Arquivo de assinatura não selecionado!')
                    ->with('acao', action('AssinaturaController@assinatura'))
                    ->with('name', 'assinatura')
                    ->with('value', "A");
            }
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