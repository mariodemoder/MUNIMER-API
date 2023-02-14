<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;

//Para hacer un hashing de password, hay que invocar a la fachada Hash
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request) {
        // Validaciones para registrar un usuario
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        // Creamos al usuario
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        //$user->password = $request->password; //sin hashing
        $user->password = Hash::make($request->password);
        $user->save();

        // La API nos devuelve una respuesta
        return response()->json([
            "status" => 1,
            "msg" => 'Alta de Usuario exitosa'
        ]);

    }

    public function login(Request $request) {
        // Validaciones para hacer el login
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        $user = User::where("email", "=", $request->email)->first();
        if(isset($user->id)){             
            if(Hash::check($request->password, $user->password)) {                
               $token = $user->createToken("auth_token")->plainTextToken;                                 
                return response()->json([
                    "status" => 1,
                    "message" => "¡Usuario logueado exitosamente!",
                    "access_token" => $token
                ]);     
            }else{                
                return response()->json([
                    "status" => 0,
                    "message" => "La password es incorrecta"
                ], 404);    
            }            
        }else{
            return response()->json( [
                "status" => 0,
                "message" => "Usuario no registrado"
            ], 404);
        }        
    }

    public function userProfile() {        
        return response()->json([
            "status" => 1,
            "message" => "Acerca del perfil de usuario",
            "data" => auth()->user()
        ]);
    }
    
    public function logout() {        
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "Cierre de sesión OK"           
        ]);
    }
}
