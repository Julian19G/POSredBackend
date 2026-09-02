# Sistema de Descuentos - Guía Completa

## 📋 Descripción

Sistema completo de descuentos que permite:
- Crear descuentos por **porcentaje** o **cantidad fija**
- Aplicarlos a **productos individuales** o **categorías completas**
- Visualizar automáticamente en el **frontend** con badges
- Calcular precios correctos en **carrito y checkout**
- Validar vigencia (fechas inicio/fin)

---

## 🔧 Backend - Crear y Gestionar Descuentos

### 1. Acceder al panel de descuentos (Laravel)

```bash
# Desde el navegador:
http://localhost:8000/descuentos
```

### 2. Crear un nuevo descuento

**Formulario:** `POST /descuentos`

**Campos:**
- `nombre` - Nombre del descuento (ej: "Verano 2025")
- `codigo` - Código único (ej: "VERANO20")
- `tipo` - "porcentaje" o "fijo"
- `valor` - Cantidad (20 para 20%, o 50 para $50 fijo)
- `fecha_inicio` - Cuándo comienza
- `fecha_fin` - Cuándo termina
- `activo` - Checkbox para activar
- `uso_maximo` - Máximo de usos totales (opcional)
- `uso_cliente_maximo` - Máximo por cliente (opcional)
- `productos[]` - IDs de productos (opcional, puede ser vacío)
- `categorias[]` - IDs de categorías (opcional)

**Ejemplo de descuento a categoría:**
```
nombre: "Ofertas de Primavera"
codigo: "PRIMAVERA25"
tipo: "porcentaje"
valor: 20
fecha_inicio: 2025-09-01
fecha_fin: 2025-09-30
activo: true
categorias: [1, 3, 5]  ← Aplica a categorías 1, 3 y 5
```

**Ejemplo de descuento a producto individual:**
```
nombre: "Promoción Rosas"
codigo: "ROSAS15"
tipo: "porcentaje"
valor: 15
productos: [42, 51]  ← Solo aplica a productos 42 y 51
```

### 3. Modelos y Relaciones (PHP)

**`Descuento.php`:**
```php
// Relaciones M:N
$descuento->productos();    // Productos con este descuento
$descuento->categorias();   // Categorías con este descuento

// Scope para activos y vigentes
Descuento::activos()
    ->where('fecha_inicio', '<=', now())
    ->where('fecha_fin', '>=', now())
    ->get();
```

**`Producto.php`:**
```php
// Obtener descuentos aplicables al producto
$producto->descuentosActivos();  // Directo + por categoría

// Calcular precio con descuento (automático)
$producto->precioConDescuento();  // Toma el mejor descuento

// Ejemplo:
$producto = Producto::find(1);
$precio_original = 100;
$precio_final = $producto->precioConDescuento($precio_original);  // 80 (si hay 20% de descuento)
```

---

## 🌐 Frontend - API Endpoints

### 1. Obtener descuentos por categoría

```javascript
// GET /api/descuentos/categoria/{categoriaId}
fetch('/api/descuentos/categoria/1')
    .then(res => res.json())
    .then(data => {
        console.log(data.productos);           // Productos con descuentos
        console.log(data.descuentos_globales); // Descuentos de la categoría
    });
```

**Respuesta:**
```json
{
    "categoria_id": 1,
    "descuentos_globales": [
        {
            "id": 5,
            "nombre": "Ofertas Primavera",
            "tipo": "porcentaje",
            "valor": 20
        }
    ],
    "productos": [
        {
            "id": 42,
            "nombre": "Rosas Rojas",
            "precio_original": 50000,
            "precio_final": 40000,
            "descuento": {
                "tipo": "porcentaje",
                "valor": 20,
                "porcentaje": 20,
                "nombre": "Ofertas Primavera"
            },
            "variantes": [...]
        }
    ]
}
```

### 2. Obtener descuentos para múltiples productos

```javascript
// POST /api/descuentos/productos
fetch('/api/descuentos/productos', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ 
        productos: [1, 5, 10]  // IDs de productos
    })
})
.then(res => res.json())
.then(data => console.log(data.productos));
```

**Respuesta:**
```json
{
    "productos": {
        "1": {
            "precio_original": 100000,
            "precio_final": 85000,
            "descuento": {
                "tipo": "porcentaje",
                "valor": 15,
                "porcentaje": 15,
                "nombre": "Descuento 15%"
            }
        },
        "5": {
            "precio_original": 50000,
            "precio_final": 50000,
            "descuento": null
        }
    }
}
```

---

## ⚛️ React - Componentes Listos

### 1. Mostrar productos con badges de descuento

**Archivo:** `src/components/DiscountComponents.jsx`

```jsx
import { CategoriaConDescuentos, ProductCard, DiscountBadge } from './DiscountComponents';

function MiCatalogo() {
    return (
        <CategoriaConDescuentos categoriaId={1} />
    );
}
```

**Características:**
- ✅ Badge en esquina superior derecha con % o $
- ✅ Precio original tachado
- ✅ Precio final en verde
- ✅ Monto ahorrado visible
- ✅ Descuentos globales de la categoría

### 2. Usar descuentos en carrito

**Archivo:** `src/services/discountService.js`

```jsx
import { useCarritoConDescuentos, aplicarDescuento } from './discountService';

function Carrito() {
    const { items, agregarItem, actualizarCantidad, totales, cargando } = useCarritoConDescuentos();

    return (
        <div>
            {items.map(item => (
                <div key={item.productId}>
                    <h3>{item.nombre}</h3>
                    
                    {/* Precio original si hay descuento */}
                    {item.descuento && (
                        <del>${item.precio}</del>
                    )}
                    
                    {/* Precio final */}
                    <strong>${item.precioFinal}</strong>
                    
                    <input 
                        type="number" 
                        value={item.cantidad}
                        onChange={(e) => actualizarCantidad(item.productId, parseInt(e.target.value))}
                    />
                </div>
            ))}
            
            {/* Totales */}
            <div>
                <p>Subtotal: ${totales.subtotal}</p>
                <p className="text-green-600">
                    -Descuento: ${totales.descuentoTotal}
                </p>
                <h3>Total: ${totales.total}</h3>
            </div>
        </div>
    );
}
```

### 3. Funciones auxiliares

```jsx
import { 
    aplicarDescuento,
    calcularAhorro,
    calcularPorcentajeDescuento,
    formatearPrecio 
} from './discountService';

// Aplicar descuento
const precioFinal = aplicarDescuento(50000, { tipo: 'porcentaje', valor: 20 });
// → 40000

// Calcular ahorro
const ahorro = calcularAhorro(50000, 40000);
// → 10000

// Calcular porcentaje
const porcentaje = calcularPorcentajeDescuento(50000, 40000);
// → 20

// Formatear precio
const texto = formatearPrecio(40000, 'es-CO');
// → "$40.000"
```

---

## 💾 Checkout - Asegurar precios correctos

El `POST /checkout/cotizar` ya incluye descuentos. **IMPORTANTE:** El frontend debe usar esta cotización antes de confirmar, no cálculos locales.

### En `checkoutService.js`:

```javascript
// Cotizar ANTES de crear venta
const cotizacion = await fetch('/api/checkout/cotizar', {
    method: 'POST',
    body: JSON.stringify({
        items: [
            { variante_id: 1, cantidad: 2 },
            { variante_id: 5, cantidad: 1 }
        ],
        envio: true,
        codigo_vendedor: 'VENDEDOR123'
    })
});

const resultado = await cotizacion.json();
console.log(resultado.items);     // Items con precios actualizados
console.log(resultado.total);     // Total FINAL con descuentos
```

---

## 📊 Diagrama de Flujo

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Admin crea descuento en /descuentos                     │
│    ├─ Asigna a productos específicos                       │
│    └─ O a categorías completas                             │
└────────────────────┬────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Backend calcula descuentos automáticamente              │
│    ├─ Producto.descuentosActivos() = directos + categoría  │
│    ├─ Descuentos vigentes (fecha inicio/fin)               │
│    └─ Toma el mejor si hay múltiples                       │
└────────────────────┬────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. Frontend muestra descuentos en catálogo                 │
│    ├─ API: /api/descuentos/categoria/{id}                  │
│    ├─ Badge con % en esquina de producto                   │
│    ├─ Precio original tachado                              │
│    └─ Precio final en verde                                │
└────────────────────┬────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Usuario agrega al carrito                               │
│    ├─ useCarritoConDescuentos() carga precios finales     │
│    ├─ Totales recalculados automáticamente                 │
│    └─ Muestra descuento total ahorrado                    │
└────────────────────┬────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. Checkout cotiza con descuentos                         │
│    ├─ POST /api/checkout/cotizar                           │
│    ├─ Valida precios FINALES en backend                    │
│    └─ Asegura coincidencia exacta                         │
└────────────────────┬────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. POST /checkout crea venta con descuentos                │
│    └─ Precio garantizado = que en cotización              │
└─────────────────────────────────────────────────────────────┘
```

---

## ✅ Checklist de Implementación

- [x] Tablas pivot descuentos_productos y descuentos_categorias
- [x] Relaciones M:N en modelos (Descuento, Producto, Categoria)
- [x] DescuentoController con CRUD completo
- [x] API endpoints para descuentos
- [x] Método `precioConDescuento()` en Producto
- [x] Componentes React (DiscountBadge, ProductCard, etc.)
- [x] Service de descuentos (calcular, formatear, hook useCarritoConDescuentos)
- [ ] Integrar componentes en página de categoría existente
- [ ] Integrar en carrito existente
- [ ] Integrar en checkout existente
- [ ] Testing en navegador (cada rol)

---

## 🧪 Testing Manual

### Backend
```bash
# Crear descuento de prueba
php artisan tinker
>>> $desc = Descuento::create([
...     'nombre' => 'Prueba 20%',
...     'codigo' => 'PRUEBA20',
...     'tipo' => 'porcentaje',
...     'valor' => 20,
...     'fecha_inicio' => now(),
...     'fecha_fin' => now()->addDays(7),
...     'activo' => true
... ]);

# Asignar a producto
>>> $desc->productos()->attach(1);

# Verificar
>>> $prod = Producto::find(1);
>>> $prod->descuentosActivos();
>>> $prod->precioConDescuento();
```

### Frontend (React)
```jsx
// Prueba rápida en console
fetch('/api/descuentos/categoria/1')
    .then(r => r.json())
    .then(d => console.log(d))
```

---

## 🐛 Troubleshooting

**"Descuento no aparece en frontend"**
- ✓ Verificar fecha_inicio <= hoy AND fecha_fin >= hoy
- ✓ Verificar `activo = true`
- ✓ Verificar producto_id correcto en pivot
- ✓ Clearar caché: `php artisan cache:clear`

**"Precios incorrectos en checkout"**
- ✓ Siempre usar `POST /checkout/cotizar` primero
- ✓ No confiar en cálculos frontend locales
- ✓ Verificar `Producto.precioConDescuento()` en backend

**"Múltiples descuentos no funcionan"**
- ✓ Sistema toma el MEJOR (mayor ahorro)
- ✓ Puede ser directo O por categoría
- ✓ No suma múltiples descuentos

