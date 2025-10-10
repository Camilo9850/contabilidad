@extends('plantilla')

@section('titulo')
Listado de Productos
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
    <li class="breadcrumb-item active">producto</li>
    <li class="breadcrumb-item active">Listado de producto</li>
</ol>
<ol class="toolbar">
    <li class="btn-item">
        <a title="Nuevo" href="{{ route('producto.index') }}">
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
                <h3 class="card-title">Listado de producto</h3>
            </div>
            <div class="card-body">
                @if(session('msg'))
                    <div class="alert alert-{{ session('msg')['ESTADO'] == 'success' ? 'success' : 'danger' }}">
                        {{ session('msg')['MSG'] }}
                    </div>
                @endif

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->descripcion }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>{{ $producto->cantidad }}</td>
                            <td>
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" width="50">
                                @else
                                    Sin imagen
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('producto.editar', $producto->id) }}" class="btn btn-primary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('producto.eliminar') }}" style="display: inline-block;" 
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?')"
                                    >
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="id" value="{{ $producto->id }}">
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