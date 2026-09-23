# Análisis del Sistema: QuickFood ERP
## Software de Gestión Empresarial (SGE) &bull; Seminario RAD &bull; Cotecnova 2026

---

## 1. Identificación de la Empresa

* **Nombre Comercial:** QuickFood
* **Razón Social:** QuickFood Delicias y Domicilios S.A.S.
* **Giro del Negocio:** Elaboración, preparación y comercialización de comidas rápidas artesanales y bebidas bajo el modelo operativo de **Dark Kitchen (Cocina Oculta)**, con canal centralizado de recepción de órdenes y servicio propio de reparto a domicilio.
* **Tamaño de la Empresa:** Pequeña Empresa (Pyme en crecimiento, con sede operativa central de cocina, personal de preparación, despacho y flota de domiciliarios).
* **Ubicación Operativa:** Colombia.

---

## 2. Problemática y Justificación del ERP

Antes de la implementación del ERP, QuickFood enfrentaba serios cuellos de botella operativos:
1. **Pérdida de pedidos y errores de comanda:** Las órdenes tomadas por mensajería o llamadas no contaban con trazabilidad centralizada, provocando retrasos en la cocina y entregas equivocadas.
2. **Descoordinación en despachos:** No existía asignación formal de pedidos a los domiciliarios, dificultando conocer qué repartidor llevaba cuál pedido ni los tiempos estimados de entrega.
3. **Falta de confirmación de direcciones:** Los clientes cambiaban de ubicación con frecuencia (trabajo, hogar, eventos), y registrar direcciones estáticas causaba pérdidas de tiempo en ruta.
4. **Falta de visibilidad de inventario:** Se vendían productos agotados durante horas pico al no haber descuento automático de existencias.
5. **Dificultad en la consolidación financiera:** La coexistencia de múltiples medios de pago (efectivo, Nequi, Daviplata, transferencias, datáfonos) sin un registro unificado entorpecía el cuadre diario de caja.

**Solución Implementada:**
El sistema **QuickFood ERP** centraliza en una plataforma web integral a los clientes, productos, categorías, pedidos con sus detalles, domiciliarios y métodos de pago, garantizando un flujo estructurado de estados desde la recepción hasta la entrega al cliente.

---

## 3. Procesos Clave del Negocio

### 3.1. Gestión de Catálogo y Menú
* Estructuración jerárquica de productos asociados a categorías (Hamburguesas Clásicas, Especiales, Perros, Salchipapas, Desgranados, Bebidas, etc.).
* Control de precios de venta al público en moneda local (COP) y descripción clara de ingredientes.
* Activación y desactivación de productos según temporada o disponibilidad.

### 3.2. Proceso de Ventas y Flujo Operativo del Pedido
El flujo neurálgico del negocio sigue una cadena estricta de 6 estados:

```
[ Cliente ] ──▶ [ 1. Recibido ] ──▶ [ 2. Preparando ] ──▶ [ 3. Listo ] ──▶ [ 4. En camino ] ──▶ [ 5. Entregado ]
                       │                                                                               ▲
                       └─────────────────────── [ 6. Cancelado ] ──────────────────────────────────────┘
```

1. **Recepción del Pedido (`Recibido`):**
   * Se selecciona o registra al cliente.
   * Se confirma la **dirección exacta de entrega** para esta orden en particular (evitando problemas de direcciones desactualizadas).
   * Se seleccionan los productos y cantidades. El sistema valida stock disponible, calcula subtotales (`cantidad × precio_unitario`) y total general.
   * Se define el método de pago acordado con el cliente.
   * El pedido ingresa a cola con estado inicial **Recibido**.

2. **Preparación en Cocina (`Preparando`):**
   * El personal de plancha y ensamble visualiza la comanda con observaciones y notas del cliente (ej: "sin cebolla", "salsas aparte").
   * El estado cambia a **Preparando**.

3. **Empaque y Verificación (`Listo`):**
   * El plato se empaca en bolsa térmica biodegradable para conservar temperatura.
   * Se marca como **Listo**, notificando al área de despacho para asignar domiciliario.

4. **Despacho y Transporte (`En camino`):**
   * Se selecciona el domiciliario responsable (con su vehículo y placa).
   * El pedido pasa a **En camino**. El domiciliario transporta los alimentos junto con el medio de recaudo requerido (ej: datáfono inalámbrico o cambio de efectivo).

5. **Finalización del Servicio (`Entregado`):**
   * El domiciliario confirma la entrega al cliente y el recaudo satisfactorio del dinero o comprobante digital.
   * El pedido queda en estado **Entregado** y se consolida en el dashboard de ventas.

6. **Incidencias y Cancelaciones (`Cancelado`):**
   * Si el cliente desiste oportunamente o se presenta fuerza mayor, el pedido pasa a **Cancelado** y el sistema repone automáticamente el stock descontado.

### 3.3. Control de Inventario
* Cada producto cuenta con un campo `stock` que refleja las unidades terminadas o porciones listas.
* Al registrar un pedido confirmado, el sistema ejecuta transacciones atómicas seguras en base de datos (`DB::transaction`) y descuenta inmediatamente la cantidad vendida.

### 3.4. Logística y Domiciliarios
* Registro y administración del personal de reparto con nombre, teléfono, documento de identidad (cédula) y especificación del vehículo utilizado (motos con placa o bicicletas eléctricas).
* Seguimiento de órdenes asignadas a cada domiciliario para balancear la carga de entregas.

### 3.5. Multi-Recaudo Financiero
* Centralización de cobros a través de múltiples opciones: Efectivo contra entrega, Nequi, Daviplata, Transferencia Bancolombia, Tarjeta Débito/Crédito con datáfono en domicilio, y billeteras digitales.

---

## 4. Proceso de Compras (Visión Futura y Complementaria)

Aunque el alcance prioritario actual de QuickFood se enfoca en la venta, preparación y entrega, la arquitectura del ERP está proyectada para incorporar el módulo de **Compras y Gestión de Proveedores**:
* **Proveedores Clave:** Panaderías industriales (pan brioche), carnicerías y frigoríficos (carne de res y pollo), distribuidoras de lácteos (queso costeño, mozzarella, cheddar), salseras y fábricas de empaques térmicos ecológicos.
* **Entidades Complementarias Futuras:** `proveedores`, `compras` y `compra_detalles`.
* **Beneficio Proyectado:** Transformar el inventario de productos terminados en un sistema de recetas (BOM / Bill of Materials) que descuente automáticamente materias primas (gramos de carne, unidades de pan, porciones de salsa) al despachar pedidos.

---

## 5. Caracterización de las Entidades del ERP

1. **`categorias`:** Clasificación organizada del menú para facilitar la toma rápida de comandas en momentos de alta demanda.
2. **`productos`:** Catálogo de alimentos y bebidas con precios, descripciones y control de disponibilidad (stock).
3. **`clientes`:** Directorio de usuarios recurrentes con datos de contacto y dirección predeterminada.
4. **`domiciliarios`:** Repartidores autorizados para el transporte de comida caliente a los diferentes barrios.
5. **`metodos_pago`:** Canales de pago habilitados para liquidar el servicio.
6. **`pedidos`:** Cabecera de la transacción comercial que vincula cliente, repartidor, método de pago, estado operativo, dirección específica y valor total.
7. **`pedido_detalles`:** Detalle de renglones que especifica qué productos y cuántas cantidades componen cada pedido, junto con su subtotal (`cantidad × precio_unitario`).
8. **`users`:** Usuarios administrativos y operadores del sistema con acceso seguro.

---

## 6. Conclusiones del Análisis

El diseño arquitectónico de QuickFood ERP responde con exactitud a la realidad operativa de una cocina moderna de domicilios:
* Garantiza la **integridad de datos** mediante claves foráneas y restricciones en cascada.
* Facilita el **control en tiempo real** de la cocina y los repartidores mediante estados transparentes.
* Cumple rigurosamente los estándares de desarrollo en **Laravel 11/12**, arquitectura **RESTful con Resource Controllers**, **eager loading** y componentes visuales en **Blade con Tailwind CSS**.
