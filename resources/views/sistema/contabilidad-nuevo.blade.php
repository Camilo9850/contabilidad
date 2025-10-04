@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($contabilidad->idcontabilidad) && $contabilidad->idcontabilidad > 0 ? $contabilidad->idcontabilidad : 0; ?>';
    <?php $globalId = isset($contabilidad->idcontabilidad) ? $contabilidad->idcontabilidad : "0"; ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/contabilidad">Contabilidad;</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
        <li class="btn-item">
        <a title="Nuevo" href="/admin/contabilidad/nuevo">
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
        location.href = "/admin/contabilidad";
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
    <form id="form1" method="POST" action="{{ route('contabilidad.guardar') }}">
        @csrf
        <div class="row">

            {{-- 1. CAMPO ID OCULTO (idcontabilidad) --}}
            {{-- Usamos idcontabilidad ?? 0 y lo pasamos con el nombre 'id' --}}
            <input type="hidden" id="id" name="id" class="form-control"
                value="{{ $contabilidad->idcontabilidad ?? 0 }}" required>

            {{-- 2. FECHA DE TRANSACCIÓN (fecha_transaccion) --}}
            <div class="form-group col-lg-6">
                <label for="txtFecha">Fecha de Transacción: *</label>
                <input type="date" id="txtFecha" name="txtFecha" class="form-control"
                    value="{{ $contabilidad->fecha_transaccion ?? \Carbon\Carbon::now()->format('Y-m-d') }}" required>
            </div>

            {{-- 3. TIPO DE MOVIMIENTO (tipo_movimiento ENUM) --}}
            <div class="form-group col-lg-6">
                <label for="lstTipoMovimiento">Tipo de Movimiento: *</label>
                <select id="lstTipoMovimiento" name="lstTipoMovimiento" class="form-control" required>
                    <option value="" disabled selected>Seleccionar tipo</option>
                    {{-- Opciones del ENUM: INGRESO, EGRESO, TRANSFERENCIA --}}
                    <option value="INGRESO" {{ ($contabilidad->tipo_movimiento ?? '') == 'INGRESO' ? 'selected' : '' }}>Ingreso</option>
                    <option value="EGRESO" {{ ($contabilidad->tipo_movimiento ?? '') == 'EGRESO' ? 'selected' : '' }}>Egreso</option>
                    <option value="TRANSFERENCIA" {{ ($contabilidad->tipo_movimiento ?? '') == 'TRANSFERENCIA' ? 'selected' : '' }}>Transferencia</option>
                </select>
            </div>

            {{-- 4. MONTO (monto DECIMAL) --}}
            <div class="form-group col-lg-6">
                <label for="txtMonto">Monto: *</label>
                <input type="number" id="txtMonto" name="txtMonto" class="form-control" step="0.01"
                    value="{{ $contabilidad->monto ?? 0.00 }}" required>
            </div>

            {{-- 5. SUCURSAL (fk_id_sucursal) --}}
            <div class="form-group col-lg-6">
                <label for="lstSucursal">Sucursal:</label>
                <select id="lstSucursal" name="lstSucursal" class="form-control">
                    <option value="">Sin Sucursal</option>
                    @if(isset($sucursales))
                    @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->idsucursal }}"
                        {{ ($contabilidad->fk_id_sucursal ?? '') == $sucursal->idsucursal ? 'selected' : '' }}>
                        {{ $sucursal->nombre }}
                    </option>
                    @endforeach
                    @endif
                </select>
            </div>

            {{-- 6. REFERENCIA ID (referencia_id) --}}
            <div class="form-group col-lg-6">
                <label for="txtReferenciaId">Referencia ID (Factura/Pedido):</label>
                <input type="number" id="txtReferenciaId" name="txtReferenciaId" class="form-control"
                    value="{{ $contabilidad->referencia_id ?? '' }}">
            </div>

            {{-- 7. DESCRIPCIÓN (descripcion TEXT) --}}
            <div class="form-group col-lg-12">
                <label for="txtDescripcion">Descripción del Movimiento:</label>
                <textarea id="txtDescripcion" name="txtDescripcion" class="form-control" rows="3">{{ $contabilidad->descripcion ?? '' }}</textarea>
            </div>

            {{-- Nota: created_at y updated_at se manejan automáticamente por Laravel --}}

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
            url: "{{ asset('admin/contabilidad/eliminar') }}",
            data: {
                id: globalId
            },
            async: true,
            dataType: "json",
            success: function(data) {
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