@extends('principalSecretaria')

@section('script')
	
	<script type="text/javascript" src="{{ url('/js/plugins/mask/jquery.mask.js') }}"></script>

	<script type="text/javascript">

	    // Função de abertura do arquivo
	    function bs_input_file() {

	        $(".input-file").before(
	            function() {
	                if ( ! $(this).prev().hasClass('input-ghost') ) {
	                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
	                    element.attr("name", $(this).attr("name"));
	                    element.change(function(){
	                        element.next(element).find('input').val((element.val()).split('\\').pop());
	                    });
	                    $(this).find("button.btn-choose").click(function(){
	                        element.click();
	                    });
	                    $(this).find("button.btn-reset").click(function(){
	                        element.val(null);
	                        $(this).parents(".input-file").find('input').val('');
	                    });
	                    $(this).find('input').css("cursor","pointer");
	                    $(this).find('input').mousedown(function() {
	                        $(this).parents('.input-file').prev().click();
	                        return false;
	                    });
	                    return element;
	                }
	            }
	        );
	    }

	    $(function() {
	        bs_input_file();
	    });

	</script>
@stop

@section('conteudo')
	<div class="jumbotron">

        <form action="{{ action('ValidarController@validarAlteracao', ['id' => $alteracao->id]) }}" method="POST" enctype="multipart/form-data">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="pendentes" value="P">
			<div class="row">
				<div class="mx-auto justify-content-md-center">
					<figure>
						<img src="/upload/fotos/{{ $alteracao->foto }}" class="rounded-circle" height="140" width="140">
					</figure>
				</div>
				<div class="form-group">
					<div class="input-group input-file" name="foto" id="foto">
						<span class="input-group-btn">
							@if($alteracao->foto != $aluno->foto)
								<button class="btn btn-info btn-choose" type="button">
							@else
								<button class="btn btn-success btn-choose" type="button" disabled>
							@endif
								Selecionar foto <i class="fas fa-file-upload"></i>
							</button>
						</span>
						<input type="text" class="form-control" placeholder='...' />
					</div>
					<small class="form-text text-muted">Por favor selecione uma imagem válida de formato PNG.</small>
				</div>
			</div>
		<div class="row">
			<div class="col">
				<label>Nome:</label>
				@if($alteracao->nome != $aluno->nome)
					<input type="text" class="form-control bg-info text-white" name="nome" value="{{ $alteracao->nome }}">
				@else
					<input type="text" class="form-control bg-light" name="nome" value="{{ $alteracao->nome }}" readonly>
				@endif
			</div>
			<div class="col">
				<label>Matricula:</label>
				<input type="text" class="form-control bg-success text-white" name="matricula" value="{{ $alteracao->aluno_matricula }}" readonly>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Curso:</label>
				@if($alteracao->curso != $aluno->curso)
					<input type="text" class="form-control bg-info text-white" name="curso" value="{{ $alteracao->curso }}">
				@else
					<input type="text" class="form-control bg-light" name="curso" value="{{ $alteracao->curso }}" readonly>
				@endif
			</div>
			<div class="col">
				<label>Ano:</label>
				@if($alteracao->ano != $aluno->ano)
					<input type="text" class="form-control bg-info text-white" name="ano" value="{{ $alteracao->ano }}">
				@else
					<input type="text" class="form-control bg-light" name="ano" value="{{ $alteracao->ano }}" readonly>
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Campus:</label>
				@if($alteracao->campus != $aluno->campus)
					<input type="text" class="form-control bg-info text-white" name="campus" value="{{ $alteracao->campus }}">
				@else
					<input type="text" class="form-control bg-light" name="campus" value="{{ $alteracao->campus }}" readonly>
				@endif
			</div>
			<div class="col">
				<label>Modalidade:</label>
				@if($alteracao->modalidade != $aluno->modalidade)
					<input type="text" class="form-control bg-info text-white" name="modalidade" value="{{ $alteracao->modalidade }}">
				@else
					<input type="text" class="form-control bg-light" name="modalidade" value="{{ $alteracao->modalidade }}" readonly>
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>CPF:</label>
				@if($alteracao->cpf != $aluno->cpf)
					<input type="text" class="form-control bg-info text-white" name="cpf" value="{{$alteracao->cpf }}">
				@else
					<input type="text" class="form-control bg-light" name="cpf" value="{{$alteracao->cpf }}" readonly>
				@endif
			</div>
			<div class="col">
				<label>RG:</label>
				@if($alteracao->rg != $aluno->rg)
					<input type="text" class="form-control bg-info text-white" name="rg" value="{{ $alteracao->rg }}">
				@else
					<input type="text" class="form-control bg-light" name="rg" value="{{ $alteracao->rg }}" readonly>
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Naturalidade:</label>
				@if($alteracao->naturalidade != $aluno->naturalidade)
					<input type="text" class="form-control bg-info text-white" name="naturalidade" value="{{ $alteracao->naturalidade }}">
				@else
					<input type="text" class="form-control bg-light" name="naturalidade" value="{{ $alteracao->naturalidade }}" readonly>
				@endif
			</div>
			<div class="col">
				<label>Nascimento: </label>
				@if($alteracao->nascimento != $aluno->nascimento)
					<input type="date" class="form-control bg-info text-white" name="nascimento" value="{{ DateTime::createFromFormat('d/m/Y', $alteracao->nascimento)->format('Y-m-d') }}">
				@else
					<input type="date" class="form-control bg-light" name="nascimento" value="{{ DateTime::createFromFormat('d/m/Y', $alteracao->nascimento)->format('Y-m-d') }}" readonly>
				@endif
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col">
					<button type="submit" class="btn btn-success btn-block">
						<i class="fas fa-check"></i> Validar Alteração
					</button>
				</form>
			</div>
			<div class="col">
				<form action="{{ action('ValidarController@recusarAlteracao', ['id' => $alteracao->id]) }}" method="POST">
				<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
				<input type ="hidden" name="pendentes" value="P">
					<button type="submit" class="btn btn-danger btn-block">
						<i class="fas fa-times"></i> Recusar Alteração
					</button>
				</form>
			</div>
		</div>
	</div>
@stop