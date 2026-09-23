<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Domiciliario;
use App\Models\MetodoPago;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuickFoodErpTest extends TestCase
{
    /**
     * Prueba A: Verifica el cumplimiento del requisito académico de
     * mínimo 10 registros por tabla con información coherente.
     */
    public function test_minimum_ten_records_per_table(): void
    {
        $this->assertGreaterThanOrEqual(10, Categoria::count(), 'La tabla categorias debe tener al menos 10 registros.');
        $this->assertGreaterThanOrEqual(10, Producto::count(), 'La tabla productos debe tener al menos 10 registros.');
        $this->assertGreaterThanOrEqual(10, Cliente::count(), 'La tabla clientes debe tener al menos 10 registros.');
        $this->assertGreaterThanOrEqual(10, Domiciliario::count(), 'La tabla domiciliarios debe tener al menos 10 registros.');
        $this->assertGreaterThanOrEqual(10, MetodoPago::count(), 'La tabla metodos_pago debe tener al menos 10 registros.');
        $this->assertGreaterThanOrEqual(10, Pedido::count(), 'La tabla pedidos debe tener al menos 10 registros.');
        $this->assertGreaterThanOrEqual(10, PedidoDetalle::count(), 'La tabla pedido_detalles debe tener al menos 10 registros.');
    }

    /**
     * Prueba B: Valida todas las relaciones Eloquent directas e inversas.
     */
    public function test_eloquent_relationships_work_bidirectionally(): void
    {
        // 1. Categoria hasMany Productos & Producto belongsTo Categoria
        $categoria = Categoria::with('productos')->first();
        $this->assertNotNull($categoria);
        $this->assertTrue($categoria->productos()->exists());
        $producto = $categoria->productos->first();
        $this->assertInstanceOf(Categoria::class, $producto->categoria);
        $this->assertEquals($categoria->id, $producto->categoria->id);

        // 2. Cliente hasMany Pedidos & Pedido belongsTo Cliente
        $cliente = Cliente::with('pedidos')->first();
        $this->assertNotNull($cliente);
        $this->assertTrue($cliente->pedidos()->exists());
        $pedido = $cliente->pedidos->first();
        $this->assertInstanceOf(Cliente::class, $pedido->cliente);
        $this->assertEquals($cliente->id, $pedido->cliente->id);

        // 3. MetodoPago hasMany Pedidos & Pedido belongsTo MetodoPago
        $metodoPago = MetodoPago::with('pedidos')->whereHas('pedidos')->first();
        $this->assertNotNull($metodoPago);
        $pedidoMetodo = $metodoPago->pedidos->first();
        $this->assertInstanceOf(MetodoPago::class, $pedidoMetodo->metodoPago);
        $this->assertEquals($metodoPago->id, $pedidoMetodo->metodoPago->id);

        // 4. Domiciliario hasMany Pedidos & Pedido belongsTo Domiciliario
        $domiciliario = Domiciliario::with('pedidos')->whereHas('pedidos')->first();
        $this->assertNotNull($domiciliario);
        $pedidoDom = $domiciliario->pedidos->first();
        $this->assertInstanceOf(Domiciliario::class, $pedidoDom->domiciliario);
        $this->assertEquals($domiciliario->id, $pedidoDom->domiciliario->id);

        // 5. Pedido hasMany PedidoDetalles & PedidoDetalle belongsTo Pedido
        $pedidoConDetalles = Pedido::with('detalles')->has('detalles')->first();
        $this->assertNotNull($pedidoConDetalles);
        $this->assertTrue($pedidoConDetalles->detalles->count() > 0);
        $detalle = $pedidoConDetalles->detalles->first();
        $this->assertInstanceOf(Pedido::class, $detalle->pedido);
        $this->assertEquals($pedidoConDetalles->id, $detalle->pedido->id);

        // 6. Producto hasMany PedidoDetalles & PedidoDetalle belongsTo Producto
        $this->assertInstanceOf(Producto::class, $detalle->producto);
        $this->assertTrue($detalle->producto->pedidoDetalles()->exists());
    }

    /**
     * Prueba C: Valida el funcionamiento de los Scopes en Modelos Eloquent.
     */
    public function test_eloquent_scopes_filter_correctly(): void
    {
        // Producto::activos()
        $activos = Producto::activos()->get();
        foreach ($activos as $prod) {
            $this->assertTrue((bool)$prod->estado);
        }

        // Producto::conStock()
        $conStock = Producto::conStock()->get();
        foreach ($conStock as $prod) {
            $this->assertGreaterThan(0, $prod->stock);
        }

        // Pedido::pendientes()
        $pendientes = Pedido::pendientes()->get();
        foreach ($pendientes as $ped) {
            $this->assertContains($ped->estado, ['Recibido', 'Preparando', 'Listo', 'En camino']);
            $this->assertNotEquals('Entregado', $ped->estado);
            $this->assertNotEquals('Cancelado', $ped->estado);
        }

        // Pedido::entregados()
        $entregados = Pedido::entregados()->get();
        foreach ($entregados as $ped) {
            $this->assertEquals('Entregado', $ped->estado);
        }
    }

    /**
     * Prueba D: Creación de pedido, cálculo backend de totales y decremento de stock.
     */
    public function test_order_creation_calculates_total_and_decrements_stock(): void
    {
        $cliente = Cliente::first();
        $metodoPago = MetodoPago::first();
        $domiciliario = Domiciliario::first();
        $producto = Producto::where('stock', '>=', 5)->first();

        $stockInicial = $producto->stock;
        $cantidadPedida = 2;
        $precioEsperado = $producto->precio;
        $totalEsperado = $precioEsperado * $cantidadPedida;

        $response = $this->post(route('pedidos.store'), [
            'cliente_id' => $cliente->id,
            'metodo_pago_id' => $metodoPago->id,
            'domiciliario_id' => $domiciliario->id,
            'direccion_entrega' => 'Calle 100 # 20-30 Casa 5, Barrio Central',
            'observaciones' => 'Test automatizado de pedido',
            'items' => [
                [
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidadPedida,
                ]
            ],
        ]);

        $response->assertRedirect();

        // Verificar que el pedido existe con estado inicial 'Recibido'
        $pedidoCreado = Pedido::where('direccion_entrega', 'Calle 100 # 20-30 Casa 5, Barrio Central')->first();
        $this->assertNotNull($pedidoCreado);
        $this->assertEquals('Recibido', $pedidoCreado->estado);
        $this->assertEquals((float)$totalEsperado, (float)$pedidoCreado->total);

        // Verificar que el detalle se creó con los valores exactos
        $detalleCreado = PedidoDetalle::where('pedido_id', $pedidoCreado->id)->first();
        $this->assertNotNull($detalleCreado);
        $this->assertEquals($producto->id, $detalleCreado->producto_id);
        $this->assertEquals($cantidadPedida, $detalleCreado->cantidad);
        $this->assertEquals((float)$totalEsperado, (float)$detalleCreado->subtotal);

        // Verificar decremento de stock en el producto
        $productoActualizado = $producto->fresh();
        $this->assertEquals($stockInicial - $cantidadPedida, $productoActualizado->stock);
    }

    /**
     * Prueba E: Transición de estados del pedido en el flujo operativo.
     */
    public function test_order_status_transitions(): void
    {
        $pedido = Pedido::where('estado', 'Recibido')->first();
        $this->assertNotNull($pedido);

        // 1. Recibido -> Preparando
        $this->patch(route('pedidos.cambiar-estado', $pedido), [
            'estado' => 'Preparando',
        ]);
        $this->assertEquals('Preparando', $pedido->fresh()->estado);

        // 2. Preparando -> Listo
        $this->patch(route('pedidos.cambiar-estado', $pedido), [
            'estado' => 'Listo',
        ]);
        $this->assertEquals('Listo', $pedido->fresh()->estado);

        // 3. Listo -> En camino
        $this->patch(route('pedidos.cambiar-estado', $pedido), [
            'estado' => 'En camino',
        ]);
        $this->assertEquals('En camino', $pedido->fresh()->estado);

        // 4. En camino -> Entregado
        $this->patch(route('pedidos.cambiar-estado', $pedido), [
            'estado' => 'Entregado',
        ]);
        $this->assertEquals('Entregado', $pedido->fresh()->estado);
    }

    /**
     * Prueba F: Carga del Dashboard con métricas operativas.
     */
    public function test_dashboard_and_views_response(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('QuickFood');
        $response->assertSee('Dashboard');

        $responsePedidos = $this->get(route('pedidos.index'));
        $responsePedidos->assertStatus(200);

        $responseProductos = $this->get(route('productos.index'));
        $responseProductos->assertStatus(200);

        $responseClientes = $this->get(route('clientes.index'));
        $responseClientes->assertStatus(200);
    }
}
