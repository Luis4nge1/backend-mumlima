# Deployment en Google Cloud Run

## 📋 Pasos para desplegar desde GitHub

### 1. **Configurar Cloud Build Trigger**

1. Ve a Google Cloud Console → Cloud Build → Triggers
2. Conecta tu repositorio GitHub
3. Crea un nuevo trigger:
   - **Nombre**: `deploy-backend`
   - **Evento**: Push to branch
   - **Branch**: `^main$` o `^master$`
   - **Configuración**: Cloud Build configuration file
   - **Archivo**: `cloudbuild.yaml`

### 2. **⚠️ IMPORTANTE: Configurar permisos (OBLIGATORIO)**

**Estos comandos son NECESARIOS para que Cloud Build pueda desplegar en Cloud Run:**

```bash
# Dar permisos a Cloud Build para desplegar en Cloud Run
gcloud projects add-iam-policy-binding name-project \
    --member="serviceAccount:$(gcloud projects describe name-project --format='value(projectNumber)')@cloudbuild.gserviceaccount.com" \
    --role="roles/run.admin"

gcloud projects add-iam-policy-binding name-project \
    --member="serviceAccount:$(gcloud projects describe name-project --format='value(projectNumber)')@cloudbuild.gserviceaccount.com" \
    --role="roles/iam.serviceAccountUser"
```

**Sin estos permisos, el deployment fallará.**

### 3. **Primer deployment**

1. Haz push a tu rama main/master
2. Cloud Build se ejecutará automáticamente
3. El servicio se desplegará en Cloud Run

### 4. **⚠️ CRÍTICO: Configurar variables de entorno**

**El servicio NO funcionará sin las variables de entorno. Ejecuta este comando después del primer deployment:**

```bash
# Reemplaza 'tu-servicio-name' con el nombre real de tu servicio
gcloud run services update tu-servicio-name \
  --region=us-central1 \
  --set-env-vars="
APP_NAME=Laravel,
APP_ENV=production,
APP_KEY=base64:$(openssl rand -base64 32),
APP_DEBUG=false,
APP_URL=https://tu-servicio-name.us-central1.run.app,
LOG_CHANNEL=stderr,
LOG_LEVEL=info,
DB_CONNECTION=mysql,
DB_HOST=127.0.0.1,
DB_PORT=3306,
DB_DATABASE=fiscalizacion_db,
DB_USERNAME=root,
DB_PASSWORD=tu_password,
SESSION_DRIVER=cookie,
SESSION_LIFETIME=120,
CACHE_DRIVER=file,
QUEUE_CONNECTION=sync,
MAIL_MAILER=log
"
```

### 5. **Verificar que funciona**

```bash
# Probar API
curl https://tu-servicio-name.us-central1.run.app/api/distribuciones
```

## 🔄 Deployments posteriores

Simplemente haz push a main/master y Cloud Build se encargará automáticamente.

## 🐛 Troubleshooting

### **Si el deployment falla:**
1. **Verificar permisos**: Ejecutar los comandos del paso 2
2. **Ver logs de build**: Cloud Build → Historial
3. **Verificar cloudbuild.yaml**: Debe estar en la raíz del repo

### **Si el servicio da "Server Error":**
1. **Configurar variables de entorno**: Paso 4 es obligatorio
2. **Ver logs del servicio**: Cloud Run → tu servicio → Logs
3. **Probar health check**: `/health` endpoint

### **Comandos útiles:**
```bash
# Ver logs en tiempo real
gcloud logs tail --service=tu-servicio-name --region=us-central1

# Ver variables actuales
gcloud run services describe tu-servicio-name --region=us-central1

# Actualizar una variable específica
gcloud run services update tu-servicio-name \
  --region=us-central1 \
  --set-env-vars="APP_DEBUG=true"
```

## 📊 Monitoreo

- **URL del servicio**: Se genera automáticamente
- **Health check**: `https://tu-servicio.run.app/health`
- **Métricas**: Cloud Run Console
- **Logs**: Cloud Logging integrado

## ✅ Checklist de deployment exitoso

- [ ] Cloud Build trigger configurado
- [ ] Permisos de Cloud Build configurados
- [ ] Primer push realizado
- [ ] Variables de entorno configuradas
- [ ] Health check responde correctamente
- [ ] API endpoints funcionan