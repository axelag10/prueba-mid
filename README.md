
# 📦 Prueba técnica Fullstack Mid-Level - Administración de inventario

API REST desarrollada en Laravel 12 para la gestión de productos, categorías, provedores, variantes e inventario, con control de roles, permisos y trazabilidad completa de movimientos de stock.

## 🤖 Tecnologías

- PHP 8.3

- Laravel 12

- Laravel Sanctum (auth API)

- Spatie Laravel Permission

- MySQL (MariaDB)

- API REST


## 🔐 Autenticación y Roles

La API utiliza tokens (Bearer) mediante Laravel Sanctum.

Roles soportados

- Admin → acceso total
- Manager → gestión operativa (productos, stock)
- Viewer → solo lectura

El acceso se controla mediante:
- Middleware (auth:sanctum, role)
- Policies (reglas de negocio)


## 🧩 Módulos principales

### 🧑‍🧒‍🧒 Usuarios

- CRUD de usuarios
- Asignación de roles

### 💎 Categorías

- Categorías padre e hijas
- CRUD completo
- Soft delete y restore
- Búsqueda por nombre o descripción
- Paginación

### 🚛 Provedores

- CRUD completo
- Soft delete y restore
- Búsqueda por nombre
- Paginación

### 🎨 Variantes

- CRUD completo
- Variantes (ej. Color, Talla)
- Tipos de variante (ej. Rojo, Azul / M, L)
- Solo Admin puede gestionar tipos de variante
- Soft delete y restore
- Búsqueda por nombre
- Paginación

### 🛍️ Productos

- CRUD completo
- Productos simples y con variantes
- Asociación con categorías
- Asociación con proveedor
- Precios y costos por tipo de variante
- Validación de tipo (simple / variant)
- Soft delete y restore

### 🗃️ Inventario (stock)

- Control por producto + tipo de variante
- Validación de stock negativo
- Actualización incremental (+ / -) 
- Preparado para múltiples bodegas

### ↕️ Movimientos de Stock

- Historial completo por stock
- Tipos:
    - in (entrada)
    - out (salida)
- Registro de:
    - Cantidad
    - Motivo (reason)
    - Usuario responsable


Ejemplo de venta:

```json
{
  "quantity": -5,
  "reason": "Ajuste por venta"
}
```

### Diseño preparado para escalar

- Transferencia entre bodegas y ventas
  - La lógica no está implementada aún, pero la arquitectura lo permite sin cambios estructurales.


## 🔬API (Endpoints principales)

Todos los endpoint requieren autentificación vía Bearer Token, salvo /login

### Autenticación
#### Login

```http
  POST /api/login
```
#### Body(JSON)

```json
{
  "email": "admin@test.com",
  "password": "admin"
}
```

### Categorías
#### Obtener todas las categorias

```http
  GET /api/categories
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `search`  | `string` | **Opcional**. Buscar por nombre   |


#### Obtener categoría por {id}

```http
  GET /api/categories/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `number` | **Requerido**. ID de categoría    |

#### Crear categoria

```http
  POST /api/categories
```
#### Body (JSON)
```json
{
  "name": "Ropa",
  "description": "Categoría principal",
  "parent_id": null
}
```

#### Actualizar categoría

```http
  PUT /api/categories/{id}
```
#### Eliminar categoría (Soft delete)

```http
  DELETE /api/categories/{id}
```
#### Restaurar categoría

```http
  PATCH /api/categories/{id}/restore
```

### Provedores
#### Obtener todos los provedores

```http
  GET /api/providers
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `search`  | `string` | **Opcional**. Buscar por nombre   |


#### Obtener provedor por {id}

```http
  GET /api/providers/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `number` | **Requerido**. ID de provedor    |

#### Crear provedor

```http
  POST /api/providers
```
#### Body (JSON)
```json
{
  "name": "Proveedor Demo",
  "email": "proveedor@test.com",
  "phone": "5551234567",
  "address": "Street 100"
}
```

#### Actualizar provedor

```http
  PUT /api/providers/{id}
```
#### Eliminar provedor (Soft delete)

```http
  DELETE /api/providers/{id}
```
#### Restaurar provedor

```http
  PATCH /api/providers/{id}/restore
```

### Variantes
#### Obtener todas las variantes

```http
  GET /api/variants
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `search`  | `string` | **Opcional**. Buscar por nombre   |


#### Obtener variante por {id}

```http
  GET /api/variants/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `number` | **Requerido**. ID de la variante  |

#### Crear variante

```http
  POST /api/variants
```
#### Body (JSON)
```json
{
  "name": "Color",
}
```

#### Actualizar variante

```http
  PUT /api/variants/{id}
```
#### Eliminar variante (Soft delete)

```http
  DELETE /api/variants/{id}
```
#### Restaurar variante

```http
  PATCH /api/variants/{id}/restore
```

### Tipos de variante
#### Crear tipo de variante

```http
  POST /api/variant-types
```
#### Body (JSON)
```json
{
  "variant_id": 1,
  "name": "Rojo"
}
```

#### Actualizar tipo de variante

```http
  PUT /api/variant-types/{id}
```
#### Eliminar tipo de variante (Soft delete)

```http
  DELETE /api/variant-types/{id}
```
#### Restaurar tipo de variante

```http
  PATCH /api/variant-types/{id}/restore
```

### Productos
#### Obtener todos los productos

```http
  GET /api/products
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `search`  | `string` | **Opcional**. Busqueda por nombre o SKU|


#### Obtener producto por {id}

```http
  GET /api/products/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `number` | **Requerido**. ID del producto  |

#### Crear producto

```http
  POST /api/products
```
#### Body (JSON) Producto simple
```json
{
  "sku": "PROD-001",
  "name": "Playera básica",
  "description": "Playera de algodón",
  "type": "simple",
  "price": 200,
  "cost": 120,
  "provider_id": 1,
  "categories": [2]
}
```
#### Body (JSON) Producto con variantes
```json
{
  "sku": "PROD-002",
  "name": "Playera con colores",
  "description": "Playera disponible en varios colores",
  "type": "variant",
  "provider_id": 1,
  "categories": [2],
  "variant_types": [
    {
      "id": 1,
      "price": 200,
      "cost": 120
    },
    {
      "id": 2,
      "price": 220,
      "cost": 130
    }
  ]
}
```

*Notas importantes:

- `variant_types.id` ya deben existir
- El producto solo se crea, los tipos se asignan
- Los precios y costos se guardan en la tabla pivote

#### Actualizar producto

```http
  PUT /api/products/{id}
```
#### Eliminar producto (Soft delete)

```http
  DELETE /api/products/{id}
```
#### Restaurar producto

```http
  PATCH /api/products/{id}/restore
```

### Stock
#### Crear stock

```http
  POST /api/stocks
```
#### Body (JSON)
```json
{
  "product_id": 1,
  "variant_type_id": 2,
  "quantity": 10
}
```
#### Actualizar stock quantity

```http
  PUT /api/stocks/{id}
```
#### Body (JSON)
```json
{
  "quantity": -3,
  "reason": "Venta mostrador"
}
```
#### Obtener movientos de stock

```http
  GET /api/stocks/{id}/movements
```

## 🧪 Pruebas

- Pruebas manuales realizadas con Postman
- Validación de roles y stock

## 💡 Deciciones técnicas

**El stock se controla mediante movimientos**
  - El inventario o se maneja solo con actualizaciones de cantidad. Si no que cada cambio de stock genera un registro en `stock_movements` permitiendo:
    - Auditoria completa 
    - Historial de ajustes
    - Trazabilidad por usuario 

**Roles y control de acceso**

Se usaron:
  - Middleware para acceso general
  - Policies para reglas de negocio
Esto permite:
  - Escalar reglas sin modificar controladores
  - Claridad en permisos por rol

**Ventas desacopladas del inventario**

No se implementó un módulo de ventas completo.

El descuento de stock se realiza mediante:
  - Cantidades negativas
  - Movimientos `out`
Este diseño permite integrar ventas futuras.

**Escalabilidad futura (Bodegas)**

El diseño contempla múltiples bodegas:
  - Stock por ubicación
  - Transferencias como movimientos `in` y `out`
  - Referencias compartidas

**Enfoque en backend**

Se priorizó:
  - Diseño limpio
  - Reglas claras
  - Trazabilidad
## 🛠️ Instalación

Clonar el repositorio

```bash
git clone https://github.com/axelag10/prueba-mid.git
cd inventory-api
```

Instalar dependencias

```bash
composer install
```

Configuracion de ambiente

```bash
cp .env.example .env
```
Configurar tu coneccion de base de datos en el `.env`

```bash
DB_DATABASE=inventory
DB_USERNAME=root
DB_PASSWORD=
```
*Puedes usar cualquier nombre para la base de datos.

Generar la clave de aplicación

```bash
php artisan key:generate
```
*Ajustar `APP_URL` si estas corriendo el proyecto  un puerto especifico

Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```
*Asegurarse que la base de datos exista antes de correr la migración.

Correr el servidor web

```bash
php artisan serve
```
*O usar el servidor de tu preferencia

## Authors

- [@axelag10](https://www.github.com/axelag10)

