@extends('principalAluno')

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
		<form action="{{ action('AlunoController@solicitarAlteracao') }}" method="POST" enctype="multipart/form-data">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="atualizar" value="A">
			<div class="row">
				<div class="mx-auto justify-content-md-center">
					<figure>
						<img src="/upload/fotos/{{ Auth::user()->foto }}" class="rounded-circle" height="140" width="140">
					</figure>
				</div>
				<div class="form-group">
					<div class="input-group input-file" name="foto" id="foto">
						<span class="input-group-btn">
							<button class="btn btn-success btn-choose" type="button">
								Selecionar foto <i class="fas fa-file-upload"></i>
							</button>
						</span>
						<input type="text" class="form-control" placeholder='...' />
					</div>
					<small class="form-text text-muted">Por favor selecione uma imagem válida com menos de 1MB.</small>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<label>Nome:</label>
					<input type="text" class="form-control bg-light" name="nome" value="{{ Auth::user()->nome }}">
				</div>
				<div class="col">
					<label>Matricula:</label>
					<input type="text" class="form-control bg-light" name="matricula" value="{{ Auth::user()->matricula }}" readonly>
				</div>
			</div>

			<div class="row">
				<div class="col">
					<label>Curso:</label>
					<input type="text" class="form-control bg-light" name="curso" value="{{ Auth::user()->curso }}">
				</div>
				<div class="col">
					<label>Ano:</label>
					<input type="text" class="form-control bg-light" name="ano" value="{{ Auth::user()->ano }}">
				</div>
			</div>

			<div class="row">
				<div class="col">
					<label>Campus:</label>
					<input type="text" class="form-control bg-light" name="campus" value="{{ Auth::user()->campus }}">
				</div>
				<div class="col">
					<label>Modalidade:</label>
					<input type="text" class="form-control bg-light" name="modalidade" value="{{ Auth::user()->modalidade }}">
				</div>
			</div>

			<div class="row">
				<div class="col">
					<label>CPF:</label>
					<input type="text" class="form-control bg-light" name="cpf" value="{{Auth::user()->cpf }}">
				</div>
				<div class="col">
					<label>RG:</label>
					<input type="text" class="form-control bg-light" name="rg" value="{{ Auth::user()->rg }}">
				</div>
			</div>

			<div class="row">
				<div class="col">
					<label>Naturalidade:</label>
					<input type="text" class="form-control bg-light" name="naturalidade" value="{{ Auth::user()->naturalidade }}">
				</div>
				<div class="col">
					<label>Nascimento: ({{ Auth::user()->nascimento }})</label>
					<input type="date" class="form-control bg-light" name="nascimento" value="">
				</div>
			</div>
			<br>
			<div class="row">
				<button type="submit" class="btn btn-success btn-block">
					<i class="fas fa-envelope"></i> Enviar solicitação de alteração
				</button>
			</div>
		</form>
	</div>
@stop