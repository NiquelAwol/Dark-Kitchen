@extends('layouts.app')

@section('title', 'Categorías de Alimentos')
@section('page_title', 'Categorías de Menú')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('categorias.index') }}" method="GET" class="flex items-center space-x-2">
            <div class="relative">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar categoría..." class="w-64 pl-9 pr-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                Buscar
            </button>
            @if(request('buscar'))
                <a href="{{ route('categorias.index') }}" class="text-xs text-slate-500 hover:text-slate-800">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('categorias.create') }}" class="inline-flex items-center space-x-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition">
            <i class="fa-solid fa-plus"></i>
            <span>Nueva Categoría</span>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4"># ID</th>
                        <th class="py-3.5 px-4">Nombre de Categoría</th>
                        <th class="py-3.5 px-4">Descripción</th>
                        <th class="py-3.5 px-4 text-center">Productos</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                        <th class="py-3.5 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categorias as $cat)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-500">#{{ $cat->id }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $cat->nombre }}
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500 max-w-md truncate">
                                {{ $cat->descripcion ?? 'Sin descripción' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="bg-purple-50 text-purple-700 font-bold px-2 py-0.5 rounded-full text-xs border border-purple-200">
                                    {{ $cat->productos_count }} platos
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($cat->estado)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Inactiva
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('categorias.edit', $cat) }}" class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded transition" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('categorias.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que desea eliminar la categoría {{ $cat->nombre }}?');">
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
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                No se encontraron categorías.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categorias->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $categorias->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
