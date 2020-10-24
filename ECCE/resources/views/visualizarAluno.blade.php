@extends('principalSecretaria')

@section('conteudo')
	<div class="jumbotron">
		<div class="row">
			<div class="mx-auto justify-content-md-center">
				<figure>
					@if($aluno->foto != null)
						<img src="/upload/fotos/{{ $aluno->foto }}" class="rounded-circle" height="140" width="140">
					@else
						<img src="/img/default.png" class="rounded-circle" height="140" width="140">
					@endif
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

		<div class="row">
			<div class="col">
				<label>Campus:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ $aluno->campus }}" readonly>
			</div>
			<div class="col">
				<label>Modalidade:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ $aluno->modalidade }}" readonly>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>CPF:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ $aluno->cpf }}" readonly>
			</div>
			<div class="col">
				<label>RG:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ $aluno->rg }}" readonly>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Naturalidade:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ $aluno->naturalidade }}" readonly>
			</div>
			<div class="col">
				<label>Nascimento:</label>
				<input type="text" class="form-control bg-light" name="" value="{{ $aluno->nascimento }}" readonly>
			</div>
		</div>

		<br>
		<br>
		@if($carteirinha != null)
			<div class="row">
				<div class="col">
					<label>Data de Emissão:</label>
					<input type="text" class="form-control bg-light" name="" value="{{ $carteirinha->emissao }}" readonly>
				</div>
				<div class="col">
					<label>Validade:</label>
					<input type="text" class="form-control bg-light" name="" value="{{ $carteirinha->validade }}" readonly>
				</div>
			</div>
		@endif
	</div>
@stop