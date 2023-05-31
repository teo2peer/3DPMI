<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Impresiones;
use App\Models\Gcodes;
use App\Models\Impresoras;
use App\Models\Filamentos;    
use App\Models\Incidencias;    
use Auth;
use Session;
class ImpresionesController extends Controller
{
    
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
 
 
 
     /**------------------------------------------------------------------------
      *                           User data
      *------------------------------------------------------------------------**/
 
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
     
     
     /**------------------------------------------------------------------------
      *                           Gcodes manager
      *------------------------------------------------------------------------**/
     public function deleteGcode(Request $request, $id)
     {
         $gcode = Gcodes::find($id);
         $gcode->delete();
         return redirect()->back();
     }
 
     
     public function cura_upload(Request $request)
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
 
     /**------------------------------------------------------------------------
      *                           Impresiones manager
      *------------------------------------------------------------------------**/
 
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
 
 
}