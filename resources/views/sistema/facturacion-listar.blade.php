@extends('plantilla')

@section('titulo')
Listado de Facturas
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
    <li class="breadcrumb-item active">Facturación</li>
    <li class="breadcrumb-item active">Listado de Facturas</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="{{ route('facturacion.index') }}">
            <i class="fa-solid fa-plus-circle" aria-hidden="true"></i>
            <span>Nuevo</span>
        </a>
    </li>
    <li class="btn-item">
        <a title="Salir" href="/admin">
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
                <h3 class="card-title">Listado de Facturas</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Número de Factura</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Subtotal</th>
                            <th>Impuesto</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                        <tr>
                            <td>{{ $factura->id }}</td>
                            <td>{{ $factura->numero_factura }}</td>
                            <td>{{ $factura->fecha }}</td>
                            <td>
                                Cliente relacionado
                            </td>
                            <td>{{ $factura->subtotal }}</td>
                            <td>{{ $factura->impuesto }}</td>
                            <td>{{ $factura->total_factura }}</td>
                            <td>{{ $factura->estado }}</td>
                            <td>
                                <a href="{{ route('facturacion.editar', $factura->id) }}" class="btn btn-primary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('facturacion.eliminar') }}" style="display: inline-block;" 
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta factura?')"
                                    >
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="id" value="{{ $factura->id }}">
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