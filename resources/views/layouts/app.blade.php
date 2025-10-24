<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Casa Maravillosa - Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5b6cf2',
                        secondary: '#ec4899',
                        accent: '#22d3ee',
                        warning: '#fbbf24',
                    }
                }
            }
        }
    </script>

    <!-- SCRIPT DE ALPINE.JS MOVIDO AL FINAL DEL BODY -->

</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-8 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-gray-500 text-sm">@yield('page-subtitle', 'Bienvenido al panel de control')</p>
                        </div>
                    <div class="flex items-center gap-4">
                        <input type="date" value="{{ date('Y-m-d') }}"
                            class="border rounded-lg px-4 py-2">
                        <button
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Exportar Datos
                            </button>
                        </div>
                    </div>
                </header>
            
            <!-- Page Content -->
            <main class="flex-1 p-8">
                @yield('content')
                </main>
            </div>
        </div>

    <!-- SCRIPT DE ALPINE.JS AÑADIDO AL FINAL -->
    
    <script src="//unpkg.com/alpinejs"></script>
</body>

</html>
