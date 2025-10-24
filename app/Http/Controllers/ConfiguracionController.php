<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        return view('configuracion.index', compact('usuario'));
    }

    public function actualizarPerfil(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($validated);

        return redirect()->route('configuracion.index')
            ->with('success', 'Perfil actualizado correctamente');
    }

    public function actualizarPassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'La contraseña actual no es correcta');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('configuracion.index')
            ->with('success', 'Contraseña actualizada correctamente');
    }
}