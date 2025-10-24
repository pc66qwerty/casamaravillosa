@extends('layouts.app')

@section('title', 'Configuración - Casa Maravillosa')
@section('page-title', 'Configuración')
@section('page-subtitle', 'Administra tu cuenta y preferencias')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        {{ session('error') }}
    </div>
    @endif

    <!-- Información del Perfil -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Información del Perfil</h3>
        
        <form action="{{ route('configuracion.perfil') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Actualizar Perfil
                </button>
            </div>
        </form>
    </div>

    <!-- Cambiar Contraseña -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Cambiar Contraseña</h3>
        
        <form action="{{ route('configuracion.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>

    <!-- Información del Sistema -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Información del Sistema</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Versión del Sistema</p>
                <p class="font-semibold">1.0.0</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Laravel</p>
                <p class="font-semibold">{{ app()->version() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Fecha de Registro</p>
                <p class="font-semibold">{{ $usuario->created_at->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Última Actualización</p>
                <p class="font-semibold">{{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

</div>
@endsection