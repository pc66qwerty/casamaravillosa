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
                        
                        @if(View::hasSection('export-button'))
                            @yield('export-button')
                        @else
                            <button id="exportBtn"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                </svg>
                                Exportar Datos
                            </button>
                        @endif
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Script de Alpine.js -->
    <script src="//unpkg.com/alpinejs"></script>
    
    <!-- Script para exportación automática -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportBtn = document.getElementById('exportBtn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    const currentPath = window.location.pathname;
                    const params = new URLSearchParams(window.location.search);
                    
                    // Determinar la ruta de exportación según la página actual
                    let exportUrl = '';
                    
                    if (currentPath.includes('/clientes')) {
                        exportUrl = '/clientes/export';
                    } else if (currentPath.includes('/productos')) {
                        exportUrl = '/productos/export';
                    } else if (currentPath.includes('/ventas')) {
                        exportUrl = '/ventas/export';
                    } else if (currentPath.includes('/inventario')) {
                        exportUrl = '/inventario/export';
                    } else if (currentPath.includes('/categorias')) {
                        exportUrl = '/categorias/export';
                    } else {
                        alert('La exportación no está disponible en esta página');
                        return;
                    }
                    
                    // Redirigir a la URL de exportación con los parámetros de filtro
                    window.location.href = exportUrl + '?' + params.toString();
                });
            }
        });
    </script>
</body>

</html>