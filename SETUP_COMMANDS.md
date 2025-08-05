# Comandos de Configuración - Sistema de Fiscalización

## 🚀 Configuración Inicial (YA EJECUTADOS)

### 1. Ejecutar migraciones
```bash
php artisan migrate
```

### 2. Ejecutar seeders (datos de prueba)
```bash
php artisan db:seed
```

### 3. Instalar Laravel Sanctum (opcional - ya instalado)
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## 🔧 Comandos de Mantenimiento

### 4. Limpiar cache (si es necesario)
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### 5. Generar clave de aplicación (si no existe)
```bash
php artisan key:generate
```

### 6. Iniciar servidor de desarrollo
```bash
php artisan serve
# Servidor disponible en: http://127.0.0.1:8000
```

## 📋 Verificación del Sistema

### 7. Verificar rutas API
```bash
php artisan route:list --path=api
```

### 8. Verificar estado de migraciones
```bash
php artisan migrate:status
```

### 9. Testing (si tienes tests)
```bash
php artisan test
```

## 🧪 Pruebas Rápidas de la API

### 10. Probar endpoint básico
```bash
curl -X GET "http://127.0.0.1:8000/api/distribuciones" \
  -H "Accept: application/json"
```

### 11. Crear distribución de prueba
```bash
curl -X POST "http://127.0.0.1:8000/api/distribuciones" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name": "Distribución Prueba", "description": "Test"}'
```

### 12. Registrar usuario de prueba
```bash
curl -X POST "http://127.0.0.1:8000/api/usuarios-fiscalizacion" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Usuario Test",
    "email": "test@example.com",
    "password": "TestPassword123",
    "cellphone": "+591 70000000",
    "distribucion_id": 1
  }'
```

## 🛠️ Comandos de Desarrollo (para expandir la API)

### Crear migraciones adicionales
```bash
php artisan make:migration create_table_name
```

### Crear controladores
```bash
php artisan make:controller Api/ControllerName --api
```

### Crear requests de validación
```bash
php artisan make:request RequestName
```

### Crear resources para respuestas
```bash
php artisan make:resource ResourceName
```

### Crear factories para testing
```bash
php artisan make:factory ModelFactory
```

### Crear seeders
```bash
php artisan make:seeder SeederName
```

## 🔄 Comandos de Reset (si necesitas empezar de nuevo)

### Resetear base de datos y seeders
```bash
php artisan migrate:fresh --seed
```

### Solo resetear migraciones
```bash
php artisan migrate:fresh
```

## 📊 Estado Actual del Sistema

- ✅ **Base de datos:** Configurada con distribuciones y usuarios
- ✅ **API:** Funcionando sin autenticación
- ✅ **Seeders:** 5 distribuciones y 5 usuarios de prueba
- ✅ **Endpoints:** 13 rutas API disponibles
- ✅ **Validaciones:** Completas con mensajes en español
- ✅ **Servidor:** http://127.0.0.1:8000

## 🎯 Próximos Pasos Sugeridos

1. **Probar todos los endpoints** con Postman o curl
2. **Integrar con tu app móvil** usando los endpoints
3. **Agregar autenticación** si es necesario (descomenta en routes/api.php)
4. **Personalizar validaciones** según tus necesidades específicas
5. **Agregar logging** para acciones importantes