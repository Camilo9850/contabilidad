@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($sucursal->idsucursal) && $sucursal->idsucursal > 0 ? $sucursal->idsucursal : 0; ?>';
    <?php $globalId = isset($sucursal->idsucursal) ? $sucursal->idsucursal : "0";?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/sucursal">Sucursal</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="/admin/sucursal/nuevo">
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
function fsalir(){
    location.href ="/admin/menu";
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
        <div id = "msg"></div>
        <?php
if (isset($msg)) {
    echo '<script>msgShow("' . $msg["MSG"] . '", "' . $msg["ESTADO"] . '")</script>';
}
?>
       <form id="form1" method="POST" action="{{ route('sucursal.guardar') }}">
    @csrf
    <div class="row">
        
        {{-- CAMPO ID OCULTO --}}
        <input type="hidden" id="id" name="id" class="form-control" 
               value="{{ $sucursal->idsucursal ?? 0 }}" required>
        
        {{-- 1. NOMBRE (VARCHAR 100) --}}
        <div class="form-group col-lg-6">
            <label for="txtNombre">Nombre de la Sucursal: *</label>
            <input type="text" id="txtNombre" name="txtNombre" class="form-control" 
                   value="{{ $sucursal->nombre ?? '' }}" required>
        </div>
        
        {{-- 2. DIRECCIÓN (VARCHAR 200) --}}
        <div class="form-group col-lg-6">
            <label for="txtDireccion">Dirección:</label>
            <input type="text" id="txtDireccion" name="txtDireccion" class="form-control" 
                   value="{{ $sucursal->direccion ?? '' }}">
        </div>

        {{-- 3. TELÉFONO (VARCHAR 20) --}}
        <div class="form-group col-lg-6">
            <label for="txtTelefono">Teléfono:</label>
            <input type="text" id="txtTelefono" name="txtTelefono" class="form-control" 
                   value="{{ $sucursal->telefono ?? '' }}">
        </div>
        
        {{-- 4. EMAIL (VARCHAR 100) --}}
        <div class="form-group col-lg-6">
            <label for="txtEmail">Email:</label>
            <input type="email" id="txtEmail" name="txtEmail" class="form-control" 
                   value="{{ $sucursal->email ?? '' }}" autocomplete="email">
        </div>

        {{-- 5. CIUDAD (VARCHAR 100) --}}
        <div class="form-group col-lg-6">
            <label for="txtCiudad">Ciudad:</label>
            <input type="text" id="txtCiudad" name="txtCiudad" class="form-control" 
                   value="{{ $sucursal->ciudad ?? '' }}">
        </div>
        
        {{-- 6. ESTADO (VARCHAR 100) --}}
        <div class="form-group col-lg-6">
            <label for="txtEstado">Estado/Provincia:</label>
            <input type="text" id="txtEstado" name="txtEstado" class="form-control" 
                   value="{{ $sucursal->estado ?? '' }}">
        </div>

        {{-- 7. ACTIVO (TINYINT 4) --}}
        <div class="form-group col-lg-6">
            <label for="lstActivo">Activa:</label>
            <select id="lstActivo" name="lstActivo" class="form-control">
                <option value="1" {{ ($sucursal->activo ?? 1) == 1 ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ ($sucursal->activo ?? 1) == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>
        
        {{-- NOTA: Se eliminó toda la sección de "Áreas de Trabajo" por ser lógica de Menú --}}

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
            url: "{{ route('sucursal.eliminar') }}",
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
                        window.location.href = "/admin/sucursal";
                    }, 1500);
                } else {
                    msgShow(data.message || "Error al eliminar la sucursal", "danger");
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