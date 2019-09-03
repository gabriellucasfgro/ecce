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
                <i class="fas fa-user"></i>
                {{ Auth::user()->nome }}
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

                                <form action="{{ action('AlunoController@perfil') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="perfil" value="P">
                                    @if(Request::input('perfil') == 'P')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-user-circle"></i> Perfil
                                    </button>
                                </form>

                                <form action="{{ action('AlunoController@atualizar') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="atualizar" value="A">
                                    @if(Request::input('atualizar') == 'A')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-edit"></i> Atualizar Dados
                                    </button>
                                </form>

                                <form action="{{ action('AlunoController@imprimir') }}" method="POST" target="_blank">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="imprimir" value="I">
                                    @if(Request::input('imprimir') == 'I')
                                        <button type="submit" class="list-group-item list-group-item-action bg-success active">
                                    @else
                                        <button type="submit" id="imprimir" class="list-group-item list-group-item-action">
                                    @endif
                                        <i class="fas fa-print"></i> Imprimir Carteirinha
                                    </button>
                                </form>

                                <form action="{{ action('AlunoController@sair') }}" method="POST">
                                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                                <input type ="hidden" name="sair" value="S">
                                    <button type="submit" class="list-group-item list-group-item-action bg-danger active">
                                        <i class="fas fa-door-open"></i> Sair
                                    </button>
                                </form>

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