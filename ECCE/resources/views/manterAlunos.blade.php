@extends('principalSecretaria')

@section('menu')
	<div class="jumbotron">
		<form action="{{ action('SecretariaController@filtrarAlunos') }}" method="POST">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="alunos" value="A">
			<div class="row">
				<div class="col">
					<input type="text" class="form-control" placeholder="Filtro" name="filtro_geral">
					<br>
					<button type="submit" class="btn btn-block btn-success">
						<i class="fas fa-search"></i>
					</button>
				</div>
			</div>
		</form>
	</div>

	<div class="jumbotron">
		<form action="{{ action('SecretariaController@filtrarTurma') }}" method="POST">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="alunos" value="A">
			<div class="row">
				<div class="col">
					<label for="filtro_curso">Curso:</label>
				    <select class="form-control" id="filtro_curso" name="filtro_curso">
					    <option selected="" disabled="" ></option>
					    @foreach($cursos as $curso)
					    	<option value="{{ $curso->curso }}"> {{ $curso->curso }} </option>
					    @endforeach
				    </select>
				    <label for="filtro_ano">Ano:</label>
				    <select class="form-control" id="filtro_ano" name="filtro_ano">
					    <option selected="" disabled="" ></option>
					    @foreach($anos as $ano)
					    	<option value="{{ $ano->ano }}"> {{ $ano->ano }} </option>
					    @endforeach
				    </select>
				    <br>
				    <button type="submit" class="btn btn-block btn-success">
						<i class="fas fa-search"></i>
					</button>
				</div>
			</div>
		</form>
	</div>
@stop

@section('conteudo')
	<div class="jumbotron">
		<table class='table table-striped'>
			<thead>
				<tr>
				    <th>MATRICULA</th>
				    <th>NOME</th>
				    <th>CURSO</th>
				    <th>ANO</th>
				    <th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($alunos as $dados)
				<tr>
				    <td>{{ $dados->matricula }}</td>
				    <td>{{ $dados->nome }}</td>
				    <td>{{ $dados->curso }}</td>
				    <td>{{ $dados->ano }}</td>
				    <td>
				    	<form action="{{ action('SecretariaController@visualisarAluno', ['id' => $dados->matricula]) }}" method="POST">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
						<input type ="hidden" name="alunos" value="A">
					    	<button type="submit" class="btn btn-xs btn-success">
					        	<i class="far fa-eye"></i>
					      	</button>
					    </form>

					    <br>

					    <form action="{{ action('SecretariaController@alterarAluno', ['id' => $dados->matricula]) }}" method="POST">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
						<input type ="hidden" name="alunos" value="A">
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