<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class VisitanteController extends Controller
{
    //
    public function _construct(){

        $this->middleware('guest:visitantes_login');

    }

    public function index(){
        return view('auth.visitanteslogin');
    }

    public function login(Request $request){
        //return true;
        $this->validate($request, [
            'cpf'       => 'required|cpf',
            'password'  => 'required|min:3',
        ]);

        $credentials = [
            'cpf'       => preg_replace("/[^0-9]/", "", $request->cpf),
            'password'  => $request->password
        ];
    
        $AuthOk = Auth::guard('visitantes_login')->attempt($credentials, $request->remember);

        if($AuthOk){
            return redirect()->intended(route('visitante'));
        }

        return redirect()->back()->withInput($request->only('cpf', 'remember'));
    }
}