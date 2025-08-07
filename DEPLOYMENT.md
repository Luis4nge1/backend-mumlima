# Deployment en Google Cloud Run

## 📋 Pasos para desplegar desde GitHub en Cloud Run

### 1. **Configurar Cloud Build Trigger**

1. Ve a Google Cloud Console → Cloud Build → Triggers
2. Conecta tu repositorio GitHub
3. Crea un nuevo trigger:
   - **Nombre**: `deploy-backend`
   - **Evento**: Push to branch
   - **Branch**: `^main$` o `^master$`
   - **Configuración**: Cloud Build configuration file
   - **Archivo**: `cloudbuild.yaml`

### 2. **Configurar permisos**

```bash
# Dar permisos a Cloud Build para desplegar en Cloud Run
gcloud projects add-iam-policy-binding certain-purpose-465906-k5 \
    --member="serviceAccount:$(gcloud projects describe certain-purpose-465906-k5 --format='value(projectNumber)')@cloudbuild.gserviceaccount.com" \
    --role="roles/run.admin"

gcloud projects add-iam-policy-binding certain-purpose-465906-k5 \
    --member="serviceAccount:$(gcloud projects describe certain-purpose-465906-k5 --format='value(projectNumber)')@cloudbuild.gserviceaccount.com" \
    --role="roles/iam.serviceAccountUser"
```

### 3. **Primer deployment**

1. Haz push a tu rama main/master
2. Cloud Build se ejecutará automáticamente
3. El servicio se desplegará en Cloud Run

### 4. **Configurar variables de entorno**

Después del primer deployment, ejecuta:

```bash
chmod +x setup-env-vars.sh
./setup-env-vars.sh
```

O configura manualmente en Cloud Console:
- Ve a Cloud Run → tu servicio → Variables y secretos
- Agrega las variables necesarias

### 5. **Variables importantes a configurar**

```
APP_KEY=base64:tu_clave_generada
APP_URL=https://tu-servicio-url.a.run.app
DB_HOST=tu_cloud_sql_ip
DB_DATABASE=fiscalizacion_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

## 🔄 Deployments posteriores

Simplemente haz push a main/master y Cloud Build se encargará automáticamente.

## 🐛 Troubleshooting

- **Logs**: Ve a Cloud Run → tu servicio → Logs
- **Variables**: Verifica en Cloud Run → tu servicio → Variables y secretos
- **Build logs**: Ve a Cloud Build → Historial

## 📊 Monitoreo

- **URL del servicio**: Se genera automáticamente
- **Métricas**: Disponibles en Cloud Run Console
- **Logs**: Integrados con Cloud Logging