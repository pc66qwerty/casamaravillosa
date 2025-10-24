<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Casa Maravillosa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-500 via-teal-500 to-blue-500 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl">
                <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">Casa Maravillosa</h1>
            <p class="text-white text-opacity-90">Crea tu cuenta</p>
        </div>

        <!-- Formulario de registro -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Registro</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           required 
                           autofocus
                           autocomplete="name"
                           placeholder="Juan Pérez"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           required
                           autocomplete="username"
                           placeholder="tu@email.com"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required
                           autocomplete="new-password"
                           placeholder="Mínimo 8 caracteres"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Contraseña
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           required
                           autocomplete="new-password"
                           placeholder="Repite tu contraseña"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Submit button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 rounded-lg hover:from-green-700 hover:to-teal-700 transition font-semibold shadow-lg">
                    Crear Cuenta
                </button>

            </form>

            <!-- Login -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-semibold">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>

        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-white text-sm">
            <p>&copy; 2025 Casa Maravillosa. Todos los derechos reservados.</p>
        </div>

    </div>

</body>
</html>