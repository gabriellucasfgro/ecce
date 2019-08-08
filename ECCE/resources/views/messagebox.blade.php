@extends('principal')

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
            <a href="/">
                <button type="submit" class="list-group-item list-group-item-action">
                    OK
                </button>
            </a>
        </div>
    </div>
</div>

@stop
