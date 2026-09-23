@extends('layouts.app')

@section('title', 'Dashboard Operativo')
@section('page_title', 'Panel de Control Operativo - QuickFood')

@section('content')
<div class="space-y-6">

    <!-- Tarjetas de Métricas Principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Pedidos Hoy -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pedidos Hoy</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $pedidosHoy }}</h3>
                <span class="text-[11px] text-slate-400">Total registrados hoy</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">En Operación</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $pedidosPendientes }}</h3>
                <span class="text-[11px] text-slate-400">En cocina o en ruta</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-fire-burner"></i>
            </div>
        </div>

        <!-- Pedidos Entregados -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Entregados</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $pedidosEntregados }}</h3>
                <span class="text-[11px] text-slate-400">Servicios finalizados</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Productos Activos -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Catálogo Activo</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $productosActivos }}</h3>
                <span class="text-[11px] text-slate-400">Platos disponibles</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-burger"></i>
            </div>
        </div>

        <!-- Ventas Hoy -->
        <div class="bg-gradient-to-br from-orange-600 to-amber-500 text-white p-5 rounded-xl shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-orange-100">Ventas Hoy</p>
                <h3 class="text-2xl font-black mt-1">${{ number_format($ventasHoy, 0, ',', '.') }}</h3>
                <span class="text-[11px] text-orange-100">Ingresos del día</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl">
                <i class="fa-solid fa-cash-register"></i>
            </div>
        </div>
    </div>

    <!-- Flujo del Negocio y Estados -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Flujo Operativo de Pedidos & Domicilios</h2>
                <p class="text-xs text-slate-500">Ciclo continuo de atención: Cliente ➔ Pedido ➔ Preparación ➔ Listo ➔ Domicilio ➔ Entrega</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-orange-100 text-orange-800 rounded-md">Dark Kitchen Workflow</span>
        </div>

        <!-- Estados en cadena -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
            <!-- Recibido -->
            <a href="{{ route('pedidos.index', ['estado' => 'Recibido']) }}" class="p-3.5 rounded-lg border border-blue-200 bg-blue-50/50 hover:bg-blue-100/60 transition group text-center">
                <div class="text-xs font-bold uppercase tracking-wider text-blue-700 flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-bell text-xs"></i>
                    <span>1. Recibido</span>
                </div>
                <div class="text-xl font-black text-blue-900 mt-1">{{ $estadosConteo['Recibido'] }}</div>
                <div class="text-[11px] text-blue-600 mt-0.5">En cola cocina</div>
            </a>

            <!-- Preparando -->
            <a href="{{ route('pedidos.index', ['estado' => 'Preparando']) }}" class="p-3.5 rounded-lg border border-amber-200 bg-amber-50/50 hover:bg-amber-100/60 transition group text-center">
                <div class="text-xs font-bold uppercase tracking-wider text-amber-700 flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-fire text-xs"></i>
                    <span>2. Preparando</span>
                </div>
                <div class="text-xl font-black text-amber-900 mt-1">{{ $estadosConteo['Preparando'] }}</div>
                <div class="text-[11px] text-amber-600 mt-0.5">En plancha / freidora</div>
            </a>

            <!-- Listo -->
            <a href="{{ route('pedidos.index', ['estado' => 'Listo']) }}" class="p-3.5 rounded-lg border border-indigo-200 bg-indigo-50/50 hover:bg-indigo-100/60 transition group text-center">
                <div class="text-xs font-bold uppercase tracking-wider text-indigo-700 flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-box-check text-xs"></i>
                    <span>3. Listo</span>
                </div>
                <div class="text-xl font-black text-indigo-900 mt-1">{{ $estadosConteo['Listo'] }}</div>
                <div class="text-[11px] text-indigo-600 mt-0.5">Empacado para entrega</div>
            </a>

            <!-- En camino -->
            <a href="{{ route('pedidos.index', ['estado' => 'En camino']) }}" class="p-3.5 rounded-lg border border-purple-200 bg-purple-50/50 hover:bg-purple-100/60 transition group text-center">
                <div class="text-xs font-bold uppercase tracking-wider text-purple-700 flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-motorcycle text-xs"></i>
                    <span>4. En camino</span>
                </div>
                <div class="text-xl font-black text-purple-900 mt-1">{{ $estadosConteo['En camino'] }}</div>
                <div class="text-[11px] text-purple-600 mt-0.5">Domiciliario en ruta</div>
            </a>

            <!-- Entregado -->
            <a href="{{ route('pedidos.index', ['estado' => 'Entregado']) }}" class="p-3.5 rounded-lg border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-100/60 transition group text-center">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-700 flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-circle-check text-xs"></i>
                    <span>5. Entregado</span>
                </div>
                <div class="text-xl font-black text-emerald-900 mt-1">{{ $estadosConteo['Entregado'] }}</div>
                <div class="text-[11px] text-emerald-600 mt-0.5">Cobrado y cerrado</div>
            </a>

            <!-- Cancelado -->
            <a href="{{ route('pedidos.index', ['estado' => 'Cancelado']) }}" class="p-3.5 rounded-lg border border-rose-200 bg-rose-50/50 hover:bg-rose-100/60 transition group text-center">
                <div class="text-xs font-bold uppercase tracking-wider text-rose-700 flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-ban text-xs"></i>
                    <span>Cancelado</span>
                </div>
                <div class="text-xl font-black text-rose-900 mt-1">{{ $estadosConteo['Cancelado'] }}</div>
                <div class="text-[11px] text-rose-600 mt-0.5">Incidencias</div>
            </a>
        </div>
    </div>

    <!-- Sección Inferior: Pedidos Recientes & Control de Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Pedidos Recientes (2 columnas) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Últimos Pedidos Registrados</h2>
                    <p class="text-xs text-slate-500">Monitor en vivo con carga optimizada Eager Loading</p>
                </div>
                <a href="{{ route('pedidos.index') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700 flex items-center space-x-1">
                    <span>Ver todos</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4"># Pedido</th>
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4">Domiciliario</th>
                            <th class="py-3 px-4 text-right">Total</th>
                            <th class="py-3 px-4 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pedidosRecientes as $pedido)
                            <tr class="hover:bg-slate-50/75 transition">
                                <td class="py-3 px-4 font-bold text-slate-800">
                                    #{{ $pedido->id }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-800">{{ $pedido->cliente->nombre }}</div>
                                    <div class="text-xs text-slate-400">{{ $pedido->cliente->telefono }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $badgeClasses = match($pedido->estado) {
                                            'Recibido' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Preparando' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Listo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                            'En camino' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'Entregado' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            default => 'bg-rose-100 text-rose-800 border-rose-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                        {{ $pedido->estado }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-xs text-slate-600">
                                    @if($pedido->domiciliario)
                                        <span class="font-medium text-slate-700"><i class="fa-solid fa-motorcycle text-slate-400 mr-1"></i>{{ $pedido->domiciliario->nombre }}</span>
                                    @else
                                        <span class="text-slate-400 italic">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-slate-800">
                                    ${{ number_format($pedido->total, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('pedidos.show', $pedido) }}" class="inline-flex items-center space-x-1 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1 rounded transition">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                        <span>Comanda</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    No hay pedidos recientes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alertas de Stock y Enlaces Rápidos (1 columna) -->
        <div class="space-y-6">
            <!-- Productos con Bajo Stock -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-boxes-stacked text-amber-500"></i>
                        <h3 class="text-sm font-bold text-slate-800">Control de Inventario</h3>
                    </div>
                    <span class="text-[11px] bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-semibold">Stock Crítico</span>
                </div>

                <div class="space-y-3">
                    @forelse($productosBajoStock as $prod)
                        <div class="flex items-center justify-between text-xs p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $prod->nombre }}</p>
                                <p class="text-[11px] text-slate-400">{{ $prod->categoria->nombre }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded-full font-bold {{ $prod->stock <= 15 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $prod->stock }} disp.
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Inventario en niveles óptimos.</p>
                    @endforelse
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="bg-slate-900 text-white rounded-xl shadow-sm p-5 space-y-3">
                <h3 class="text-sm font-bold text-orange-400 uppercase tracking-wider text-xs">Acciones Rápidas</h3>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="{{ route('pedidos.create') }}" class="p-2.5 bg-slate-800 hover:bg-orange-600 rounded-lg transition font-medium flex items-center space-x-2">
                        <i class="fa-solid fa-receipt text-orange-400"></i>
                        <span>Nuevo Pedido</span>
                    </a>
                    <a href="{{ route('productos.create') }}" class="p-2.5 bg-slate-800 hover:bg-orange-600 rounded-lg transition font-medium flex items-center space-x-2">
                        <i class="fa-solid fa-utensils text-orange-400"></i>
                        <span>Nuevo Producto</span>
                    </a>
                    <a href="{{ route('clientes.create') }}" class="p-2.5 bg-slate-800 hover:bg-orange-600 rounded-lg transition font-medium flex items-center space-x-2">
                        <i class="fa-solid fa-user-plus text-orange-400"></i>
                        <span>Nuevo Cliente</span>
                    </a>
                    <a href="{{ route('domiciliarios.create') }}" class="p-2.5 bg-slate-800 hover:bg-orange-600 rounded-lg transition font-medium flex items-center space-x-2">
                        <i class="fa-solid fa-motorcycle text-orange-400"></i>
                        <span>Domiciliario</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
