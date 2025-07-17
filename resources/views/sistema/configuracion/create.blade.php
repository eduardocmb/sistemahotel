@extends('adminlte::page')

@section('title', 'Configuración')

@section('content_header')
    <h1>Configuración</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('dashboard') }}">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
    <div class="row mt-2">
        <div class="col-12">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        </div>
    </div>
@stop

@section('content')
<div class="container">
    <form action="{{ route('configuracion.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <!-- Facturación -->
                <div class="mb-3">
                    <h3 class="bg-secondary">Facturación</h3>
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="imprimir_factura"
                            name="imprimir_factura"
                            {{ $configuraciones->where('codigo', 'PRFAC')->first()?->valor == 'S' ? 'checked' : '' }}
                            >
                        <label class="form-check-label" for="imprimir_factura">Imprimir Factura</label>
                    </div>
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="imprimir_copia"
                            name="imprimir_copia"
                            {{ $configuraciones->where('codigo', 'PRFCO')->first()?->valor == 'S' ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="imprimir_copia">Imprimir Copia</label>
                    </div>
                </div>

                <!-- Configuración de Página - Factura -->
                <div class="mb-3">
                    <h3 class="bg-secondary">Configuración de Página - Factura</h3>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="pagina_ticket" name="config_pagina" value="ticket" {{ $configuraciones->where('codigo', 'TAMAN')->first()?->valor == 'TICKET' ? 'checked' : '' }}>
                        <label class="form-check-label" for="pagina_ticket">Ticket</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="pagina_carta" name="config_pagina" value="carta" {{ $configuraciones->where('codigo', 'TAMAN')->first()?->valor == 'CARTA' ? 'checked' : '' }}>
                        <label class="form-check-label" for="pagina_carta">Carta</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="pagina_media_carta" name="config_pagina" value="media_carta" {{ $configuraciones->where('codigo', 'TAMAN')->first()?->valor == 'MEDIA CARTA' ? 'checked' : '' }}>
                        <label class="form-check-label" for="pagina_media_carta">Media Carta</label>
                    </div>
                </div>
                <!-- Recibos y Estados de Cuenta -->
                <div class="mb-3">
                    <h3 class="bg-secondary">Recibos y Estados de Cuenta</h3>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="recibo_ticket" name="recibo_estado" value="ticket" {{ $configuraciones->where('codigo', 'TMREC')->first()?->valor == 'TICKET' ? 'checked' : '' }}>
                        <label class="form-check-label" for="recibo_ticket">Ticket</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="recibo_carta" name="recibo_estado" value="carta" {{ $configuraciones->where('codigo', 'TMREC')->first()?->valor == 'CARTA' ? 'checked' : '' }}>
                        <label class="form-check-label" for="recibo_carta">Carta</label>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Salir</a>
            </div>
        </div>
    </form>
</div>
@endsection
