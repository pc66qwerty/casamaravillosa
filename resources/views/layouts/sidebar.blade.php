<aside class="w-64 bg-gray-900 text-white flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-800">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 p-3 rounded-lg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-lg">Casa Maravillosa</h2>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <p class="text-xs text-gray-500 uppercase font-semibold mb-3">Principal</p>
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>
        
        <p class="text-xs text-gray-500 uppercase font-semibold mt-6 mb-3">Gestión</p>
        
        <a href="{{ route('clientes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('clientes.*') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <span>Clientes</span>
        </a>
        
        <a href="{{ route('categorias.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('categorias.*') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
            </svg>
            <span>Categorías</span>
        </a>
        
        <a href="{{ route('productos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('productos.*') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
            </svg>
            <span>Productos</span>
        </a>
        
        <a href="{{ route('inventario.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('inventario.*') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
            <span>Inventario</span>
            <span class="ml-auto bg-red-500 text-xs px-2 py-1 rounded-full">
                {{ \App\Models\Producto::whereHas('inventarios', function($q) {
                    $q->whereRaw('cantidad_actual <= productos.stock_minimo');
                })->count() }}
            </span>
        </a>
        
        <a href="{{ route('ventas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('ventas.*') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
            </svg>
            <span>Ventas</span>
        </a>
        
        <a href="{{ route('reportes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('reportes.*') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
            </svg>
            <span>Reportes</span>
        </a>
        
        <p class="text-xs text-gray-500 uppercase font-semibold mt-6 mb-3">Sistema</p>
        
        <a href="{{ route('configuracion.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition {{ request()->routeIs('configuracion.*') ? 'bg-cyan-500 text-white' : '' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
            </svg>
            <span>Configuración</span>
        </a>
    </nav>
    
    <!-- User Info -->
    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center gap-3">
            <div class="bg-pink-500 w-10 h-10 rounded-full flex items-center justify-center font-bold text-white">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="flex-1">
                <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-white transition" title="Cerrar Sesión">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>