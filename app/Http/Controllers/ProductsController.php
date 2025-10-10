<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entidades\Products;
use Illuminate\Support\Facades\Validator;
use App\Entidades\Cliente as EntidadCliente; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ProductsController extends Controller
{
    public function index()
    {
        $titulo = "producto";

        return view('sistema.producto-nuevo', compact('titulo', ));
    }
    
    public function listar()
    {
        $titulo = "producto";
        
        // Obtener todos los productos de la base de datos
        $productos = \App\Entidades\Products::all();

        return view('sistema.producto-listar', compact('titulo', 'productos'));
    }
    
    public function guardar(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'cantidad' => 'required|integer|min:0',
                'imagen_archivo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            ]);

            
            $nombreImagen = null;
            if ($request->hasFile('imagen_archivo')) {
                $imagen = $request->file('imagen_archivo');
                
                // Asegurarse de que el directorio existe
                $directorio = public_path('storage/productos');
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0755, true);
                }
                
                $nombreImagen = Str::uuid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $directorio . '/' . $nombreImagen;
                
                // Mover archivo sin procesamiento de imagen
                $imagen->move($directorio, $nombreImagen);
            }

            
            $producto = Products::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion ?? null,
                'precio' => $request->precio,
                'cantidad' => $request->cantidad,
                'imagen' => $nombreImagen, 
            ]);

            return redirect()
                ->route('productos.listar')
                ->with('msg', ['MSG' => 'Producto creado correctamente', 'ESTADO' => 'success']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validación fallida', 'detalles' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar producto', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withInput()
                ->with('msg', ['MSG' => 'Ocurrió un error al guardar el producto: ' . $e->getMessage(), 'ESTADO' => 'danger']);
        }
    }
    
    public function editar($id)
    {
        $producto = \App\Entidades\Products::find($id);
        
        if (!$producto) {
            return redirect()->route('producto.listar')->with('msg', [
                'MSG' => 'Producto no encontrado', 
                'ESTADO' => 'danger'
            ]);
        }
        
        $titulo = "Editar producto";
        
        return view('sistema.producto-nuevo', compact('titulo', 'producto'));
    }
    
    public function actualizar(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'cantidad' => 'required|integer|min:0',
                'imagen_archivo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            ]);

            $producto = \App\Entidades\Products::find($id);
            
            if (!$producto) {
                return redirect()->route('producto.listar')->with('msg', [
                    'MSG' => 'Producto no encontrado', 
                    'ESTADO' => 'danger'
                ]);
            }

            // Manejar la actualización de la imagen
            $nombreImagen = $producto->imagen; // Mantener la imagen actual por defecto
            if ($request->hasFile('imagen_archivo')) {
                // Eliminar la imagen anterior si existe
                if ($nombreImagen) {
                    $rutaImagenAnterior = public_path('storage/productos/' . $nombreImagen);
                    if (file_exists($rutaImagenAnterior)) {
                        unlink($rutaImagenAnterior);
                    }
                }
                
                $imagen = $request->file('imagen_archivo');
                
                // Asegurarse de que el directorio existe
                $directorio = public_path('storage/productos');
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0755, true);
                }
                
                $nombreImagen = \Illuminate\Support\Str::uuid() . '.' . $imagen->getClientOriginalExtension();
                $ruta = $directorio . '/' . $nombreImagen;
                
                // Mover archivo sin procesamiento de imagen
                $imagen->move($directorio, $nombreImagen);
            }

            // Actualizar los datos del producto
            $producto->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion ?? null,
                'precio' => $request->precio,
                'cantidad' => $request->cantidad,
                'imagen' => $nombreImagen,
            ]);

            return redirect()
                ->route('producto.listar')
                ->with('msg', ['MSG' => 'Producto actualizado correctamente', 'ESTADO' => 'success']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al actualizar producto', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withInput()
                ->with('msg', ['MSG' => 'Ocurrió un error al actualizar el producto: ' . $e->getMessage(), 'ESTADO' => 'danger']);
        }
    }
    
    public function eliminar(Request $request)
    {
        try {
            $id = $request->input('id');
            
            $producto = \App\Entidades\Products::find($id);
            
            if (!$producto) {
                return redirect()->route('producto.listar')->with('msg', [
                    'MSG' => 'Producto no encontrado', 
                    'ESTADO' => 'danger'
                ]);
            }
            
            // Eliminar la imagen del disco si existe
            if ($producto->imagen) {
                $rutaImagen = public_path('storage/productos/' . $producto->imagen);
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }
            
            $producto->delete();
            
            return redirect()->route('producto.listar')->with('msg', [
                'MSG' => 'Producto eliminado correctamente', 
                'ESTADO' => 'success'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar producto', ['error' => $e->getMessage()]);
            
            return redirect()->route('producto.listar')->with('msg', [
                'MSG' => 'Ocurrió un error al eliminar el producto: ' . $e->getMessage(), 
                'ESTADO' => 'danger'
            ]);
        }
    }

}