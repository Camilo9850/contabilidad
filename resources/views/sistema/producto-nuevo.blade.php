@extends('plantilla')
@section('titulo', "$titulo")

@section('scripts')
<script>
    // Definición de ID global desde la variable de Laravel
    globalId = '{{ isset($producto->id) && $producto->id > 0 ? $producto->id : 0 }}';
    <?php $globalId = isset($producto->id) ? $producto->id : "0"; ?>
</script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('producto.listar') }}">producto</a></li>
    <li class="breadcrumb-item active">{{ isset($producto) && $producto->id ? 'Editar producto' : 'Nuevo producto' }}</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="/admin/producto/nuevo">
            <i class="fa-solid fa-plus-circle" aria-hidden="true"></i>
            <span>Nuevo</span>
        </a>
    </li>
    <li class="btn-item">
        <a title="Guardar" href="#" onclick="javascript: guardar();">
            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
            <span>Guardar</span>
        </a>
    </li>
    @if($globalId > 0)
    <li class="btn-item">
        <a title="Eliminar" href="#" onclick="javascript: $('#mdlEliminar').modal('toggle');">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
            <span>Eliminar</span>
        </a>
    </li>
    @endif
    <li class="btn-item">
        <a title="Salir" href="#" onclick="javascript: $('#modalSalir').modal('toggle');">
            <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
            <span>Salir</span>
        </a>
    </li>
</ol>
<script>
    function fsalir() {
        location.href = "/admin/producto";
    }
</script>

@endsection

@section('contenido')
@if (isset($msg))
    <div id="msg"></div>
    <script>msgShow("{{ $msg['MSG'] }}", "{{ $msg['ESTADO'] }}")</script>
@endif

<div class="panel-body">
    <div id="msg"></div>
  <form id="form1" method="POST" action="{{ isset($producto) && $producto->id > 0 ? route('producto.actualizar', $producto->id) : route('producto.guardar') }}" enctype="multipart/form-data">
    @csrf
    
    {{-- Manejo de método para PUT/PATCH si es actualización --}}
    @if(isset($producto) && $producto->id)
        @method('PUT')
    @endif
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">

       
        
        {{-- 1. NOMBRE (VARCHAR 255) --}}
        <div class="form-group col-lg-6">
            <label for="txtNombre">Nombre del Producto: *</label>
            <input type="text" id="txtNombre" name="nombre" class="form-control"
                   value="{{ $producto->nombre ?? old('nombre') }}" required>
        </div>

        {{-- 2. DESCRIPCIÓN (VARCHAR 255) --}}
        <div class="form-group col-lg-6">
            <label for="txtDescripcion">Descripción:</label>
            <textarea id="txtDescripcion" name="descripcion" class="form-control">{{ $producto->descripcion ?? old('descripcion') }}</textarea>
        </div>
        
        {{-- 3. PRECIO (DECIMAL 8,2) --}}
        <div class="form-group col-lg-4">
            <label for="txtPrecio">Precio: *</label>
            <input type="number" id="txtPrecio" name="precio" class="form-control" step="0.01"
                   value="{{ $producto->precio ?? old('precio', 0.00) }}" required>
        </div>

        {{-- 4. CANTIDAD (INT 11) --}}
        <div class="form-group col-lg-4">
            <label for="txtCantidad">Cantidad/Stock: *</label>
            <input type="number" id="txtCantidad" name="cantidad" class="form-control" min="0"
                   value="{{ $producto->cantidad ?? old('cantidad', 0) }}" required>
        </div>

        {{-- 5. IMAGEN (VARCHAR 255) --}}
        <div class="form-group col-lg-4">
            <label for="fileImagen">Subir Imagen</label>
    <input type="file" id="fileImagen" name="imagen_archivo" class="form-control" accept="image/*">

           @if(isset($producto->imagen) && $producto->imagen)
            <small class="form-text text-muted">Imagen actual: {{ $producto->imagen }}</small>
            {{-- Muestra la imagen actual si existe --}}
            {{-- <img src="{{ asset('file/productos/' . $producto->imagen) }}" width="100"> --}}
        @endif
    </div>

        {{-- NOTA: Si usas subida de archivos, debes cambiar el 'type="text"' a 'type="file"'
           y añadir 'enctype="multipart/form-data"' al tag <form> --}}

        {{-- created_at y updated_at son manejados por los timestamps --}}

    </div>
</form>
</div>

{{-- Modal Eliminar --}}
<div class="modal fade" id="mdlEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar registro?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">¿Deseas eliminar el registro actual?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" onclick="eliminar();">Sí</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#form1").validate();

    function guardar() {
        if ($("#form1").valid()) {
            modificado = false;
            form1.submit();
        } else {
            $("#modalGuardar").modal('toggle');
            msgShow("Corrija los errores e intente nuevamente.", "danger");
            return false;
        }
    }

    function eliminar() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "{{ route('producto.eliminar', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', globalId),
            data: {
                id: globalId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            async: true,
            dataType: "json",
            success: function(data) {
                if (data.success === true) {
                    msgShow(data.message || "Registro eliminado exitosamente.", "success");
                    setTimeout(function() {
                        window.location.href = "/admin/producto";
                    }, 1500);
                } else {
                    msgShow(data.message || data.err || "Error al eliminar el registro", "danger");
                }
                $('#mdlEliminar').modal('toggle');
            },
            error: function(xhr, status, error) {
                msgShow("Error al eliminar el registro: " + error, "danger");
                $('#mdlEliminar').modal('toggle');
            }
        });
    }
    
   
</script>
@endsection
