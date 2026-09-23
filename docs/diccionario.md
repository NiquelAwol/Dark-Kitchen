# Diccionario de Datos: QuickFood ERP
## Software de Gestión Empresarial (SGE) &bull; Cotecnova 2026

Este documento describe la estructura técnica y semántica de las tablas que componen la base de datos relacional de **QuickFood ERP**, implementada en MySQL 8.4 bajo el motor InnoDB.

---

### Resumen de Tablas del Sistema

| Tabla | Tipo | Descripción | Registros Iniciales |
| :--- | :--- | :--- | :---: |
| `categorias` | Negocio | Clasificación jerárquica del menú de comidas y bebidas | 10 |
| `productos` | Negocio | Catálogo de platos, combos y bebidas con precio y stock | 20 |
| `clientes` | Negocio | Directorio de clientes con contacto y dirección de entrega | 10 |
| `domiciliarios` | Negocio | Flota de repartidores con identificación y vehículo | 10 |
| `metodos_pago` | Negocio | Medios de cobro y recaudos aceptados por la empresa | 10 |
| `pedidos` | Negocio (Cabecera) | Órdenes de compra, ciclo de estados y logística | 12 |
| `pedido_detalles` | Negocio (Detalle) | Desglose renglón por renglón de ítems por pedido | 24 |
| `users` | Sistema | Cuentas de usuario y acceso de administradores | 1 |

---

## 1. Tabla: `categorias`

Almacena las clasificaciones principales del catálogo de alimentos (ej: Hamburguesas Clásicas, Perros Calientes, Bebidas).

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción / Regla de Negocio |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Identificador único de la categoría. |
| `nombre` | `VARCHAR(100)` | NO | - | - | Nombre distintivo de la categoría (ej: "Hamburguesas Clásicas"). |
| `descripcion` | `TEXT` | SÍ | - | NULL | Explicación de los tipos de platos incluidos en esta categoría. |
| `estado` | `TINYINT(1)` | NO | - | 1 | Estado de activación: `1` = Activa, `0` = Inactiva. |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de creación del registro. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de la última modificación del registro. |

---

## 2. Tabla: `productos`

Catálogo de alimentos, platos preparados, bebidas y combos comercializados por QuickFood.

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción / Regla de Negocio |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Identificador único del producto. |
| `categoria_id` | `BIGINT UNSIGNED` | NO | FK | - | Clave foránea que referencia a `categorias(id)`. Restricción `RESTRICT` en eliminación y `CASCADE` en actualización. |
| `nombre` | `VARCHAR(150)` | NO | - | - | Nombre comercial del producto (ej: "Hamburguesa Monster Costeña"). |
| `descripcion` | `TEXT` | SÍ | - | NULL | Lista de ingredientes y detalles del plato para el personal de cocina. |
| `precio` | `DECIMAL(12,2)` | NO | - | - | Precio de venta al público en COP. Debe ser un valor estrictamente mayor a 0. |
| `stock` | `INT` | NO | - | 0 | Cantidad de porciones o unidades terminadas disponibles. Se descuenta en cada venta. |
| `estado` | `TINYINT(1)` | NO | - | 1 | Estado de disponibilidad: `1` = Activo (visible en comanda), `0` = Inactivo. |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de creación del registro. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de la última modificación del registro. |

---

## 3. Tabla: `clientes`

Directorio de clientes atendidos por QuickFood mediante servicio a domicilio.

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción / Regla de Negocio |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Identificador único del cliente. |
| `nombre` | `VARCHAR(150)` | NO | - | - | Nombre completo o razón de contacto del cliente. |
| `telefono` | `VARCHAR(30)` | NO | - | - | Número de teléfono o celular principal para confirmación de entrega. |
| `direccion` | `VARCHAR(255)` | NO | - | - | Dirección residencial habitual del cliente (barrio, calle, apartamento). |
| `email` | `VARCHAR(150)` | SÍ | - | NULL | Correo electrónico opcional para notificaciones o facturación. |
| `estado` | `TINYINT(1)` | NO | - | 1 | Estado del cliente: `1` = Activo, `0` = Inactivo. |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de creación del registro. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de la última modificación del registro. |

---

## 4. Tabla: `domiciliarios`

Flota de personal encargada de la recogida de comidas preparadas en cocina y su despacho en ruta.

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción / Regla de Negocio |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Identificador único del domiciliario. |
| `nombre` | `VARCHAR(150)` | NO | - | - | Nombre completo del repartidor. |
| `telefono` | `VARCHAR(30)` | NO | - | - | Teléfono celular de contacto en ruta. |
| `documento` | `VARCHAR(50)` | NO | - | - | Cédula de ciudadanía (CC) o documento de identidad oficial. |
| `vehiculo` | `VARCHAR(100)` | NO | - | - | Descripción del medio de transporte y placa (ej: "Moto Yamaha YBR 125 - Placa ASD-89G"). |
| `estado` | `TINYINT(1)` | NO | - | 1 | Disponibilidad operativa: `1` = Activo/En turno, `0` = Fuera de turno. |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de creación del registro. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de la última modificación del registro. |

---

## 5. Tabla: `metodos_pago`

Canales y plataformas a través de los cuales QuickFood recauda el valor de los pedidos.

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción / Regla de Negocio |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Identificador único del método de pago. |
| `nombre` | `VARCHAR(100)` | NO | - | - | Denominación del medio (ej: "Efectivo", "Nequi", "Daviplata", "Bancolombia"). |
| `estado` | `TINYINT(1)` | NO | - | 1 | Estado del canal: `1` = Habilitado, `0` = Inhabilitado. |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de creación del registro. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha y hora de la última modificación del registro. |

---

## 6. Tabla: `pedidos`

Tabla cabecera que representa cada orden de compra tomada en el ERP. Coordina al cliente, al domiciliario, el cobro y la transición de estados.

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción / Regla de Negocio |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Número de orden / ID de comanda. |
| `cliente_id` | `BIGINT UNSIGNED` | NO | FK | - | Clave foránea que referencia a `clientes(id)`. |
| `domiciliario_id` | `BIGINT UNSIGNED` | SÍ | FK | NULL | Clave foránea que referencia a `domiciliarios(id)`. Es nulo al ingresar el pedido y se asigna antes de despachar a ruta (`ON DELETE SET NULL`). |
| `metodo_pago_id` | `BIGINT UNSIGNED` | NO | FK | - | Clave foránea que referencia a `metodos_pago(id)`. |
| `direccion_entrega`| `VARCHAR(255)` | NO | - | - | Dirección confirmada específicamente para esta orden (garantiza trazabilidad histórica aunque el cliente se mude). |
| `estado` | `VARCHAR(30)` | NO | - | 'Recibido' | Estado operativo del pedido. Valores permitidos: `Recibido`, `Preparando`, `Listo`, `En camino`, `Entregado`, `Cancelado`. |
| `total` | `DECIMAL(12,2)` | NO | - | 0.00 | Monto total general calculado en backend como sumatoria de los subtotales de sus detalles. |
| `observaciones` | `TEXT` | SÍ | - | NULL | Instrucciones especiales para cocina (ej: "sin cebolla") o para entrega ("timbrar fuerte"). |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Momento exacto de recepción de la comanda. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Momento de la última actualización de estado o asignación. |

---

## 7. Tabla: `pedido_detalles`

Tabla que contiene los ítems específicos que integran cada comanda de pedido.

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción / Regla de Negocio |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Identificador único del renglón de detalle. |
| `pedido_id` | `BIGINT UNSIGNED` | NO | FK | - | Clave foránea que referencia a `pedidos(id)` con `ON DELETE CASCADE`. Si el pedido se elimina, sus renglones se eliminan automáticamente. |
| `producto_id` | `BIGINT UNSIGNED` | NO | FK | - | Clave foránea que referencia a `productos(id)` con `RESTRICT` en eliminación. |
| `cantidad` | `INT` | NO | - | 1 | Unidades solicitadas de este producto. Debe ser mayor o igual a 1. |
| `precio_unitario`| `DECIMAL(12,2)` | NO | - | - | Precio congelado del producto al momento de registrar la orden. |
| `subtotal` | `DECIMAL(12,2)` | NO | - | - | Valor monetario del renglón, calculado exactamente como `cantidad × precio_unitario`. |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha de creación del registro. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha de actualización del registro. |

---

## 8. Tabla: `users`

Tabla predeterminada de autenticación de Laravel para el acceso seguro al sistema ERP.

| Campo | Tipo | Nulo | Llave | Predeterminado | Descripción |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | `BIGINT UNSIGNED` | NO | PK | Auto-increment | Identificador del usuario. |
| `name` | `VARCHAR(255)` | NO | - | - | Nombre completo del usuario administrativo. |
| `email` | `VARCHAR(255)` | NO | UNIQUE | - | Correo corporativo para inicio de sesión. |
| `email_verified_at`| `TIMESTAMP` | SÍ | - | NULL | Marca temporal de verificación de correo. |
| `password` | `VARCHAR(255)` | NO | - | - | Contraseña encriptada mediante algoritmo seguro Bcrypt. |
| `remember_token` | `VARCHAR(100)` | SÍ | - | NULL | Token de persistencia de sesión ("Recordarme"). |
| `created_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha de registro del usuario. |
| `updated_at` | `TIMESTAMP` | SÍ | - | NULL | Fecha de última modificación. |

---

## 9. Reglas de Integridad Referencial y de Negocio

1. **Protección de Catálogo Histórico:** No se permite eliminar un producto (`RESTRICT`) ni un método de pago si existen ventas previas asociadas en `pedido_detalles` o `pedidos`.
2. **Eliminación en Cascada de Comandas:** Si un pedido es eliminado, la base de datos elimina automáticamente todos sus renglones en `pedido_detalles` (`CASCADE`), evitando registros huérfanos.
3. **Persistencia de Domiciliarios:** Si un domiciliario es dado de baja del personal, los pedidos históricos que entregó mantienen su registro y la referencia pasa a `NULL` (`SET NULL`), conservando la información contable de la entrega.
4. **Coherencia Matemática:** El backend calcula el total como `sum(cantidad * precio_unitario)` garantizando que el total almacenado en `pedidos` coincida estrictamente con el dinero recaudado.
