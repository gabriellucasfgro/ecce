@extends('principalSecretaria')

@section('conteudo')
    <div class="jumbotron">
        <form action="{{ action('RootController@atualizarFuncionario', ['id' => $funcionario->id]) }}" method="POST">
        <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
            <div class="row">
                <div class="col">
                    <label>Nome:</label>
                    <input type="text" class="form-control bg-light" name="nome" value="{{ $funcionario->nome }}">
                </div>
                <div class="col">
                    <label>Matricula/Login:</label>
                    @if($funcionario->matricula == 'root_ecce')
                        <input type="text" class="form-control bg-light" name="matricula" value="{{ $funcionario->matricula }}" readonly>
                    @else
                        <input type="text" class="form-control bg-light" name="matricula" value="{{ $funcionario->matricula }}">
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label>Senha:</label>
                    <input type="password" class="form-control bg-light" name="password" value="">
                </div>
            </div>
            <br>
            <div class="row">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fas fa-check"></i> Atualizar Funcionario
                </button>
            </div>
        </form>
    </div>
@stop