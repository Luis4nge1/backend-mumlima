# Backend Fiscalización API

API REST desarrollada en Laravel para sistema de fiscalización con gestión de distribuciones, entidades y usuarios.

## Características

- **Gestión de Distribuciones**: CRUD completo para distribuciones geográficas
- **Gestión de Entidades**: Sistema jerárquico de entidades gubernamentales
- **Usuarios de Fiscalización**: Gestión completa de usuarios con autenticación
- **Relaciones**: Sistema de relaciones entre distribuciones, entidades y usuarios
- **Validaciones**: Validaciones robustas y mensajes de error en español
- **Protecciones**: Prevención de eliminaciones que comprometan integridad de datos

## Instalación

```bash
# Clonar repositorio
git clone <repository-url>
cd backend-fiscalizacion

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fiscalizacion
DB_USERNAME=root
DB_PASSWORD=

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Iniciar servidor
php artisan serve
```

## API Endpoints

### Distribuciones

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/distribuciones` | Listar distribuciones |
| POST | `/api/distribuciones` | Crear distribución |
| GET | `/api/distribuciones/{id}` | Ver distribución |
| PUT/PATCH | `/api/distribuciones/{id}` | Actualizar distribución |
| DELETE | `/api/distribuciones/{id}` | Eliminar distribución |
| GET | `/api/distribuciones/{id}/usuarios` | Usuarios por distribución |

**Parámetros de consulta:**
- `?search=texto` - Búsqueda por nombre o descripción
- `?with_usuarios_count=true` - Incluir contador de usuarios
- `?with_usuarios=true` - Incluir usuarios relacionados
- `?per_page=15` - Paginación

### Tipos de Entidad

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/entity-types` | Listar tipos de entidad |
| POST | `/api/entity-types` | Crear tipo de entidad |
| GET | `/api/entity-types/{id}` | Ver tipo de entidad |
| PUT/PATCH | `/api/entity-types/{id}` | Actualizar tipo de entidad |
| DELETE | `/api/entity-types/{id}` | Eliminar tipo de entidad |

**Parámetros de consulta:**
- `?search=texto` - Búsqueda por nombre o descripción
- `?with_entities_count=true` - Incluir contador de entidades
- `?with_entities=true` - Incluir entidades relacionadas

### Entidades

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/entities` | Listar entidades |
| POST | `/api/entities` | Crear entidad |
| GET | `/api/entities/{id}` | Ver entidad |
| PUT/PATCH | `/api/entities/{id}` | Actualizar entidad |
| DELETE | `/api/entities/{id}` | Eliminar entidad |
| GET | `/api/entities/hierarchy` | Ver jerarquía completa |
| GET | `/api/entities/{id}/usuarios` | Usuarios por entidad |

**Parámetros de consulta:**
- `?search=texto` - Búsqueda por nombre, descripción o email
- `?parent_id=1` - Filtrar por entidad padre
- `?parent_id=null` - Solo entidades raíz
- `?type_id=1` - Filtrar por tipo de entidad
- `?with_parent=true` - Incluir entidad padre
- `?with_children=true` - Incluir entidades hijas
- `?with_type=true` - Incluir tipo de entidad
- `?with_usuarios_fiscalizacion=true` - Incluir usuarios
- `?with_children_count=true` - Incluir contador de hijos
- `?with_usuarios_fiscalizacion_count=true` - Incluir contador de usuarios

### Usuarios de Fiscalización

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/usuarios-fiscalizacion` | Listar usuarios |
| POST | `/api/usuarios-fiscalizacion` | Crear usuario |
| GET | `/api/usuarios-fiscalizacion/{id}` | Ver usuario |
| PUT/PATCH | `/api/usuarios-fiscalizacion/{id}` | Actualizar usuario |
| DELETE | `/api/usuarios-fiscalizacion/{id}` | Eliminar usuario |
| POST | `/api/usuarios-fiscalizacion/login` | Login con email/password |
| PATCH | `/api/usuarios-fiscalizacion/{id}/toggle-status` | Cambiar estado |
| PUT | `/api/usuarios-fiscalizacion/{id}/profile` | Actualizar perfil |
| PATCH | `/api/usuarios-fiscalizacion/{id}/password` | Cambiar contraseña |
| PATCH | `/api/usuarios-fiscalizacion/{id}/reset-password` | Resetear contraseña (admin) |

**Parámetros de consulta:**
- `?search=texto` - Búsqueda por nombre, email o teléfono
- `?status=active` - Filtrar por estado
- `?distribucion_id=1` - Filtrar por distribución
- `?entity_id=1` - Filtrar por entidad
- `?with_distribucion=true` - Incluir distribución
- `?with_entity=true` - Incluir entidad

## Estructura de Datos

### Distribución
```json
{
  "id": 1,
  "name": "Distribución Norte",
  "description": "Área norte de la ciudad",
  "created_at": "2025-08-08T15:07:17.000000Z",
  "updated_at": "2025-08-08T15:07:17.000000Z"
}
```

### Tipo de Entidad
```json
{
  "id": 1,
  "name": "Ministerio",
  "description": "Entidad gubernamental de nivel ministerial",
  "created_at": "2025-08-08T15:07:17.000000Z",
  "updated_at": "2025-08-08T15:07:17.000000Z"
}
```

### Entidad
```json
{
  "id": 1,
  "parent_id": null,
  "type_id": 1,
  "name": "Ministerio de Economía y Finanzas Públicas",
  "short_name": "MEFP",
  "description": "Ministerio encargado de la política económica",
  "contact_email": "contacto@economiayfinanzas.gob.bo",
  "contact_phone": "+591 2 2582000",
  "address": "Av. Mariscal Santa Cruz, La Paz, Bolivia",
  "created_at": "2025-08-08T15:07:17.000000Z",
  "updated_at": "2025-08-08T15:07:17.000000Z"
}
```

### Usuario de Fiscalización
```json
{
  "id": 1,
  "name": "Juan Pérez",
  "email": "juan.perez@fiscalizacion.com",
  "cellphone": "+591 70123456",
  "status": "active",
  "distribucion_id": 1,
  "entity_id": 1,
  "created_at": "2025-08-08T15:07:17.000000Z",
  "updated_at": "2025-08-08T15:07:17.000000Z"
}
```

## Validaciones y Protecciones

- **Distribuciones**: No se pueden eliminar si tienen usuarios asignados
- **Tipos de Entidad**: No se pueden eliminar si tienen entidades asociadas
- **Entidades**: No se pueden eliminar si tienen entidades hijas o usuarios asignados
- **Referencias Circulares**: Validación automática en jerarquías de entidades
- **Emails Únicos**: Validación de unicidad en usuarios con ignore en actualizaciones

## Seeders Incluidos

- **DistribucionSeeder**: 5 distribuciones de ejemplo
- **EntityTypeSeeder**: 7 tipos de entidad (Ministerio, Viceministerio, etc.)
- **EntitySeeder**: 6 entidades con jerarquía (2 ministerios, 2 viceministerios, etc.)
- **UsuarioFiscalizacionSeeder**: 5 usuarios con relaciones asignadas

## Tecnologías

- **Laravel 11**: Framework PHP
- **MySQL**: Base de datos
- **Eloquent ORM**: Mapeo objeto-relacional
- **Laravel Sanctum**: Autenticación API (preparado)
- **Form Requests**: Validaciones robustas
- **API Resources**: Serialización consistente
