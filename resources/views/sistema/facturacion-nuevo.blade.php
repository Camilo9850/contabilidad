@extends('plantilla')
@section('titulo', "$titulo")

@section('scripts')
<script>
    // Definición de ID global desde la variable de Laravel
    globalId = '{{ isset($factura->id) && $factura->id > 0 ? $factura->id : 0 }}';
    <?php $globalId = isset($factura->id) ? $factura->id : "0"; ?>
</script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('facturacion.listar') }}">Facturación</a></li>
    <li class="breadcrumb-item active">{{ isset($factura) && $factura->id ? 'Editar Factura' : 'Nueva Factura' }}</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="/admin/facturacion/nuevo">
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
        location.href = "/admin/cliente";
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
    <form id="form1" method="POST" action="{{ isset($factura) && $factura->id > 0 ? route('factura.actualizar', $factura->id) : route('factura.guardar') }}">
        @csrf

        {{-- Método PUT si es actualización --}}
        @if(isset($factura) && $factura->id)
            @method('PUT')
        @endif

        {{-- Validación de errores --}}
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
            {{-- Campo ID oculto --}}
            <input type="hidden" id="id" name="id" class="form-control" value="{{ $factura->id ?? 0 }}">

            {{-- Número de factura --}}
            <div class="form-group col-lg-6">
                <label for="txtNumeroFactura">Número de Factura: *</label>
                <input type="text" id="txtNumeroFactura" name="numero_factura" class="form-control"
                       value="{{ $factura->numero_factura ?? old('numero_factura') }}" required>
            </div>

            {{-- Fecha --}}
            <div class="form-group col-lg-6">
                <label for="txtFecha">Fecha: *</label>
                <input type="date" id="txtFecha" name="fecha" class="form-control"
                       value="{{ $factura->fecha ? \Carbon\Carbon::parse($factura->fecha)->format('Y-m-d') : old('fecha') }}" required>
            </div>

            {{-- Cliente --}}
            <div class="form-group col-lg-6">
                <label for="lstCliente">Cliente: *</label>
                <select id="lstCliente" name="fk_id_cliente" class="form-control">
                    <option value="" disabled selected>Seleccionar Cliente</option>
                    @if(isset($clientes))
                        @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            {{ (isset($factura->fk_id_cliente) && $factura->fk_id_cliente == $cliente->idcliente) ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- Subtotal --}}
            <div class="form-group col-lg-3">
                <label for="txtSubtotal">Subtotal: *</label>
                <input type="number" id="txtSubtotal" name="subtotal" class="form-control" step="0.01"
                       value="{{ $factura->subtotal ?? old('subtotal', 0.00) }}" required oninput="calcularImpuestoYTotal()">
            </div>

            {{-- % Impuesto --}}
            <div class="form-group col-lg-3">
                <label for="txtPorcentajeImpuesto">% Impuesto:</label>
                <input type="number" id="txtPorcentajeImpuesto" name="porcentaje_impuesto" class="form-control"
                       step="0.01" min="0" max="100"
                       value="{{ $factura->porcentaje_impuesto ?? ($factura->id ? ($factura->subtotal > 0 ? number_format(($factura->impuesto / $factura->subtotal) * 100, 2) : 0) : 19) }}"
                       placeholder="Ej: 19 para 19%" oninput="calcularImpuestoYTotal()">
            </div>

            {{-- Impuesto --}}
            <div class="form-group col-lg-3">
                <label for="txtImpuesto">Impuesto:</label>
                <input type="number" id="txtImpuesto" class="form-control" step="0.01"
                       value="{{ $factura->impuesto ?? old('impuesto', 0.00) }}" disabled>
                <input type="hidden" id="hiddenImpuesto" name="impuesto" value="{{ $factura->impuesto ?? old('impuesto', 0.00) }}">
            </div>

            {{-- Total --}}
            <div class="form-group col-lg-3">
                <label for="txtTotalFactura">Total Factura: *</label>
                <input type="number" id="txtTotalFactura" class="form-control" step="0.01"
                       value="{{ $factura->total_factura ?? old('total_factura', 0.00) }}" required disabled>
                <input type="hidden" id="hiddenTotalFactura" name="total_factura" value="{{ $factura->total_factura ?? old('total_factura', 0.00) }}">
            </div>

            {{-- Estado --}}
            <div class="form-group col-lg-6">
                <label for="lstEstado">Estado: *</label>
                <select id="lstEstado" name="estado" class="form-control" required>
                    <option value="PENDIENTE" {{ ($factura->estado ?? 'PENDIENTE') == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                    <option value="PAGADA" {{ ($factura->estado ?? '') == 'PAGADA' ? 'selected' : '' }}>PAGADA</option>
                    <option value="ANULADA" {{ ($factura->estado ?? '') == 'ANULADA' ? 'selected' : '' }}>ANULADA</option>
                </select>
            </div>
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
            url: "{{ route('facturacion.eliminar', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', globalId),
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
                        window.location.href = "/admin/facturacion";
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
    
    function calcularImpuestoYTotal() {
        var subtotal = parseFloat(document.getElementById('txtSubtotal').value) || 0;
        var porcentajeImpuesto = parseFloat(document.getElementById('txtPorcentajeImpuesto').value) || 0;
        
        var impuesto = (subtotal * porcentajeImpuesto) / 100;
        var total = subtotal + impuesto;
        
        document.getElementById('txtImpuesto').value = impuesto.toFixed(2);
        document.getElementById('txtTotalFactura').value = total.toFixed(2);
        
        document.getElementById('hiddenImpuesto').value = impuesto.toFixed(2);
        document.getElementById('hiddenTotalFactura').value = total.toFixed(2);
    }
    
    $(document).ready(function() {
        calcularImpuestoYTotal();
    });
</script>
@endsection
