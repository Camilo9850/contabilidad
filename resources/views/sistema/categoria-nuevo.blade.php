@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($categoria->idcategoria) && $categoria->idcategoria > 0 ? $categoria->idcategoria : 0; ?>';
    <?php $globalId = isset($categoria->idcategoria) ? $categoria->idcategoria : "0";?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/sistema/categoria">categoria</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="/admin/sistema/categoria/nuevo">
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
    location.href ="/admin/sistema/categoria";
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
      <form id="form1" method="POST" action="{{ route('categoria.guardar') }}">
    @csrf
    <div class="row">
        <input type="hidden" id="id" name="id" class="form-control" value="{{ $categoria->idcategoria ?? 0 }}" required>
        
        <div class="form-group col-lg-6">
            <label for="txtNombre">Nombre: *</label>
            <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="{{ $categoria->nombre ?? '' }}" required>
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
        $.ajax({
            type: "GET",
            url: "{{ asset('admin/sistema/categoria/eliminar') }}",
            data: { id:globalId },
            async: true,
            dataType: "json",
            success: function (data) {
                if (data.err = "0") {
                    msgShow("Registro eliminado exitosamente.", "success");
                } else {
                    msgShow(data.err, "danger");
                }
                $('#mdlEliminar').modal('toggle');
            }
        });
    }

</script>
@endsection