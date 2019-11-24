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
        <div class="row">
            <div class="mx-auto justify-content-md-center">
                <figure>
                    <img src="/upload/fotos/{{ $alteracao->foto }}" class="rounded-circle" height="140" width="140">
                </figure>
            </div>
        </div>
		<div class="row">
			<div class="col">
				<label>Nome:</label>
				<input type="text" class="form-control bg-light" name="nome" value="{{ $alteracao->nome }}" readonly="">
			</div>
			<div class="col">
				<label>Matricula:</label>
				<input type="text" class="form-control bg-light" name="matricula" value="{{ $alteracao->aluno_matricula }}" readonly="">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Curso:</label>
				<input type="text" class="form-control bg-light" name="curso" value="{{ $alteracao->curso }}" readonly="">
			</div>
			<div class="col">
				<label>Ano:</label>
				<input type="text" class="form-control bg-light" name="ano" value="{{ $alteracao->ano }}" readonly="">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Campus:</label>
				<input type="text" class="form-control bg-light" name="campus" value="{{ $alteracao->campus }}" readonly="">
			</div>
			<div class="col">
				<label>Modalidade:</label>
				<input type="text" class="form-control bg-light" name="modalidade" value="{{ $alteracao->modalidade }}" readonly="">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>CPF:</label>
				<input type="text" class="form-control bg-light" name="cpf" value="{{$alteracao->cpf }}" readonly="">
			</div>
			<div class="col">
				<label>RG:</label>
				<input type="text" class="form-control bg-light" name="rg" value="{{ $alteracao->rg }}" readonly="">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Naturalidade:</label>
				<input type="text" class="form-control bg-light" name="naturalidade" value="{{ $alteracao->naturalidade }}" readonly="">
			</div>
			<div class="col">
				<label>Nascimento: </label>
				<input type="text" class="form-control bg-light" name="nascimento" value="{{ $alteracao->nascimento }}" readonly="">
			</div>
		</div>
		<br>
		<div class="row">
            <div class="mx-auto justify-content-md-center">
			@if($alteracao->status == 0 && $alteracao->aprovado == 1)
				<h5 class="bg-success text-white" >STATUS: APROVADO</h5>
			@elseif($alteracao->status == 0 && $alteracao->aprovado == 0)
				<h5 class="bg-danger text-white">STATUS: NEGADO</h5>
			@endif
            </div>
        </div>
	</div>
@stop