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
					<img src="/upload/assinatura/assinatura.png" class="" height="100" width="240">
				</figure>
			</div>

			<form action="{{ action('AssinaturaController@atualizarAssinatura') }}" method="POST" enctype="multipart/form-data">
			<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
			<input type ="hidden" name="assinatura" value="A">
                <div class="form-group">
                    <div class="input-group input-file" name="assinatura" id="assinatura">
				        <span class="input-group-btn">
				            <button class="btn btn-success btn-choose" type="button">
				                Selecionar assinatura <i class="fas fa-file-upload"></i>
				            </button>
				        </span>
				        <input type="text" class="form-control" placeholder='...' />
				    </div>
                    <small class="form-text text-muted">Por favor selecione uma imagem válida de formato <strong>PNG</strong>.</small>
                </div>
		</div>
			<br>
            <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-check"></i> Salvar Assinatura
            </button>
        </form>
	</div>
@stop