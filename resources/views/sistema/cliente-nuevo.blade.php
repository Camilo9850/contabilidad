@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($cliente->idcliente) && $cliente->idcliente > 0 ? $cliente->idcliente : 0; ?>';
    <?php $globalId = isset($cliente->idcliente) ? $cliente->idcliente : "0"; ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/cliente">cliente;</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
        <li class="btn-item">
        <a title="Nuevo" href="/admin/cliente/nuevo">
            <i class="fa-solid fa-plus-circle" aria-hidden="true"></i>
            <span>Nuevo</span>
        </a>
    </li>
        <li class="btn-item">
        <a title="Guardar" href="#" onclick="javascript: $('#modalGuardar').modal('toggle');">
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
        location.href = "/admin/cliente";
    }
</script>
@endsection
@section('contenido')
<?php
if (isset($msg)) {
    echo '<div id = "msg"></div>';
    echo '<script>msgShow("' . $msg["MSG"] . '", "' . $msg["ESTADO"] . '")</script>';
}
?>
<div class="panel-body">
    <div id="msg"></div>
    <?php
    if (isset($msg)) {
        echo '<script>msgShow("' . $msg["MSG"] . '", "' . $msg["ESTADO"] . '")</script>';
    }
    ?>
    <form id="form1" method="POST" action="{{ route('cliente.guardar') }}">
    @csrf
    <div class="row">
        
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        
        {{-- 1. NOMBRE (VARCHAR 50) --}}
        <div class="form-group col-lg-6">
            <label for="txtNombre">Nombre: *</label>
            <input type="text" id="txtNombre" name="nombre" class="form-control" 
            value="{{ old('nombre') }}" required>
        </div>
        
        {{-- 2. APELLIDO (VARCHAR 50) --}}
        <div class="form-group col-lg-6">
            <label for="txtApellido">Apellido:</label>
            <input type="text" id="txtApellido" name="apellido" class="form-control" 
            value="{{ old('apellido') }}">
        </div>
 
        {{-- 3. TELÉFONO (INT 10) --}}
        <div class="form-group col-lg-6">
            <label for="txtTelefono">Teléfono (Fijo):</label>
            <input type="number" id="txtTelefono" name="telefono" class="form-control" 
            value="{{ old('telefono') }}">
        </div>
        
        {{-- 4. CELULAR (VARCHAR 50) --}}
        <div class="form-group col-lg-6">
            <label for="txtCelular">Celular:</label>
            <input type="text" id="txtCelular" name="celular" class="form-control" 
            value="{{ old('celular') }}">
        </div>
 
        {{-- 5. DIRECCIÓN (VARCHAR 50) --}}
        <div class="form-group col-lg-12">
            <label for="txtDireccion">Dirección:</label>
            <input type="text" id="txtDireccion" name="direccion" class="form-control" 
            value="{{ old('direccion') }}">
        </div>
 
        {{-- 6. CORREO (VARCHAR 50) --}}
        <div class="form-group col-lg-6">
            <label for="txtCorreo">Correo Electrónico: *</label>
            <input type="email" id="txtCorreo" name="correo" class="form-control" 
            value="{{ old('correo') }}" required autocomplete="email">
        </div>
 
        {{-- 7. CLAVE (VARCHAR 150) --}}
        <div class="form-group col-lg-6">
            <label for="txtClave">Clave: *</label>
            <input type="password" id="txtClave" name="clave" class="form-control" 
                   value="" 
                   autocomplete="new-password"
                   >
            
            @if(isset($cliente->idcliente))
                <small class="form-text text-muted">Deje vacío para no cambiar la clave.</small>
            @endif
        </div>
 
    </div>
</form>
</div>
<div class="modal fade" id="mdlEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar registro?</h5>
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
            url: "{{ route('cliente.eliminar', ['idcliente' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', globalId),
            data: { 
                id: globalId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            async: true,
            dataType: "json",
            success: function (data) {
                if (data.success === true) {
                    msgShow(data.message || "Registro eliminado exitosamente.", "success");
                    setTimeout(function() {
                        window.location.href = "/admin/cliente";
                    }, 1500);
                } else {
                    msgShow(data.message || data.err || "Error al eliminar el registro", "danger");
                }
                $('#mdlEliminar').modal('toggle');
            },
            error: function (xhr, status, error) {
                msgShow("Error al eliminar el registro: " + error, "danger");
                $('#mdlEliminar').modal('toggle');
            }
        });
    }
</script>
@endsection