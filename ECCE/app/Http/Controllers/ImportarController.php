<?php

namespace App\Http\Controllers;

use Request;
use Importer;
use File;
use Auth;
use App\Aluno;
use App\Carteirinha;

class ImportarController extends Controller {

    public function importar() {
        if(verifyAuth()) {
            return view('importarAlunos');
        }
    }

    public function importarTurma() {
        if(verifyAuth()) {
            if(Request::hasFile('arquivo')) {
               $extension = File::extension(Request::file('arquivo')->getClientOriginalName());
               if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                    set_time_limit(3600);

                    $dateTime = date('Ymd_His');
                    $file = Request::file('arquivo');
                    $fileName = $dateTime . '-' . $file->getClientOriginalName();
                    $savePath = public_path('/upload/alunos/');
                    $file->move($savePath, $fileName);

                    $excel = Importer::make('Excel');
                    $excel->load($savePath.$fileName);
                    $collection = $excel->getCollection();

                    for($col=0; $col<sizeof($collection[0]); $col++) {
                        if($collection[0][$col] == 'MATRICULA' ) {
                            $colunaMatricula = $col;
                        } else if($collection[0][$col] == 'NOME') {
                            $colunaNome = $col;
                        } else if($collection[0][$col] == 'CPF') {
                            $colunaCpf = $col;
                        } else if($collection[0][$col] == 'DATA NASCIMENTO') {
                            $colunaNascimento = $col;
                        } else if($collection[0][$col] == 'CURSO') {
                            $colunaCurso = $col;
                        } else if($collection[0][$col] == 'ANO') {
                            $colunaAno = $col;
                        } else if($collection[0][$col] == 'RG') {
                            $colunaRg = $col;
                        } else if($collection[0][$col] == 'CAMPUS') {
                            $colunaCampus = $col;
                        } else if($collection[0][$col] == 'MODALIDADE') {
                            $colunaModalidade = $col;
                        } else if($collection[0][$col] == 'NATURALIDADE') {
                            $colunaNaturalidade = $col;
                        } else if($collection[0][$col] == 'VALIDADE') {
                            $colunaValidade = $col;
                        } else if($collection[0][$col] == 'EMISSÃO') {
                            $colunaEmissao = $col;
                        }
                    }

                    for($row=1; $row<sizeof($collection); $row++) {
                        if( $collection[$row][$colunaMatricula] != ''
                            && $collection[$row][$colunaNome] != ''
                            && $collection[$row][$colunaCpf] != ''
                            && $collection[$row][$colunaRg] != ''
                            && $collection[$row][$colunaNascimento] != ''
                            && $collection[$row][$colunaCurso] != '' 
                            && $collection[$row][$colunaAno] != ''
                            && $collection[$row][$colunaValidade] != ''
                            && $collection[$row][$colunaEmissao] != '') {

                            if(Aluno::where('matricula', $collection[$row][$colunaMatricula])->first() == null) {
                                $objAluno = new Aluno();
                                $objCarteirinha = new Carteirinha();
                            }else {
                                $objAluno = Aluno::find($collection[$row][$colunaMatricula]);
                                $objCarteirinha = Carteirinha::find($collection[$row][$colunaMatricula]);
                            }

                            $objAluno->matricula = $collection[$row][$colunaMatricula];
                            $objAluno->nome = $collection[$row][$colunaNome];
                            $objAluno->cpf = $collection[$row][$colunaCpf];
                            $objAluno->rg = $collection[$row][$colunaRg];
                            $objAluno->nascimento = $collection[$row][$colunaNascimento];
                            $objAluno->curso = $collection[$row][$colunaCurso];
                            $objAluno->ano = $collection[$row][$colunaAno];
                            $objAluno->campus = $collection[$row][$colunaCampus];
                            $objAluno->modalidade = $collection[$row][$colunaModalidade];
                            $objAluno->naturalidade = $collection[$row][$colunaNaturalidade];
                            $pwd = preg_replace("/[^0-9]/", "", $collection[$row][$colunaCpf]);
                            $pwd = str_pad($pwd, 11, '0', STR_PAD_LEFT);
                            $objAluno->password = bcrypt($pwd);
                            $objAluno->save();

                            $objCarteirinha->aluno_matricula = $collection[$row][$colunaMatricula];
                            $objCarteirinha->validade = $collection[$row][$colunaValidade];
                            $objCarteirinha->emissao = $collection[$row][$colunaEmissao];
                            $objCarteirinha->save();

                        }
                    }
                    
                    return view('messageboxSecretaria')->with('tipo', 'alert alert-success')
                        ->with('titulo', 'ARQUIVO IMPORTADO!')
                        ->with('msg', 'Arquivo foi importado com sucesso!')
                        ->with('acao', action('ImportarController@importar'))
                        ->with('name', 'importar')
                        ->with('value', "I");
                } else {
                    return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                        ->with('titulo', 'ARQUIVO INVÁLIDO!')
                        ->with('msg', 'Arquivo não foi importado!')
                        ->with('acao', action('ImportarController@importar'))
                        ->with('name', 'importar')
                        ->with('value', "I");
                }
            } else {
                return view('messageboxSecretaria')->with('tipo', 'alert alert-danger')
                    ->with('titulo', 'SELECIONE UM ARQUIVO!')
                    ->with('msg', 'Arquivo não foi selecionado!')
                    ->with('acao', action('ImportarController@importar'))
                    ->with('name', 'importar')
                    ->with('value', "I");
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