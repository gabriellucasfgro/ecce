@extends('principalSecretaria')

@section('menu')
	<div class="jumbotron">
		<form action="{{ action('ManterController@filtrarCarteirinhas') }}" method="POST">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="carteirinhas" value="C">
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
		<form action="{{ action('ManterController@filtrarCarteirinhasTurma') }}" method="POST">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="carteirinhas" value="C">
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
		@if(isset($filtro_curso) && isset($filtro_ano))
			<form action="{{ action('ImprimirController@imprimirTurma', ['curso' => $filtro_curso, 'ano' => $filtro_ano]) }}" method="POST" target="_blank">
			<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
			<input type ="hidden" name="carteirinhas" value="C">
		    	<button type="submit" class="btn btn-xs btn-block btn-success">
		        	<i class="fas fa-address-card"></i> Imprimir Carteirinhas
		      	</button>
		    </form>
		@endif
		<table class='table table-striped'>
			<thead>
				<tr>
					<th>MATRICULA</th>
					<th>NOME</th>
					<th>CURSO</th>
					<th>VALIDADE</th>
					<th>EMISSÃO</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($alunos_carteirinhas as $dados)
				<tr>
				    <td>{{ $dados->matricula }}</td>
				    <td>{{ $dados->nome }}</td>
				    <td>{{ $dados->curso }}</td>
				    <td>{{ $dados->validade }}</td>
				    <td>{{ $dados->emissao }}</td>
				    <td>
				    	<form action="{{ action('ImprimirController@imprimirAluno', ['matricula' => $dados->matricula]) }}" method="POST" target="_blank">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
						<input type ="hidden" name="carteirinhas" value="C">
					    	<button type="submit" class="btn btn-xs btn-success">
					        	<i class="fas fa-address-card"></i>
					      	</button>
						</form>
						<br>
						<form action="{{ action('ManterController@alterarCarteirinha', ['matricula' => $dados->matricula]) }}" method="POST">
						<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
						<input type ="hidden" name="carteirinhas" value="C">
					    	<button type="submit" class="btn btn-xs btn-success">
								<i class="fas fa-pencil-alt"></i>
					      	</button>
					    </form>
				    </td>
				@endforeach
				</tr>
			</tbody>
		</table>
	</div>
@stop