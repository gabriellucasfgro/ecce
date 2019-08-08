@extends('principal')

@section('script')

	 <script language="Javascript">

            function IsEmpty(){ 

                if(document.getElementById('matricula_aut').value == "")
                {
                    window.location.href='/carteirinha/0';
                }
                else 
                {
                	window.location.href='/carteirinha/'+document.getElementById('matricula_aut').value;
                }
                return;
            }

        </script>

@stop

@section('conteudo')
<div class="text-center mx-auto justify-content-md-center" >
	<div class="row">
		<div class="col-sm">
			<div class="jumbotron">
				<h5>ALUNO</h5>
				<img src=" {{ url('/img/aluno.png') }}" height="100" width="100">
				<br><br>
				<form action="{{ action('AlunoController@autenticar') }}" method="POST">
			    <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
			    <input type ="hidden" name="perfil" value="P">
			      	<label for="matricula_aluno" class="sr-only">Matrícula</label>
			      	<input type="text" name="matricula_aluno" id="matricula_aluno" class="form-control" placeholder="Matrícula" required>
			      	<br>
			      	<label for="cpf_aluno" class="sr-only">CPF</label>
			      	<input type="password" name="cpf_aluno" id="cpf_aluno" class="form-control" placeholder="CPF" required>
			      	<br>
			      <button class="btn btn-lg btn-block btn-success" type="submit">
			      	<i class="fas fa-sign-in-alt"></i> Entrar
			      </button>
			    </form>
			    <br>
		    </div>
			@if(isset($erro_aluno))
				<div class="alert alert-danger">
				  <strong>Erro!</strong> {{ $erro_aluno }}
				</div>
			@endif
			@if(isset($aluno_criado))
				<div class="alert alert-danger">
				  <strong>Sucesso!</strong> {{ $aluno_criado }}
				</div>
			@endif
		</div>
		<div class="col-sm">
			<div class="jumbotron">
				<h5>CONSULTAR CARTEIRINHA</h5>
				<img src=" {{ url('/img/carteirinha.png') }}" height="150" width="150">
				<br><br>
			    <label for="matricula_aut" class="sr-only">Matrícula</label>
			    <input type="text" name="matricula_aut" id="matricula_aut" class="form-control" placeholder="Matrícula" required>
			    <br>
			    <button type="submit" class="btn btn-block btn-lg btn-success" onclick="IsEmpty();">
			    	<i class="fas fa-address-card"></i> Consultar
			    </button>
			</div>
	    </div>
		<div class="col-sm">
			<div class="jumbotron">
				<h5>SECRETARIA</h5>
				<img src=" {{ url('/img/diretoria.png') }}" height="100" width="100">
				<br><br>
				<form action="{{ action('SecretariaController@autenticar') }}" method="POST">
			    <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
			    <input type ="hidden" name="alunos" value="A">
			    	<label for="matricula_funcionario" class="sr-only">Matrícula</label>
			      	<input type="text" name="matricula_funcionario" id="matricula_funcionario" class="form-control" placeholder="Matrícula" required>
			      	<br>
			    	<label for="cpf_funcionario" class="sr-only">CPF</label>
			      	<input type="password" name="cpf_funcionario" id="cpf_funcionario" class="form-control" placeholder="Senha" required>
			      	<br>
			    	<button class="btn btn-block btn-lg btn-success" type="submit">
			    		<i class="fas fa-sign-in-alt"></i> Entrar
			    	</button>
				</form>
			    <br>
			</div>
			@if(isset($erro_secretaria))
				<div class="alert alert-danger">
				  <strong>Erro!</strong> {{ $erro_secretaria }}
				</div>
			@endif
		</div>
    </div>
</div>
@stop