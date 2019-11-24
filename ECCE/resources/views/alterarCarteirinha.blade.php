@extends('principalSecretaria')

@section('conteudo')
    <div class="jumbotron">
        <div class="row">
            <div class="mx-auto justify-content-md-center">
                <figure>
                    <img src="/upload/fotos/{{ $aluno->foto }}" class="rounded-circle" height="140" width="140">
                </figure>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label>Nome:</label>
                <input type="text" class="form-control bg-light" name="" value="{{ $aluno->nome }}" readonly>
            </div>
            <div class="col">
                <label>Matricula:</label>
                <input type="text" class="form-control bg-light" name="" value="{{ $aluno->matricula }}" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Curso:</label>
                <input type="text" class="form-control bg-light" name="" value="{{ $aluno->curso }}" readonly>
            </div>
            <div class="col">
                <label>Ano:</label>
                <input type="text" class="form-control bg-light" name="" value="{{ $aluno->ano }}" readonly>
            </div>
        </div>
        <br>
        
        <br>
        
        <form action="{{ action('ManterController@atualizarCarteirinha', ['matricula' => $aluno->matricula]) }}" method="POST">
        <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
        <input type ="hidden" name="carteirinhas" value="C">
            <div class="row">
                <div class="col">
                    <label>Data de Emissão</label>
                    <input type="date" class="form-control bg-light" name="emissao" value="{{ DateTime::createFromFormat('d/m/Y', $carteirinha->emissao)->format('Y-m-d') }}">
                </div>
                <div class="col">
                    <label>Validade</label>
                    <input type="date" class="form-control bg-light" name="validade" value="{{ DateTime::createFromFormat('d/m/Y', $carteirinha->validade)->format('Y-m-d') }}">
                </div>
            </div>
            <br>
            <div class="row">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fas fa-check"></i> Salvar Dados
                </button>
            </div>
        </form>
    </div>
@stop