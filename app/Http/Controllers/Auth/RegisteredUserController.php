<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        // Validar el registro
        $data = $request->validated();

        // Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Comprobar si el usuario está autenticado
        // if (Auth::check()) {
        //     // Si está autenticado, devolver una respuesta positiva
        //     return response()->json(['status' => 'Usuario autenticado correctamente.'], 200);
        // } else {
        //     // Si no está autenticado, devolver un error
        //     return response()->json(['status' => 'Error en la autenticación.'], 400);
        // }

        // Respuesta
        return [
            'type' => 'success',
            'message' => 'Usuario creado correctamente'
        ];
    }
}
