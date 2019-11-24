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
					<img src="/upload/fotos/{{ $aluno->foto }}" class="rounded-circle" height="140" width="140">
				</figure>
			</div>

			<form action="{{ action('ManterController@atualizarImagem', ['matricula' => $aluno->matricula]) }}" method="POST" enctype="multipart/form-data">
			<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
			<input type ="hidden" name="alunos" value="A">
                <div class="form-group">
                    <div class="input-group input-file" name="foto" id="foto">
				        <span class="input-group-btn">
				            <button class="btn btn-success btn-choose" type="button">
				                Selecionar foto <i class="fas fa-file-upload"></i>
				            </button>
				        </span>
				        <input type="text" class="form-control" placeholder='...' />
				    </div>
                    <small class="form-text text-muted">Por favor selecione uma imagem válida de formato PNG.</small>
                </div>
		</div>
			<br>
            <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-check"></i> Salvar Imagem
            </button>
        </form>
	</div>

	<div class="jumbotron">
		<form action="{{ action('ManterController@atualizarDados', ['matricula' => $aluno->matricula]) }}" method="POST">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="alunos" value="A">
		<div class="row">
			<div class="col">
				<label>Nome:</label>
				<input type="text" class="form-control bg-light" name="nome" value="{{ $aluno->nome }}">
			</div>
			<div class="col">
				<label>Matricula:</label>
				<input type="text" class="form-control bg-light" name="matricula" value="{{ $aluno->matricula }}" readonly="">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Curso:</label>
				<input type="text" class="form-control bg-light" name="curso" value="{{ $aluno->curso }}">
			</div>
			<div class="col">
				<label>Ano:</label>
				<input type="text" class="form-control bg-light" name="ano" value="{{ $aluno->ano }}">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Campus:</label>
				<input type="text" class="form-control bg-light" name="campus" value="{{ $aluno->campus }}">
			</div>
			<div class="col">
				<label>Modalidade:</label>
				<input type="text" class="form-control bg-light" name="modalidade" value="{{ $aluno->modalidade }}">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>CPF:</label>
				<input type="text" class="form-control bg-light" name="cpf" value="{{$aluno->cpf }}">
			</div>
			<div class="col">
				<label>RG:</label>
				<input type="text" class="form-control bg-light" name="rg" value="{{ $aluno->rg }}">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Naturalidade:</label>
				<input type="text" class="form-control bg-light" name="naturalidade" value="{{ $aluno->naturalidade }}">
			</div>
			<div class="col">
				<label>Nascimento:</label>
				<input type="date" class="form-control bg-light" name="nascimento" value="{{ DateTime::createFromFormat('d/m/Y', $aluno->nascimento)->format('Y-m-d') }}">
			</div>
		</div>
		<br>
		<div class="row">
			 <button type="submit" class="btn btn-success btn-block">
		        <i class="fas fa-check"></i> Salvar Dados
		    </button>
		</div>
		</form>
	</div>
@stop