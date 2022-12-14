<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $this->valididateData($request);

        $credentials = $request->only(['email', 'password']);

        if(Auth::attempt($credentials)){
            $token = $request->user()->createToken($request->user()->email);

            return $this->successResponse([
                'success'   =>  true,
                'user'      =>  $request->user(),
                'token'     =>  $token->plainTextToken
            ], 200); 
            // if(
            //     $request->user()->hasPermissionTo('login') || 
            //     $request->user()->hasPermissionTo('*')
            // ){
                
                
            // }else{
            //     return $this->errorResponse([
            //         'email' =>  'No tienes permisos para iniciar sesión'
            //     ], 'Error de validaciòn' ,422);        
            // }
        }

        return $this->errorResponse([
            'email' =>  'Correo o contraseña invalida'
        ], 'Error de validaciòn' ,422);
    }

    private function valididateData(Request $request)
    {
        $request->validate([
            'email'      => 'required',
            'password'  =>  'required'
        ],[
            'email.required'    =>  'Ingrese el correo electrónico',
            'password.required' =>  'Ingrese la contraseña'
        ]);
    }
}
