<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Impresiones;
use App\Models\Gcodes;
use App\Models\Impresoras;
use App\Models\Filamentos;    
use App\Models\Incidencias;    
use App\Models\Zonas;    
use App\Models\Subzonas;    
use App\Models\Categorias;    
use App\Models\Productos;    
use Auth;
class DashboardController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function index()
    {
        if(Auth::check()){
            $impresiones = Impresiones::where('user_id', Auth::id())->with('impresoras')->with('filamentos')->with('puestos_por')->get();

            
            return view('dashboard.index', ['impresiones'=>$impresiones]);
        }else{
            return redirect('/login');
        }
    }

    public function manageUsers(Request $request)
    {
        // Get all users and return into an array
        $users = User::all();        
        // return json_encode($users);  
        // return json_encode($users->toArray());      
        return view('dashboard.manageUsers', ['users' => $users]);  
    }

    public function userDetails(Request $request, $id)
    {
        // Get all users and return into an array
        $users = User::find($id)->load('filamentos');
        
        $impresiones = Impresiones::where('user_id', $id)->with('impresoras')->with('filamentos')->with('puestos_por')->get();
        // return json_encode($users->toArray());     
        //return json_encode($impresiones);
        return view('dashboard.user.details', ['user' => $users, 'impresiones'=>$impresiones]);  
    }

    /**------------------------------------------------------------------------
     *                           Filamentos
     *------------------------------------------------------------------------**/

    public function filamentos(Request $Request)
    {
        $filamentos = Filamentos::where('user_id', Auth::id())->get();
        return view('dashboard.filamentos.index', ['filamentos' => $filamentos]);
    }
    public function filamentos_add(Request $request)
    {
        $nombre = $request->name;
        $type = $request->type;
        $name = $nombre . ' | ' . $type;

        $filamento = new Filamentos;
        $filamento->user_id = Auth::id();
        $filamento->name = $name;
        $filamento->save();
        return redirect()->back();
    }

    public function filamentos_delete(Request $request, $id)
    {
        $filamento = Filamentos::find($id);
        $filamento->delete();
        return redirect()->back();
    }


    
    
    /**------------------------------------------------------------------------
     *                           Impresoras
     *------------------------------------------------------------------------**/
    

    
    public function impresoras(Request $request)
    {
        $impresoras = Impresoras::all();
        return view('dashboard.impresoras', ['impresoras' => $impresoras]);
    }

    public function impresoras_add(Request $request)
    {
        $name = $request->name;
        $port = $request->port;
        $ip = $request->ip;
        $type = $request->type;
        
        $impresora = new Impresoras;
        $impresora->name = $name;
        $impresora->port = $port;
        $impresora->ip = $ip;
        $impresora->tipo = $type;
        $impresora->save();
        return redirect()->back();
    }
    
    public function impresoras_delete(Request $request, $id)
    {
        $impresora = Impresoras::find($id);
        $impresora->delete();
        return redirect()->back();
    }

    public function impresoras_alter(Request $request, $id)
    {
        $impresora = Impresoras::find($id);
        // alternate estado
        if($impresora->estado == 0){
            $impresora->estado = 1;
        }else{
            $impresora->estado = 0;
        }
        $impresora->save();
        return redirect()->back();
    }





    public function userImpresiones(Request $request)
    {
        
        $impresiones = Impresiones::where('user_id', Auth::id())->with('impresoras')->with('filamentos')->with('puestos_por')->with('gcode')->get();

        // $impresoras = Impresoras::all();
        // $filamentos = Filamentos::where('user_id', Auth::id())->get();
        return view('dashboard.user.impresiones', ['impresiones' => $impresiones]);
    }
    public function userGcodes(Request $request)
    {
        
        $gcodes = Gcodes::where('user_id', Auth::id())->with('impresiones')->with('user')->get();
        $impresoras  = Impresoras::all();
        $filamentos = Filamentos::where('user_id', Auth::id())->get();
        $impresiones = Impresiones::where('user_id', Auth::id())->with('puestos_por')->get();

        return view('dashboard.user.gcodes', ['gcodes' => $gcodes, 'impresoras' => $impresoras, 'filamentos' => $filamentos, 'impresiones' => $impresiones]);
    }
    // deltete
    public function deleteGcode(Request $request, $id)
    {
        $gcode = Gcodes::find($id);
        $gcode->delete();
        return redirect()->back();
    }

    
    public function curaUpload(Request $request)
    {
        // $request->validate([
        //     'gcode' => 'required|mimes:gcode|max:2048',
        // ]);
        
        $impresora = $request->impresora;
        $filamento = $request->filamento;
        $description = $request->description;
        $estado = 0;
        $filament_used =0;
        $time = 0;
        

        $temp_user = $request->user;
        if ($temp_user == 'hacks'){
            $user_id = 6;
            $user = User::find(6);
        }else{
            // find user by email
            $user = User::where('email', $temp_user)->first();
            $user_id = $user->id;
        }

        if ($request->file('gcode')) {
            try{
                    $gcode = $request->file('gcode');
                    
                    $location = public_path('gcodes/'.$user->name.'/'.$gcode->getClientOriginalName());
                    // create directory if not exists
                    if (!file_exists(public_path('gcodes/'.$user->name.'/'))) {
                        mkdir(public_path('gcodes/'.$user->name.'/'), 0777, true);
                    }
                    $gcode->move(public_path('gcodes/'.$user->name.'/'), $gcode->getClientOriginalName());
                    $path = $location;
            }catch(Throwable $e){
                    // return redirect()->back()->with('error', 'Error al subir el archivo');
                    return $e->getMessage();
            }
        }
        $name = $gcode->getClientOriginalName();
        
        
        $lines = file($location);
        $count = 0;
        //find line in gcode where is the time expected and the filament used 

        foreach($lines as $key=>$line) {
            $count += 1;
            if ($count >= 30){
                break;
            }
            // search for line ;Filament used: and get the value
            
            if (str_contains($line, ";Filament used")){
                $filament_used = floatval(  str_replace("m","",  str_replace(";Filament used:", "", trim($line)) ));
                //$filament_used = $line;
            }
            if (str_contains($line, ";TIME")){
                $time = intval( str_replace(";TIME:", "", trim($line)));
                //$time = $line;

            }
            if($time !=0 && $filament_used !=0){
                break;
            }
        }

        // get the last 25 lines of the gcode
        $last_lines = array_slice($lines, -25);
        // if in lines is creality_ender3 or CE3D is a Ender 3, if is ASX is a Artillery Sidewinder X1
        $made_for = "No definido";
        foreach($last_lines as $key=>$line) {
            if ((str_contains($line, "creality_ender3")) || (str_contains($line, "CE3"))){
                $made_for = "Ender 3";
            }
            else if (str_contains($line, "artillery_sidewinder_x1")){
                $made_for = "Artillery X1";
            }
        }
         $gcode = new Gcodes;
            $gcode->user_id = $user_id;
            $gcode->name = $name;
            $gcode->path = $path;
            $gcode->filament_used = $filament_used;
            $gcode->made_for = $made_for;
            $gcode->time = $time;

        
        

        if ($gcode->save()) {
            // return redirect()->back()->with('success', 'Impresion añadida correctamente');
            // return code 200
            return 200;
        }else{
            return redirect()->back()->with('error', 'Error al añadir la impresion');
        }
    }

    public function impresion_add(Request $request)
    {
        $gcode_id = $request->gcode;
        $impresora_id = $request->impresora;
        $filamento_id = $request->filamento;
        $description = $request->description??" ";
        $estado = 1;

        $gcode = Gcodes::find($gcode_id);

        $impresora = Impresoras::find($impresora_id);
        $impresora->estado = 2;
        $impresora->save();

        $filamento = Filamentos::find($filamento_id);
        $filamento->available = $filamento->available - $gcode->filament_used;
        $filamento->save();


        $impresion = new Impresiones;
        $impresion->user_id = Auth::id();
        $impresion->name = $gcode->name;
        $impresion->description = $description;
        $impresion->impresora_id = $impresora_id;
        $impresion->filamento_id = $filamento_id;
        $impresion->gcode_id = $gcode_id;
        $impresion->estado = $estado;
        $impresion->puesto_por = Auth::id();
        if ($impresion->save()) {
            return 200;
        }else{
            return 400;
        }
        

    }

    // public function addImpresionForm(Request $request)
    // {
    //     // $request->validate([
    //     //     'gcode' => 'required|mimes:gcode|max:2048',
    //     // ]);
    //     if ($request->file('gcode')) {
    //         try{
    //                 $gcode = $request->file('gcode');
                    
    //                 $location = public_path('gcodes/'.Auth::user()->name.'/'.$gcode->getClientOriginalName());
    //                 // create directory if not exists
    //                 if (!file_exists(public_path('gcodes/'.Auth::user()->name.'/'))) {
    //                     mkdir(public_path('gcodes/'.Auth::user()->name.'/'), 0777, true);
    //                 }
    //                 $gcode->move(public_path('gcodes/'.Auth::user()->name.'/'), $gcode->getClientOriginalName());
    //                 $path = $location;
    //         }catch(Throwable $e){
    //                 // return redirect()->back()->with('error', 'Error al subir el archivo');
    //                 return $e->getMessage();
    //         }
    //     }
    //     $impresora = $request->impresora;
    //     $filamento = $request->filamento;
    //     $name = $request->name;
    //     $description = $request->description;
    //     $estado = 0;
    //     $user_id = Auth::id();
    //     $filament_used =0;
    //     $time = 0;
        
    //     $lines = file($location);
    //     $count = 0;
    //     //find line in gcode where is the time expected and the filament used 

    //     foreach($lines as $key=>$line) {
    //         $count += 1;
    //         if ($count >= 30){
    //             break;
    //         }
    //         // search for line ;Filament used: and get the value
            
    //         if (str_contains($line, ";Filament used")){
    //             $filament_used = floatval(  str_replace("m","",  str_replace(";Filament used:", "", trim($line)) ));
    //             //$filament_used = $line;
    //         }
    //         if (str_contains($line, ";TIME")){
    //             $time = intval( str_replace(";TIME:", "", trim($line)));
    //             //$time = $line;

    //         }
    //         if($time !=0 && $filament_used !=0){
    //             break;
    //         }
    //     }



         

        
    //     $impresion = new Impresiones;
    //     $impresion->user_id = $user_id;
    //     $impresion->name = $name;
    //     $impresion->description = $description;
    //     $impresion->impresora = $impresora;
    //     $impresion->filamento = $filamento;
    //     $impresion->gcode = $path;
    //     $impresion->time = $time;
    //     $impresion->filament_used = $filament_used;
    //     $impresion->estado = $estado;
    //     $impresion->puesto_por = 1;

    //     if ($impresion->save()) {
    //         return redirect()->back()->with('success', 'Impresion añadida correctamente');
    //     }else{
    //         return redirect()->back()->with('error', 'Error al añadir la impresion');
    //     }
    // }


    


    public function impresion_start(Request $request, $id)
    {
        $impresion = Impresiones::find($id);
        $impresion->puesto_por = Auth::id();
        $impresion->estado = 1;
        $impresion->iniciado = date('Y-m-d H:i:s');
        $impresion->save();
        return redirect()->back();
    }

    public function impresion_finish(Request $request, $id)
    {
        $impresion = Impresiones::find($id);
        $impresion->estado = 2;
        $impresion->finalizado = date('Y-m-d H:i:s');
        $impresion->save();
        return redirect()->back();
    }
    public function impresion_error(Request $request, $id)
    {
        $impresion = Impresiones::find($id);
        $impresion->estado = 3;
        $impresion->finalizado = date('Y-m-d H:i:s');
        $impresion->save();
        return redirect()->back();
    }

    public function impresion_delete(Request $request, $id)
    {
        $impresion = Impresiones::find($id);
        $impresion->delete();
        return redirect()->back();
    }

    public function impresiones(Request $request)
    {
        
        $impresiones = Impresiones::All()->load('impresoras')->load('filamentos')->load('puestos_por')->load('user')->load('gcode');


        return view('dashboard.impresiones', ['impresiones' => $impresiones]);
    }


    public function incidencias(Request $request)
    {
        
        $incidencias = Incidencias::All()->load('impresoras')->load('puestos_por')->load('resueltos_por');
        $impresoras = Impresoras::all();

        return view('dashboard.incidencias', ['incidencias' => $incidencias, 'impresoras' => $impresoras]);

    }

    public function zonas(Request $request)
    {
            $zonas = Zonas::All();
            $subzonas = Subzonas::All();
            return view('dashboard.zonas', ['zonas' => $zonas, 'subzonas' => $subzonas]);
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

        // generate an ITF-14 barcode in a string: The first 3 digits are the zone, the next 5 the subzone and the last digit is the last 5 digits of the product
        // in this case the product is 00000
        // add the 0s to the left of the zone and subzone
        $fst_part_barcode = str_pad($zona_id, 4, "0", STR_PAD_LEFT);
        $snd_part_barcode = str_pad($subzonas->id, 4, "0", STR_PAD_LEFT);
        $barcode = $fst_part_barcode.$snd_part_barcode."00000";
        $subzonas->barcode = $barcode;
        $subzonas->save();


        return redirect()->back();
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
        return view('dashboard.categories', ['categorias' => $categorias]);
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
        return view('dashboard.inventario', ['productos' => $productos, 'subzonas' => $subzonas, 'zonas' => $zonas, 'categorias' => $categorias]);
    }

    public function inventario_add(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $cantidad = $request->cantidad;
        $precio = $request->precio;
        $subzona_id = $request->subzona_id;
        $zona_id = $request->zona_id;
        $categoria_id = $request->categoria_id;

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
        $fst_part_barcode = str_pad($zona_id, 4, "0", STR_PAD_LEFT);
        $snd_part_barcode = str_pad($subzona_id, 4, "0", STR_PAD_LEFT);
        $trd_part_barcode = str_pad($producto->id, 5, "0", STR_PAD_LEFT);
        $barcode = $fst_part_barcode.$snd_part_barcode.$trd_part_barcode;
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


    // registrar tarjeta
    public function addTarjeta(Request $request)
    {
        $uid = $request->uid;
        $pin = $request->pin;
        Auth::user()->uid = $uid;
        Auth::user()->pin = $pin;
        Auth::user()->save();
        return 200;
    }

    public function smartLogin(Request $request)
    {
        $uid = $request->uid;
        $user = User::where('uid', $uid)->first();
        if($user){
            // log them in
            auth()->login($user, true);
            return 200;
        } else {
            return 400;
        }
        return 400;
    }
}