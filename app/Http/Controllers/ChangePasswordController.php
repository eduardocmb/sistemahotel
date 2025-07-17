<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de que esta línea esté presente
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('auth.passwords.change', compact('user'));
    }


    public function update(Request $request, $user_id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'current_password' => 'required',
        ], [
            'password.required' => 'La nueva contraseña es requerida',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'current_password.required' => 'La contraseña actual es requerida',
        ]);

        $user = \App\Models\User::find($user_id);

        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Las contraseña actual y la ingresada no coinciden');
        }

        $user->password = bcrypt($request->password);
        $user->cambiar = "N";
        $user->save();

        return redirect()->route('login')->with('success', 'Contraseña actualizada con éxito');
    }
}
