@extends('layouts.app')

@section('title', 'Catálogo de Productos')
@section('page_title', 'Catálogo de Productos & Menú')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Filtros y Búsqueda -->
        <form action="{{ route('productos.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar producto..." class="w-60 pl-9 pr-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>

            <select name="categoria_id" class="text-xs rounded-lg border border-slate-300 py-2 px-3 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                Filtrar
            </button>
            @if(request('buscar') || request('categoria_id'))
                <a href="{{ route('productos.index') }}" class="text-xs text-slate-500 hover:text-slate-800">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('productos.create') }}" class="inline-flex items-center space-x-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo Producto</span>
        </a>
    </div>

    <!-- Tabla de Productos -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4"># ID</th>
                        <th class="py-3.5 px-4">Producto & Descripción</th>
                        <th class="py-3.5 px-4">Categoría</th>
                        <th class="py-3.5 px-4 text-right">Precio</th>
                        <th class="py-3.5 px-4 text-center">Stock</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                        <th class="py-3.5 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($productos as $producto)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-500">
                                #{{ $producto->id }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $producto->nombre }}</div>
                                <div class="text-xs text-slate-400 max-w-md truncate">{{ $producto->descripcion }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-medium text-slate-600">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-700">
                                    {{ $producto->categoria->nombre }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-slate-800">
                                ${{ number_format($producto->precio, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($producto->stock > 20)
                                    <span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                        {{ $producto->stock }}
                                    </span>
                                @elseif($producto->stock > 0)
                                    <span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                        {{ $producto->stock }}
                                    </span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                        Agotado (0)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($producto->estado)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('productos.edit', $producto) }}" class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded transition" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que desea eliminar el producto {{ $producto->nombre }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-slate-100 rounded transition" title="Eliminar">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                No se encontraron productos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($productos->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
