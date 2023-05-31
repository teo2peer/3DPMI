<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Impresiones;
use App\Models\Incidencias;
use App\Models\Impresoras;
use Auth;
use Session;
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

    public function incidencias(Request $request)
    {
        
        $incidencias = Incidencias::All()->load('impresoras')->load('puestos_por')->load('resueltos_por');
        $impresoras = Impresoras::all();

        return view('dashboard.incidencias', ['incidencias' => $incidencias, 'impresoras' => $impresoras]);

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