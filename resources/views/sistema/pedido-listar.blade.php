@extends('plantilla')

@section('titulo')
Listado de Pedidos
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
    <li class="breadcrumb-item active">pedido</li>
    <li class="breadcrumb-item active">Listado de pedido</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="{{ route('pedido.index') }}">
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
                <h3 class="card-title">Listado de pedido</h3>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{ session('msg')['ESTADO'] == 'success' ? 'success' : 'danger' }}">
                    {{ session('msg')['MSG'] }}
                </div>
                @endif

                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Subtotal</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente->nombre }}</td>
                            <td>
                                @if($pedido->estado === 'EN_PROCESO')
                                <span class="badge bg-warning text-dark">En proceso</span>
                                @elseif($pedido->estado === 'COMPLETADO')
                                <span class="badge bg-success">Completado</span>
                                @elseif($pedido->estado === 'CANCELADO')
                                <span class="badge bg-danger">Cancelado</span>
                                @else
                                <span class="badge bg-secondary">{{ $pedido->estado }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $pedido->hora }}</td>
                            <td>${{ number_format($pedido->subtotal, 2) }}</td>
                            <td>${{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <a href="{{ route('pedido.editar', $pedido->id) }}" class="btn btn-primary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('pedido.eliminar', $pedido->id) }}"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este pedido?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay pedidos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection