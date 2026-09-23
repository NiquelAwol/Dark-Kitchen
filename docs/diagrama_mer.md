# Diagrama Entidad - Relación (MER): QuickFood ERP
## Software de Gestión Empresarial (SGE) &bull; Cotecnova 2026

A continuación se presenta el modelo relacional del sistema **QuickFood ERP**, implementado mediante migraciones en Laravel con base de datos MySQL 8.4.

![Diagrama MER de QuickFood ERP](diagrama_mer.png)

---

### Especificación en Notación Mermaid

```mermaid
erDiagram
    CATEGORIAS ||--o{ PRODUCTOS : "clasifica (1:N)"
    CLIENTES ||--o{ PEDIDOS : "realiza (1:N)"
    DOMICILIARIOS ||--o{ PEDIDOS : "transporta (1:N)"
    METODOS_PAGO ||--o{ PEDIDOS : "liquida (1:N)"
    PEDIDOS ||--|{ PEDIDO_DETALLES : "contiene (1:N)"
    PRODUCTOS ||--o{ PEDIDO_DETALLES : "incluye (1:N)"

    CATEGORIAS {
        bigint id PK
        varchar nombre
        text descripcion
        boolean estado
        datetime created_at
        datetime updated_at
    }

    PRODUCTOS {
        bigint id PK
        bigint categoria_id FK
        varchar nombre
        text descripcion
        decimal precio
        int stock
        boolean estado
        datetime created_at
        datetime updated_at
    }

    CLIENTES {
        bigint id PK
        varchar nombre
        varchar telefono
        varchar direccion
        varchar email
        boolean estado
        datetime created_at
        datetime updated_at
    }

    DOMICILIARIOS {
        bigint id PK
        varchar nombre
        varchar telefono
        varchar documento
        varchar vehiculo
        boolean estado
        datetime created_at
        datetime updated_at
    }

    METODOS_PAGO {
        bigint id PK
        varchar nombre
        boolean estado
        datetime created_at
        datetime updated_at
    }

    PEDIDOS {
        bigint id PK
        bigint cliente_id FK
        bigint domiciliario_id FK
        bigint metodo_pago_id FK
        varchar direccion_entrega
        varchar estado
        decimal total
        text observaciones
        datetime created_at
        datetime updated_at
    }

    PEDIDO_DETALLES {
        bigint id PK
        bigint pedido_id FK
        bigint producto_id FK
        int cantidad
        decimal precio_unitario
        decimal subtotal
        datetime created_at
        datetime updated_at
    }

    USERS {
        bigint id PK
        varchar name
        varchar email
        varchar password
        datetime created_at
        datetime updated_at
    }
```

---

### Cardinalidad y Relaciones Eloquent Implementadas

1. **`Categoria` 1 : N `Producto`**
   * Eloquent: `$this->hasMany(Producto::class)` / Inversa: `$this->belongsTo(Categoria::class)`.
   * Regla DB: Clave foránea `categoria_id` en `productos` apuntando a `categorias(id)`.
2. **`Cliente` 1 : N `Pedido`**
   * Eloquent: `$this->hasMany(Pedido::class)` / Inversa: `$this->belongsTo(Cliente::class)`.
   * Regla DB: Clave foránea `cliente_id` en `pedidos` apuntando a `clientes(id)`.
3. **`Domiciliario` 1 : N `Pedido`**
   * Eloquent: `$this->hasMany(Pedido::class)` / Inversa: `$this->belongsTo(Domiciliario::class)`.
   * Regla DB: Clave foránea `domiciliario_id` (nullable) en `pedidos` con `ON DELETE SET NULL`.
4. **`MetodoPago` 1 : N `Pedido`**
   * Eloquent: `$this->hasMany(Pedido::class)` / Inversa: `$this->belongsTo(MetodoPago::class)`.
   * Regla DB: Clave foránea `metodo_pago_id` en `pedidos` con `RESTRICT` en eliminación.
5. **`Pedido` 1 : N `PedidoDetalle`**
   * Eloquent: `$this->hasMany(PedidoDetalle::class)` / Inversa: `$this->belongsTo(Pedido::class)`.
   * Regla DB: Clave foránea `pedido_id` en `pedido_detalles` con `ON DELETE CASCADE`.
6. **`Producto` 1 : N `PedidoDetalle`**
   * Eloquent: `$this->hasMany(PedidoDetalle::class)` / Inversa: `$this->belongsTo(Producto::class)`.
   * Regla DB: Clave foránea `producto_id` en `pedido_detalles` con `RESTRICT` en eliminación.
