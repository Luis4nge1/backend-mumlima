# API Documentation - Sistema de Fiscalización

## Servidor: http://127.0.0.1:8000

## Autenticación

### POST /api/login
Iniciar sesión y obtener token de acceso.

**Body:**
```json
{
  "email": "juan.perez@fiscalizacion.com",
  "password": "Password123"
}
```

**Respuesta exitosa:**
```json
{
  "message": "Inicio de sesión exitoso.",
  "user": {...},
  "token": "1|abc123...",
  "token_type": "Bearer"
}
```

### POST /api/logout
Cerrar sesión (requiere autenticación).

### GET /api/me
Obtener información del usuario autenticado.

### POST /api/refresh
Renovar token de acceso.

## Endpoints Disponibles (Requieren Autenticación)

### Distribuciones

#### GET /api/distribuciones
Obtener lista de distribuciones con paginación y filtros.

**Parámetros de consulta:**
- `search` (string): Buscar por nombre o descripción
- `with_usuarios_count` (boolean): Incluir conteo de usuarios
- `with_usuarios` (boolean): Incluir usuarios relacionados
- `per_page` (int): Elementos por página (default: 15)

**Ejemplo de respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Distribución Norte",
      "description": "Distribución que cubre la zona norte",
      "usuarios_count": 5,
      "created_at": "2025-01-08T10:00:00.000000Z",
      "updated_at": "2025-01-08T10:00:00.000000Z"
    }
  ],
  "links": {...},
  "meta": {...}
}
```

#### POST /api/distribuciones
Crear nueva distribución.

**Body:**
```json
{
  "name": "Nueva Distribución",
  "description": "Descripción opcional"
}
```

#### GET /api/distribuciones/{id}
Obtener distribución específica.

**Parámetros de consulta:**
- `with_usuarios` (boolean): Incluir usuarios relacionados

#### PUT/PATCH /api/distribuciones/{id}
Actualizar distribución.

#### DELETE /api/distribuciones/{id}
Eliminar distribución (solo si no tiene usuarios asociados).

### Usuarios de Fiscalización

#### GET /api/usuarios-fiscalizacion
Obtener lista de usuarios con paginación y filtros.

**Parámetros de consulta:**
- `search` (string): Buscar por nombre, email o teléfono
- `status` (string): Filtrar por estado (active/inactive)
- `distribucion_id` (int): Filtrar por distribución
- `with_distribucion` (boolean): Incluir distribución relacionada
- `per_page` (int): Elementos por página (default: 15)

#### POST /api/usuarios-fiscalizacion
Crear nuevo usuario.

**Body:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "Password123",
  "cellphone": "+591 70123456",
  "status": "active",
  "distribucion_id": 1
}
```

#### GET /api/usuarios-fiscalizacion/{id}
Obtener usuario específico.

#### PUT/PATCH /api/usuarios-fiscalizacion/{id}
Actualizar usuario.

#### DELETE /api/usuarios-fiscalizacion/{id}
Eliminar usuario.

#### PATCH /api/usuarios-fiscalizacion/{id}/toggle-status
Cambiar estado del usuario (active/inactive).

### Rutas Anidadas

#### GET /api/distribuciones/{id}/usuarios
Obtener usuarios de una distribución específica.

**Parámetros de consulta:**
- `status` (string): Filtrar por estado
- `per_page` (int): Elementos por página

## Códigos de Estado HTTP

- `200` - OK
- `201` - Created
- `422` - Validation Error
- `404` - Not Found
- `500` - Server Error

## Validaciones

### Distribuciones
- `name`: requerido, único, máximo 255 caracteres
- `description`: opcional, máximo 1000 caracteres

### Usuarios Fiscalización
- `name`: requerido, máximo 255 caracteres
- `email`: requerido, email válido, único
- `password`: requerido, mínimo 8 caracteres, mayúsculas, minúsculas y números
- `cellphone`: requerido, máximo 20 caracteres
- `status`: opcional, valores: active/inactive
- `distribucion_id`: requerido, debe existir en distribuciones

## Headers para Endpoints Protegidos

Todos los endpoints (excepto login) requieren autenticación:
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

## Usuarios de Prueba

Puedes usar estos usuarios para hacer login:
- **juan.perez@fiscalizacion.com** / Password123
- **maria.garcia@fiscalizacion.com** / Password123  
- **carlos.lopez@fiscalizacion.com** / Password123
- **luis.martinez@fiscalizacion.com** / Password123

**Nota:** ana.rodriguez@fiscalizacion.com está INACTIVA