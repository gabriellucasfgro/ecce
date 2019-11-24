<?php

namespace App\Http\Controllers;

use Exporter;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Aluno;

class ExportarController extends Controller {

    public function exportar() {
        if(verifyAuth()) {
            $turmas = DB::select("SELECT curso, ano FROM alunos GROUP BY curso, ano");
            return view('exportarAlunos')->with('turmas', $turmas);
        }
    }

    public function exportarTurma($curso, $ano) {
        if(verifyAuth()) {
            $alunos = Aluno::where('curso', $curso)->where('ano', $ano)->get();
            $excel = Exporter::make('Excel');
            $excel->load($alunos);
            $excel->stream($curso.'_'.$ano.'.xlsx');
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