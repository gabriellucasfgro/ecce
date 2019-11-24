@extends('principalSecretaria')

@section('conteudo')
	<div class="jumbotron">
		<table class='table table-striped'>
			<thead>
				<tr>
				    <th>CURSO</th>
				    <th>ANO</th>
				    <th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($turmas as $dados)
				<tr>
				    <td>{{ $dados->curso }}</td>
				    <td>{{ $dados->ano }}</td>
				    <td>
				    	<form action="{{ action('ExportarController@exportarTurma', ['curso' => $dados->curso, 'ano' => $dados->ano]) }}" method="POST">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
						<input type ="hidden" name="exportar" value="E">
					    	<button type="submit" class="btn btn-xs btn-success">
					        	<i class="fas fa-file-export"></i>
					      	</button>
					    </form>
				    </td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@stop