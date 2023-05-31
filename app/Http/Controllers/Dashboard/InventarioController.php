<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zonas;    
use App\Models\Subzonas;    
use App\Models\Categorias;    
use App\Models\Productos;    
use Auth;
use Session;

class InventarioController extends Controller
{
    
    public function zonas(Request $request)
    {
            $zonas = Zonas::All();
            $subzonas = Subzonas::All();
            return view('dashboard.inventario.zonas', ['zonas' => $zonas, 'subzonas' => $subzonas]);
    }
    public function zonas_imprimir(Request $request)
    {
            $zonas = Zonas::All();
            return view('dashboard.inventario.imprimir_etiquetas', ['zonas' => $zonas]);
    }

    public function zonas_add(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $zona = new Zonas;
        $zona->name = $name;
        $zona->descripcion = $description;
        $zona->save();
        return redirect()->back();
    }
    // delete
    public function zonas_delete(Request $request, $id)
    {
        $zona = Zonas::find($id);
        $zona->delete();
        return redirect()->back();
    }

    public function subzonas_add(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $zona_id = $request->zona_id;

        $subzonas = new Subzonas;
        $subzonas->name = $name;
        $subzonas->descripcion = $description;
        $subzonas->zona_id = $zona_id;
        $subzonas->barcode = "";
        $subzonas->save();

       
        $barcode = "1".str_pad($subzonas->id, 6, "0", STR_PAD_LEFT);
        $subzonas->barcode = $barcode;
        $subzonas->save();


        return redirect()->back();
    }

    // get subzonas by zona
    public function subzonas_by_zona(Request $request, $id)
    {
        $subzonas = Subzonas::where('zona_id', $id)->get();
        return $subzonas;
    }

    // get subzonas barcodes by zona
    public function subzonas_barcodes_by_zona(Request $request, $id)
    {
        $subzonas = Subzonas::where('zona_id', $id)->get();
        $barcodes = array();
        $name = array();
        foreach ($subzonas as $subzona) {
            $barcodes[] = $subzona->barcode;
            $name[] = $subzona->name;
        }
        return [
            'barcodes' => $barcodes,
            'name' => $name
        ];
    }

    // delete   
    public function subzonas_delete(Request $request, $id)
    {
        $subzona = Subzonas::find($id);
        $subzona->delete();
        return redirect()->back();
    }


    // categorias
    public function categorias(Request $request)
    {
        $categorias = Categorias::All();
        return view('dashboard.inventario.categories', ['categorias' => $categorias]);
    }

    public function categorias_add(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $categoria = new Categorias;
        $categoria->name = $name;
        $categoria->descripcion = $description;
        $categoria->save();
        return redirect()->back();
    }
    // delete categorias
    public function categorias_delete(Request $request, $id)
    {
        $categoria = Categorias::find($id);
        $categoria->delete();
        return redirect()->back();
    }
        

    // inventario
    public function inventario(Request $request)
    {
        $productos = Productos::All()->load('subzona')->load('zona')->load('user');
        $subzonas = Subzonas::All();
        $zonas = Zonas::All();
        $categorias = Categorias::All();
        return view('dashboard.inventario.index', ['productos' => $productos, 'subzonas' => $subzonas, 'zonas' => $zonas, 'categorias' => $categorias]);
    }

    public function inventario_add(Request $request)
    {
        $name = $request->name;
        $description = $request->description ?? "";
        $cantidad = $request->cantidad;
        $precio = $request->precio;
        $barcode = $request->barcode_input;
        $categoria_id = $request->categoria_id;
        Session::put('barcode_subzona', $barcode);

        // remove the last digit of the barcode
        
        $subzona = Subzonas::where('barcode', substr($barcode, 0, -1))->first();
        $subzona_id = $subzona->id;
        $zona_id = $subzona->zona_id;

        $user_id = Auth::id();
        $producto = new Productos;
        $producto->name = $name;
        $producto->descripcion = $description;
        $producto->cantidad = $cantidad;
        $producto->precio = $precio;
        $producto->subzona_id = $subzona_id;
        $producto->zona_id = $zona_id;
        $producto->categoria_id = $categoria_id;
        $producto->user_id = Auth::id();
        $producto->save();


        // generate barcode
        $barcode = "1".str_pad($producto->id, 6, "0", STR_PAD_LEFT);

        $producto->barcode = $barcode;
        $producto->save();

        return redirect()->back();
    }
    // delete producto
    public function inventario_delete(Request $request, $id)
    {
        $producto = Productos::find($id);
        $producto->delete();
        return redirect()->back();
    }

     public function buscador_get(Request $request)
    {
        $barcode = substr($request->barcode, 0, -1);
        
        // check if the first digit is 1
        if(substr($barcode, 0, 1) == 1){
            // check if the barcode is a subzona
            $subzona = Subzonas::where('barcode', $barcode)->with('zona')->first();
            $productos = Productos::where('subzona_id', $subzona->id)->get();
            if($subzona){
                // return the subzona
                return [
                    "productos" => $productos,
                    "subzona" => $subzona->name,
                    "zona" => $subzona->zona->name,
                    "status" => 200
                ];
            } else {
                // return 400
                return [
                    "status" => 404
                ];
            }
        } else {
            // check if the barcode is a product
            $producto = Productos::find(substr($barcode, 1))->load('subzona')->load('zona');
            if($producto){
                // return the product
                return [
                    "productos" => [$producto],
                    "subzona" => $producto->subzona->name,
                    "zona" => $producto->zona->name,
                    "status" => 200
                ];
            } else {
                // return 400
                return [
                    "status" => 404
                ];
            }
        }

    }
}