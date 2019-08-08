@extends('principal')

@section('conteudo')
	<div class="jumbotron">
		<div class="mx-auto justify-content-md-center">
			@if($status == 'valido')
				<h4>Carteirinha Válida</h4>
			@elseif($status == 'invalido')
				<h4>Carteirinha Inválida</h4>
			@endif
		</div>
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

		<div class="row">
			<div class="col">
				<label>Data de Emissão</label>
				<input type="text" class="form-control bg-light" name="" value="{{ date('d/m/Y', strtotime($carteirinha->emissao)) }}" readonly>
			</div>
			<div class="col">
				<label>Validade</label>
				<input type="text" class="form-control bg-light" name="" value="{{ $carteirinha->validade }}" readonly>
			</div>
		</div>
	</div>
@stop