@extends('principalSecretaria')

@section('conteudo')
	<div class="jumbotron">
		<table class='table table-striped'>
			<thead>
				<tr>
				    <th>NOME</th>
				    <th>CURSO</th>
					<th>ANO</th>
					<th>DATA</th>
				    <th>ALTERAÇÕES</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($alteracoes as $dados)
				<tr>
				    <td>{{ $dados->nome }}</td>
				    <td>{{ $dados->curso }}</td>
					<td>{{ $dados->ano }}</td>
					<td>{{ $dados->data }}</td>
				    <td>
				    	<form action="{{ action('ValidarController@visualizarAlteracao', ['id' => $dados->id]) }}" method="POST">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
						<input type ="hidden" name="pendentes" value="P">
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