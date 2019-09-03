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
		<form action="{{ action('SecretariaController@importarTurma') }}" method="POST" enctype="multipart/form-data">
		<input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
		<input type ="hidden" name="importar" value="I">
		<label>Planilha da turma: </label>
	    <div class="input-group input-file" name="arquivo" id="arquivo">
	        <span class="input-group-btn">
	            <button class="btn btn-success btn-choose" type="button">
	                Selecionar arquivo <i class="fas fa-file-upload"></i>
	            </button>
	        </span>
	        <input type="text" class="form-control" placeholder='Nenhum arquivo selecionado...' />
	    </div>
		<br>
		<div class="alert alert-primary" role="alert">
			O arquivo deve ser uma planilha válida!
		</div>
		<div class="alert alert-primary" role="alert">
			Dependendo do tamanho do arquivo pode levar alguns instantes para a importação!
		</div>
	    <button type="submit" class="btn btn-success btn-block">
	        <i class="fas fa-check"></i> Concluir
	    </button>
	</div>
@stop