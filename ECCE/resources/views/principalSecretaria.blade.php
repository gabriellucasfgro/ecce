<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ECCE - Sistema Emissão e Controle de Carteirinhas Estudantis</title>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="{{ url('/themes/theme.css') }}" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

        @yield('script')

    </head>

    <body role="document">
        <!-- Fixed navbar -->
        <nav class="navbar navbar-dark bg-success">
            <a class="navbar-brand" href="#"><img src=" {{ url('/img/ifpr.png') }}" height="30" width="30"> Emissão e Controle de Carteirinhas Estudantis</a>
            <label class="navbar-brand" >
                <i class="fas fa-user-cog"></i>
                {{ Auth::guard('secretaria')->user()->nome }}
            </label>
        </nav>

        <div class="container theme-showcase" role="main">

            <div class="page-header">

                <div class="page-header">
                    <h1 class="form-signin-heading">
                        @yield('cabecalho')
                    </h1>
                </div>

                <div>
                    <div class="row">
                        <div class="col-3">
                            <div class="list-group">

                                <form action="{{ action('SecretariaController@manterAlunos') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="alunos" value="A">
                                    @if(Request::input('alunos') == 'A')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-user-circle"></i> Alunos
                                    </button>
                                </form>

                                <form action="{{ action('SecretariaController@manterCarteirinhas') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="carteirinhas" value="C">
                                    @if(Request::input('carteirinhas') == 'C')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-address-card"></i> Carteirinhas
                                    </button>
                                </form>

                                <form action="{{ action('SecretariaController@importar') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="importar" value="I">
                                    @if(Request::input('importar') == 'I')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-upload"></i> Importar Alunos
                                    </button>
                                </form>

                                <form action="{{ action('SecretariaController@exportar') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="exportar" value="E">
                                    @if(Request::input('exportar') == 'E')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-download"></i> Exportar Alunos
                                    </button>
                                </form>

                                <form action="{{ action('SecretariaController@assinatura') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="assinatura" value="A">
                                    @if(Request::input('assinatura') == 'A')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-signature"></i> Assinatura Diretor
                                    </button>
                                </form>

                                <form action="{{ action('SecretariaController@solicitacao') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="solicitacao" value="S">
                                    @if(Request::input('solicitacao') == 'S')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-envelope"></i> Solicitações de Alteração
                                    </button>
                                </form>

                                <form action="{{ action('SecretariaController@sair') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                    <button type="submit" class="list-group-item list-group-item-action bg-danger active">
                                        <i class="fas fa-door-open"></i> Sair
                                    </button>
                                </form>

                                @if(Auth::guard('secretaria')->user()->matricula == 'root_ecce')
                                    <form action="{{ action('SecretariaController@funcionarios') }}" method="POST">
                                    <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                        <button type="submit" class="list-group-item list-group-item-action bg-warning">
                                            <i class="fas fa-user-shield"></i> Administrar funcionarios
                                        </button>
                                    </form>
                                @endif

                            </div>
                            <br>

                            @yield('menu')

                        </div>
                        <div class="col-9">
                            
                            @yield('conteudo')

                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="page-header">
                <b>&copy;2019
                    &nbsp;&nbsp;&raquo;&nbsp;&nbsp;
                    IFPR Paranaguá
                </b>
            </div>
        </div>
    </body>
</html>