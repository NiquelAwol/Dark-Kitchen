<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Domiciliario;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del ERP con métricas operativas y pedidos recientes.
     */
    public function index()
    {
        $pedidosHoy = Pedido::hoy()->count();
        $pedidosPendientes = Pedido::pendientes()->count();
        $pedidosEntregados = Pedido::entregados()->count();
        $productosActivos = Producto::activos()->count();
        $ventasHoy = Pedido::hoy()->where('estado', '!=', 'Cancelado')->sum('total');

        $pedidosRecientes = Pedido::with(['cliente', 'domiciliario', 'metodoPago'])
            ->latest()
            ->take(8)
            ->get();

        $productosBajoStock = Producto::with('categoria')
            ->where('stock', '<=', 25)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $estadosConteo = [
            'Recibido' => Pedido::where('estado', 'Recibido')->count(),
            'Preparando' => Pedido::where('estado', 'Preparando')->count(),
            'Listo' => Pedido::where('estado', 'Listo')->count(),
            'En camino' => Pedido::where('estado', 'En camino')->count(),
            'Entregado' => Pedido::where('estado', 'Entregado')->count(),
            'Cancelado' => Pedido::where('estado', 'Cancelado')->count(),
        ];

        return view('dashboard', compact(
            'pedidosHoy',
            'pedidosPendientes',
            'pedidosEntregados',
            'productosActivos',
            'ventasHoy',
            'pedidosRecientes',
            'productosBajoStock',
            'estadosConteo'
        ));
    }
}
