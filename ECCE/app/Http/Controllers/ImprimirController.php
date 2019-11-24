<?php

namespace App\Http\Controllers;

use Auth;
use App\Aluno;
use PHPJasper\PHPJasper;

class ImprimirController extends Controller {

    public function imprimir() {
        if(verifyAuthAluno()) {
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
                                'assinatura' => $assinatura,
                                'url' => request()->getHttpHost().'/carteirinha/'
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

    public function imprimirAluno($matricula) {
        if(verifyAuth()) {
            $aluno = Aluno::find($matricula);

            $frente = public_path() . '/reports/frente.png';
            $verso = public_path() . '/reports/verso.png';
            $assinatura = public_path() . '/upload/assinatura/assinatura.png';

            if($aluno->foto != null) {
                $foto = public_path() . '/upload/fotos/' . $aluno->foto;
            } else {
                $foto = public_path() . '/img/default.png';
            }

            $input = public_path() . '/reports/carteirinhaIFPR.jasper';   
            $output = public_path() . '/reports/' . time() . '_carteirinha';
            $options = [
                'format' => ['pdf'],
                'params' => [
                                'mat' => $matricula,
                                'frente' => $frente,
                                'verso' => $verso,
                                'foto' => $foto,
                                'assinatura' => $assinatura,
                                'url' => request()->getHttpHost().'/carteirinha/'
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

    public function imprimirTurma($curso, $ano) {
        if(verifyAuth()) {
            $frente = public_path() . '/reports/frente.png';
            $verso = public_path() . '/reports/verso.png';
            $caminho = public_path() . '/upload/fotos/';
            $assinatura = public_path() . '/upload/assinatura/assinatura.png';

            $input = public_path() . '/reports/carteirinhaIFPR_turma.jasper';   
            $output = public_path() . '/reports/' . time() . '_carteirinhas_' . $curso . '_' . $ano;
            $options = [
                'format' => ['pdf'],
                'params' => [
                                'curso' => $curso,
                                'ano' => $ano,
                                'frente' => $frente,
                                'verso' => $verso,
                                'caminho' => $caminho,
                                'assinatura' => $assinatura,
                                'url' => request()->getHttpHost().'/carteirinha/'
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
}
function verifyAuth() {
    if(Auth::guard('secretaria')->check()) {
        if(Auth::guard('secretaria')->user()->tipo() == 'secretaria') { 
            return true; 
        }
    }
    abort(403, "Acesso não autorizado!");
}

function verifyAuthAluno() {
    if(Auth::check()) {
        if(Auth::user()->tipo() == 'aluno') { 
            return true; 
        }
    }
    abort(403, "Acesso não autorizado!");
}