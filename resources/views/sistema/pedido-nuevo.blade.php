@extends('plantilla')
@section('titulo', "$titulo")

@section('scripts')
<script>
    // Definición de ID global desde la variable de Laravel
    globalId = '{{ isset($pedido->idpedido) && $pedido->idpedido > 0 ? $pedido->idpedido : 0 }}';
    <?php $globalId = isset($pedido->idpedido) ? $pedido->idpedido : "0"; ?>
</script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pedido.listar') }}">pedido</a></li>
    <li class="breadcrumb-item active">{{ isset($pedido) && $pedido->idpedido ? 'Editar pedido' : 'Nuevo pedido' }}</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="/admin/pedido/nuevo">
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
        location.href = "/admin/pedido";
    }
</script>
@endsection

@section('contenido')
@if (isset($msg))
<div id="msg"></div>
<script>
    msgShow("{{ $msg['MSG'] }}", "{{ $msg['ESTADO'] }}")
</script>
@endif

<div class="panel-body">
    <div id="msg"></div>


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
    <form id="form1" method="POST" action="{{ isset($pedido) && $pedido->idpedido > 0 ? route('pedido.actualizar', $pedido->idpedido) : route('pedido.guardar') }}">
        @csrf

        {{-- Método para PUT/PATCH si es actualización --}}
        @if(isset($pedido) && $pedido->idpedido)
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

            {{-- 1. CLIENTE (fk_cliente BIGINT) --}}
            <div class="form-group col-lg-6">
                <label for="lstCliente">Cliente: *</label>
                <select name="cliente_id" id="lstCliente" class="form-control" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" 
                            {{ (isset($pedido->fk_cliente) && $pedido->fk_cliente == $cliente->id) ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 2. ESTADO (fk_idestadopedido INT) --}}
            <div class="form-group col-lg-6">
                <label for="lstEstado">Estado del Pedido: *</label>
                <select id="lstEstado" name="estado" class="form-control" required>
                    <option value="1" {{ (isset($pedido->fk_idestadopedido) && $pedido->fk_idestadopedido == 1) ? 'selected' : '' }}>PENDIENTE</option>
                    <option value="2" {{ (isset($pedido->fk_idestadopedido) && $pedido->fk_idestadopedido == 2) ? 'selected' : '' }}>EN PROCESO</option>
                    <option value="3" {{ (isset($pedido->fk_idestadopedido) && $pedido->fk_idestadopedido == 3) ? 'selected' : '' }}>ENTREGADO</option>
                    <option value="4" {{ (isset($pedido->fk_idestadopedido) && $pedido->fk_idestadopedido == 4) ? 'selected' : '' }}>CANCELADO</option>
                </select>
            </div>

            {{-- 3. FECHA (DATE) --}}
            <div class="form-group col-lg-4">
                <label for="txtFecha">Fecha: *</label>
                <input type="date" id="txtFecha" name="fecha" class="form-control"
                    value="{{ $pedido->fecha ? \Carbon\Carbon::parse($pedido->fecha)->format('Y-m-d') : old('fecha', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
            </div>

            {{-- 4. HORA (TIME) --}}
            <div class="form-group col-lg-4">
                <label for="txtHora">Hora:</label>
                <input type="time" id="txtHora" name="hora" class="form-control"
                    value="{{ $pedido->hora ?? old('hora', \Carbon\Carbon::now()->format('H:i')) }}">
            </div>

            {{-- 5. SUBTOTAL (DECIMAL 15,2) --}}
            <div class="form-group col-lg-4">
                <label for="txtSubtotal">Subtotal: *</label>
                <input type="number" id="txtSubtotal" name="subtotal" class="form-control" step="0.01"
                    value="{{ $pedido->subtotal ?? old('subtotal', 0.00) }}" required>
            </div>

            {{-- 6. TOTAL (DECIMAL 15,2) --}}
            <div class="form-group col-lg-4">
                <label for="txtTotal">Total: *</label>
                <input type="number" id="txtTotal" name="total" class="form-control" step="0.01"
                    value="{{ $pedido->total ?? old('total', 0.00) }}" required>
                <small class="form-text text-muted">Monto final a pagar.</small>
            </div>


        </div>
    </form>
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
                url: "{{ route('pedido.eliminar', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', globalId),
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
    </script>
    @endsection