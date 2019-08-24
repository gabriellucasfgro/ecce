@extends('principalSecretaria')

@section('conteudo')
	<div class="jumbotron">
		<table class='table table-striped'>
			<thead>
				<tr>
				    <th>NOME</th>
				    <th>CURSO</th>
				    <th>ANO</th>
				    <th>ALTERAÇÕES</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($alteracoes as $dados)
				<tr>
				    <td>{{ $dados->nome }}</td>
				    <td>{{ $dados->curso }}</td>
				    <td>{{ $dados->ano }}</td>
				    <td>
				    	<form action="{{ action('SecretariaController@visualisarAlteracao', ['id' => $dados->matricula]) }}" method="POST">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
						<input type ="hidden" name="solicitacao" value="S">
					    	<button type="submit" class="btn btn-xs btn-success">
                                <i class="fas fa-eye"></i>
					      	</button>
					    </form>
				    </td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@stop