@extends('principalSecretaria')

@section('conteudo')

<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-body">
            <div class="{{ $tipo }}">
                <h4><strong>{{ $titulo }}</strong></h4>
                {{ $msg }}
            </div>
        </div>
        <div class="modal-footer">
            <form action="{{ $acao }} " method="POST">
                <input type ="hidden" name="_token" value="{{{ csrf_token() }}}">
                <input type ="hidden" name="{{ $name }}" value="{{ $value }}">
                <button type="submit" class="list-group-item list-group-item-action">
                    OK
                </button>
            </form>
        </div>
    </div>
</div>

@stop
