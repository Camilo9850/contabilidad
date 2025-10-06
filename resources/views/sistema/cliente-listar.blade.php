@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    $(document).ready(function() {
        $('#dtCliente').DataTable({
            "order": [[ 0, "asc" ]],
            "pageLength": 25,
            "language": {
                "url": "{{ asset('js/spanish.json') }}"
            },
            responsive: true
        });
    });
</script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item active">Clientes</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="{{ route('cliente.index') }}">
            <i class="fa-solid fa-plus-circle" aria-hidden="true"></i>
            <span>Nuevo</span>
        </a>
    </li>
    <li class="btn-item">
        <a title="Salir" href="/admin/home">
            <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
            <span>Salir</span>
        </a>
    </li>
</ol>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Listado de Clientes</h3>
            </div>
            <div class="card-body">
                <table id="dtCliente" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Celular</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->idcliente }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->apellido }}</td>
                            <td>{{ $cliente->correo }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>{{ $cliente->celular }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td>
                                <a href="{{ route('cliente.editar', ['idcliente' => $cliente->idcliente]) }}" class="btn btn-primary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('cliente.eliminar', ['idcliente' => $cliente->idcliente]) }}" style="display: inline-block;" 
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection