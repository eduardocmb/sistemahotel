@extends('adminlte::page')

@section('title', 'Datos Generales del Negocio')

@section('content_header')
<a class="btn btn-secondary my-2 btn-sm" href="{{ route('dashboard') }}">
    <i class="fas fa-arrow-left"></i> Volver
</a>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-teal">
            <h3 class="card-title">Formulario de Información del Negocio</h3>
        </div>
        <div class="card-body bordered">
            <form method="POST" action="{{ route('infohotel.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-4">
                        <label for="rtn">RTN</label>
                        <input oninput="this.value = this.value.toUpperCase()"
                            type="text"
                            class="form-control"
                            id="rtn"
                            name="rtn"
                            maxlength="18"
                            value="{{ old('rtn', $infoHotel->rtn ?? '') }}"
                            required>
                    </div>
                    <div class="form-group col-4">
                        <label for="nombre">Nombre</label>
                        <input oninput="this.value = this.value.toUpperCase()"
                            type="text"
                            class="form-control"
                            id="nombre"
                            name="nombre"
                            maxlength="150"
                            value="{{ old('nombre', $infoHotel->nombre ?? '') }}"
                            required>
                    </div>
                    <div class="form-group col-4">
                        <label for="eslogan">Eslogan</label>
                        <input oninput="this.value = this.value.toUpperCase()"
                            type="text"
                            class="form-control"
                            id="eslogan"
                            name="eslogan"
                            maxlength="150"
                            value="{{ old('eslogan', $infoHotel->eslogan ?? '') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="direccion">Dirección</label>
                        <textarea oninput="this.value = this.value.toUpperCase()"
                            class="form-control"
                            id="direccion"
                            name="direccion"
                            maxlength="150"
                            rows="2"
                            required>{{ old('direccion', $infoHotel->direccion ?? '') }}</textarea>
                    </div>
                    <div class="form-group col-6">
                        <label for="correo">Correo</label>
                        <input
                            type="email"
                            class="form-control"
                            id="correo"
                            name="correo"
                            maxlength="150"
                            value="{{ old('correo', $infoHotel->correo ?? '') }}"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="propietario">Propietario</label>
                        <input  oninput="this.value = this.value.toUpperCase()"
                            type="text"
                            class="form-control"
                            id="propietario"
                            name="propietario"
                            maxlength="80"
                            value="{{ old('propietario', $infoHotel->propietario ?? '') }}"
                            required>
                    </div>
                    <div class="form-group col-6">
                        <label for="telefono">Teléfono</label>
                        <input  oninput="this.value = this.value.toUpperCase()"
                            type="text"
                            class="form-control"
                            id="telefono"
                            name="telefono"
                            maxlength="10"
                            value="{{ old('telefono', $infoHotel->telefono ?? '') }}"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="logo">Logo Principal</label>
                        <input
                            type="file"
                            class="form-control-file"
                            id="logo"
                            name="logo"
                            accept="image/*">
                        @if(isset($infoHotel->logo) && Storage::disk('public')->exists($infoHotel->logo))
                            <img
                                id="logo-preview"
                                src="{{ asset('storage/' . $infoHotel->logo) }}"
                                alt="Logo actual"
                                style="max-width: 200px; margin-top: 10px;">
                        @else
                            <img
                                id="logo-preview"
                                src="#"
                                alt="Vista previa del logo"
                                style="display:none; max-width: 200px; margin-top: 10px;">
                        @endif
                    </div>
                    <div class="form-group col-6">
                        <label for="logo2">Banner del Sistema</label>
                        <input
                            type="file"
                            class="form-control-file"
                            id="logo2"
                            name="logo2"
                            accept="image/*"
                            required>
                        @if(isset($infoHotel->logo2) && Storage::disk('public')->exists($infoHotel->logo2))
                            <img
                                id="logo2-preview"
                                src="{{ asset('storage/' . $infoHotel->logo2) }}"
                                alt="Logo secundario actual"
                                style="max-width: 200px; margin-top: 10px;">
                        @else
                            <img
                                id="logo2-preview"
                                src="#"
                                alt="Vista previa del logo secundario"
                                style="display:none; max-width: 200px; margin-top: 10px;">
                        @endif
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(previewId).attr('src', e.target.result);
                    $(previewId).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo").change(function() {
            readURL(this, "#logo-preview");
        });

        $("#logo2").change(function() {
            readURL(this, "#logo2-preview");
        });
    </script>
@stop
