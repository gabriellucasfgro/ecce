@extends('principalSecretaria')

@section('conteudo')
	<div class="jumbotron">
		<table class='table table-striped'>
			<thead>
				<tr>
				    <th>MATRICULA</th>
				    <th>NOME</th>
				    <th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($funcionarios as $dados)
				<tr>
				    <td>{{ $dados->matricula }}</td>
				    <td>{{ $dados->nome }}</td>
				    <td>
					    <form action="{{ action('SecretariaController@alterarFuncionario', ['id' => $dados->id]) }}" method="POST">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
					    	<button type="submit" class="btn btn-xs btn-success">
					        	<i class="fas fa-pencil-alt"></i>
					      	</button>
					    </form>
				    </td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@stop