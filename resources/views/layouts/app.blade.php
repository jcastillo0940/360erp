<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ERP 360 | Panamá</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="//unpkg.com/alpinejs@3.x.x"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        /* Personalización del Sidebar */
        .nav-active {
            background-color: #1e293b;
            color: white;
            border-right: 3px solid #3b82f6;
        }

        .sub-active {
            color: #60a5fa !important;
            font-weight: 600;
        }

        /* Estilo para las alertas de SweetAlert para que coincidan con Tailwind */
        .swal2-popup {
            font-family: 'Inter', sans-serif;
            border-radius: 12px;
        }

        .swal2-title {
            font-size: 1.25rem !important;
            color: #1f2937 !important;
        }

        .swal2-html-container {
            color: #4b5563 !important;
        }
    </style>
</head>

<body class="h-screen flex overflow-hidden text-slate-800">

    <aside class="w-72 bg-[#0f172a] text-slate-400 flex flex-col shadow-2xl z-50 transition-all duration-300"
        x-data="{ openMenu: '{{ request()->is('rutas*', 'repartidores*', 'repartidor/dashboard') ? 'reparto' : (request()->is('ordenes*', 'proveedores*', 'facturas_compra*', 'notas_debito*') ? 'compras' : (request()->is('facturas*', 'cotizaciones*', 'entregas*') ? 'ventas' : (request()->is('items*', 'bodegas*', 'kardex*', 'ajustes*') ? 'inv' : null))) }}' }">

        <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-[#0f172a]">
            <div class="flex items-center gap-3 text-white">
                <div class="bg-blue-600 p-1.5 rounded-lg shadow-lg shadow-blue-500/20"><i
                        class="fas fa-cube text-lg"></i></div>
                <div>
                    <h1 class="font-bold text-lg tracking-tight leading-none">ERP 360</h1><span
                        class="text-[10px] font-semibold text-blue-400 tracking-wider uppercase">Panamá</span>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scroll">
            <a href="{{ route('reportes.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-all">
                <i class="fas fa-chart-pie w-5 text-center"></i><span class="font-medium text-sm">Dashboard</span>
            </a>

            <div class="px-3 mt-6 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-600">Módulos
                Operativos</div>

            <div>
                <button @click="openMenu === 'compras' ? openMenu = null : openMenu = 'compras'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-all"
                    :class="{'bg-slate-800 text-white': openMenu === 'compras'}">
                    <div class="flex items-center gap-3"><i
                            class="fas fa-shopping-cart w-5 text-center text-purple-500"></i><span
                            class="font-medium text-sm">Compras</span></div>
                    <i class="fas fa-chevron-right text-xs transition-transform"
                        :class="{'rotate-90 text-white': openMenu === 'compras'}"></i>
                </button>
                <div x-show="openMenu === 'compras'" x-collapse x-cloak
                    class="pl-4 pr-2 mt-1 space-y-1 bg-[#0b1120] py-2 rounded-lg">
                    <a href="{{ route('ordenes_compra.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('ordenes_compra*') ? 'sub-active' : '' }}">Órdenes
                        de Compra</a>
                    <a href="{{ route('facturas_compra.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('facturas_compra*') ? 'sub-active' : '' }}">Cuentas
                        por Pagar</a>
                    <a href="{{ route('notas_debito.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('notas_debito*') ? 'sub-active' : '' }}">Notas
                        de Débito/Crédito</a>
                    <a href="{{ route('proveedores.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('proveedores*') ? 'sub-active' : '' }}">Proveedores</a>
                </div>
            </div>

            <div>
                <button @click="openMenu === 'reparto' ? openMenu = null : openMenu = 'reparto'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-all"
                    :class="{'bg-slate-800 text-white': openMenu === 'reparto'}">
                    <div class="flex items-center gap-3"><i
                            class="fas fa-truck w-5 text-center text-orange-500"></i><span
                            class="font-medium text-sm">Reparto y Logística</span></div>
                    <i class="fas fa-chevron-right text-xs transition-transform"
                        :class="{'rotate-90 text-white': openMenu === 'reparto'}"></i>
                </button>
                <div x-show="openMenu === 'reparto'" x-collapse x-cloak
                    class="pl-4 pr-2 mt-1 space-y-1 bg-[#0b1120] py-2 rounded-lg">
                    <a href="{{ route('repartidor.dashboard') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('repartidor.dashboard') ? 'sub-active' : '' }}">Dashboard
                        Reparto</a>
                    <a href="{{ route('rutas_reparto.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('rutas_reparto*') ? 'sub-active' : '' }}">Gestión
                        de Rutas</a>
                    <a href="{{ route('repartidores.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('repartidores*') ? 'sub-active' : '' }}">Gestión
                        Repartidores</a>
                </div>
            </div>

            <div>
                <button @click="openMenu === 'ventas' ? openMenu = null : openMenu = 'ventas'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-all"
                    :class="{'bg-slate-800 text-white': openMenu === 'ventas'}">
                    <div class="flex items-center gap-3"><i
                            class="fas fa-file-invoice-dollar w-5 text-center text-green-500"></i><span
                            class="font-medium text-sm">Ventas</span></div>
                    <i class="fas fa-chevron-right text-xs transition-transform"
                        :class="{'rotate-90 text-white': openMenu === 'ventas'}"></i>
                </button>
                <div x-show="openMenu === 'ventas'" x-collapse x-cloak
                    class="pl-4 pr-2 mt-1 space-y-1 bg-[#0b1120] py-2 rounded-lg">
                    <a href="{{ route('facturas.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('facturas*') ? 'sub-active' : '' }}">Facturas
                        Venta</a>
                    <a href="{{ route('cotizaciones.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('cotizaciones*') ? 'sub-active' : '' }}">Cotizaciones</a>
                    <a href="{{ route('entregas.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('entregas*') ? 'sub-active' : '' }}">Órdenes
                        Entrega</a>
                    <a href="{{ route('clientes.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('clientes*') ? 'sub-active' : '' }}">Clientes</a>
                </div>
            </div>

            <div>
                <button @click="openMenu === 'inv' ? openMenu = null : openMenu = 'inv'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-all"
                    :class="{'bg-slate-800 text-white': openMenu === 'inv'}">
                    <div class="flex items-center gap-3"><i
                            class="fas fa-boxes w-5 text-center text-yellow-500"></i><span
                            class="font-medium text-sm">Inventario</span></div>
                    <i class="fas fa-chevron-right text-xs transition-transform"
                        :class="{'rotate-90 text-white': openMenu === 'inv'}"></i>
                </button>
                <div x-show="openMenu === 'inv'" x-collapse x-cloak
                    class="pl-4 pr-2 mt-1 space-y-1 bg-[#0b1120] py-2 rounded-lg">
                    <a href="{{ route('items.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('items*') ? 'sub-active' : '' }}">Catálogo</a>
                    <a href="{{ route('ajustes.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('ajustes*') ? 'sub-active' : '' }}">Ajustes
                        Stock</a>
                    <a href="{{ route('mermas.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('mermas*') ? 'sub-active' : '' }}">Control
                        Mermas</a>
                    <a href="{{ route('kardex.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('kardex*') ? 'sub-active' : '' }}">Kardex</a>
                    <a href="{{ route('bodegas.index') }}"
                        class="block py-2 px-2 text-sm hover:text-white transition-all {{ request()->routeIs('bodegas*') ? 'sub-active' : '' }}">Bodegas</a>
                    <a href="{{ route('reportes.rentabilidad') }}"
                        class="block py-2 px-2 text-sm text-green-400 hover:text-white transition-all {{ request()->routeIs('reportes.rentabilidad') ? 'sub-active' : '' }}">Rentabilidad</a>
                </div>
            </div>

            <div class="px-3 mt-6 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-600">Configuración
            </div>

            <a href="{{ route('listas_precios.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-all">
                <i class="fas fa-tags w-5 text-center"></i><span class="font-medium text-sm">Listas de Precios</span>
            </a>
            <a href="/configuracion"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 hover:text-white transition-all">
                <i class="fas fa-cogs w-5 text-center"></i><span class="font-medium text-sm">Ajustes Generales</span>
            </a>
        </nav>

        <div class="p-4 bg-[#0f172a] border-t border-slate-800 flex items-center gap-3">
            <div
                class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                {{ substr(Auth::user()->name ?? 'A', 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Administrador' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="cursor-pointer hover:bg-slate-800 p-2 rounded transition-colors group">
                    <i class="fas fa-sign-out-alt text-slate-500 group-hover:text-red-400 transition-colors"></i>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 bg-white shadow-sm border-b border-slate-200 flex items-center justify-between px-8 z-10">
            <h2 class="text-lg font-semibold text-slate-800">Panel de Control</h2>
            <div class="flex items-center gap-4">
                <button class="p-2 text-slate-400 hover:text-blue-600"><i class="fas fa-bell"></i></button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto bg-[#f8fafc] p-8">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 bg-white border-l-4 border-green-500 p-4 rounded shadow-sm flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-white border-l-4 border-red-500 p-4 rounded shadow-sm flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}', confirmButtonColor: '#ef4444' });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                html: '<ul class="text-left list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#f59e0b'
            });
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede revertir.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>