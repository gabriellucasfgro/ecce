@extends('principalAluno')

@section('conteudo')
	<div class="jumbotron">
		<div class="row">
			<div class="mx-auto justify-content-md-center">
				<figure>
					<img src="/upload/fotos/{{ Auth::user()->foto }}" class="rounded-circle" height="140" width="140">
				</figure>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<label>Nome:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->nome }}" readonly>
			</div>
			<div class="col">
				<label>Matricula:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->matricula }}" readonly>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Curso:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->curso }}" readonly>
			</div>
			<div class="col">
				<label>Ano:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->ano }}" readonly>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Campus:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->campus }}" readonly>
			</div>
			<div class="col">
				<label>Modalidade:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->modalidade }}" readonly>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>CPF:</label>
				<input type="text" class="form-control bg-light" name="" value="{{Auth::user()->cpf }}" readonly>
			</div>
			<div class="col">
				<label>RG:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->rg }}" readonly>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Naturalidade:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->naturalidade }}" readonly>
			</div>
			<div class="col">
				<label>Nascimento:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Auth::user()->nascimento }}" readonly>
			</div>
		</div>

		<br>
		<br>

		<div class="row">
			<div class="col">
				<label>Data de Emissão</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Session::get('carteirinha')->emissao }}" readonly>
			</div>
			<div class="col">
				<label>Validade</label>
				<input type="text" class="form-control bg-light" name="" value="{{ Session::get('carteirinha')->validade }}" readonly>
			</div>
		</div>
	</div>
@stop