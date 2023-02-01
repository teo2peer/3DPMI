<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Request;
use App\Models\User;
use Auth;
class ErrorController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function NotAllowed(Request $Request)
    {
        $description = "Tu acceso a sido restringido";
        $message = "Tu correo no esta autorizado para acceder a esta pagina, utiliza el correo que te fue proporcionado por el administrador o el correo que fue autorizado";
        return view('errors.403', compact('message', 'description'));
    }
    public function CheckIsAllowed(Request $Request)
    {
        if(!Auth::check()) {
            return redirect("/login");
        }
        if (Auth::user() -> email_verified) {
            return redirect("/dashboard");
        }else {
            Auth::logout();
            $description = "Tu acceso a la pagina a sido solicitado";
            $message = "Tu correo esta autorizado para acceder a esta pagina, pero no ha sido autorizao por el administrador. Vuelve a intentarlo mas tarde";
            return view('errors.403', compact('message', 'description'));
        }
    }

}