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
        <form action="{{ action('SecretariaController@validarAlteracao') }}" method="POST" enctype="multipart/form-data">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="solicitacao" value="S">
			<div class="row">
				<div class="mx-auto justify-content-md-center">
					<figure>
						<img src="/upload/fotos/{{ $alteracao->foto }}" class="rounded-circle" height="140" width="140">
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
					<small class="form-text text-muted">Por favor selecione uma imagem válida de formato PNG.</small>
				</div>
			</div>
		<div class="row">
			<div class="col">
				<label>Nome:</label>
				<input type="text" class="form-control bg-light" name="nome" value="{{ $alteracao->nome }}">
			</div>
			<div class="col">
				<label>Matricula:</label>
				<input type="text" class="form-control bg-light" name="matricula" value="{{ $alteracao->matricula }}" readonly="">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Curso:</label>
				<input type="text" class="form-control bg-light" name="curso" value="{{ $alteracao->curso }}">
			</div>
			<div class="col">
				<label>Ano:</label>
				<input type="text" class="form-control bg-light" name="ano" value="{{ $alteracao->ano }}">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Campus:</label>
				<input type="text" class="form-control bg-light" name="campus" value="{{ $alteracao->campus }}">
			</div>
			<div class="col">
				<label>Modalidade:</label>
				<input type="text" class="form-control bg-light" name="modalidade" value="{{ $alteracao->modalidade }}">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>CPF:</label>
				<input type="text" class="form-control bg-light" name="cpf" value="{{$alteracao->cpf }}">
			</div>
			<div class="col">
				<label>RG:</label>
				<input type="text" class="form-control bg-light" name="rg" value="{{ $alteracao->rg }}">
			</div>
		</div>

		<div class="row">
			<div class="col">
				<label>Naturalidade:</label>
				<input type="text" class="form-control bg-light" name="naturalidade" value="{{ $alteracao->naturalidade }}">
			</div>
			<div class="col">
				<label>Nascimento: ({{ $alteracao->nascimento }})</label>
				<input type="date" class="form-control bg-light" name="nascimento">
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
                <form action="{{ action('SecretariaController@recusarAlteracao', ['id' => $alteracao->matricula]) }}" method="POST">
                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                <input type ="hidden" name="solicitacao" value="S">
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-times"></i> Recusar Alteração
                    </button>
                </form>
            </div>
		</div>
	</div>
@stop