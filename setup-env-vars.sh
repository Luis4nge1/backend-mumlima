#!/bin/bash

# Script para configurar variables de entorno en Cloud Run
# Ejecutar después del primer deployment

PROJECT_ID="name-project"
SERVICE_NAME="name-service"
REGION="us-central1"

echo "🔧 Configurando variables de entorno en Cloud Run..."

# Generar una APP_KEY aleatoria
APP_KEY=$(openssl rand -base64 32)

gcloud run services update $SERVICE_NAME \
  --region=$REGION \
  --set-env-vars="
APP_NAME=Laravel,
APP_ENV=production,
APP_KEY=base64:$APP_KEY,
APP_DEBUG=false,
APP_URL=https://name-service.us-central1.run.app,
LOG_CHANNEL=stderr,
LOG_LEVEL=info,
DB_CONNECTION=mysql,
DB_HOST=127.0.0.1,
DB_PORT=3306,
DB_DATABASE=fiscalizacion_db,
DB_USERNAME=root,
DB_PASSWORD=tu_password_aqui,
SESSION_DRIVER=cookie,
SESSION_LIFETIME=120,
CACHE_DRIVER=file,
QUEUE_CONNECTION=sync,
MAIL_MAILER=log
"

echo "✅ Variables de entorno configuradas!"
echo "🔑 APP_KEY generada: base64:$APP_KEY"
echo ""
echo "⚠️  IMPORTANTE: Actualiza estas variables según tu configuración:"
echo "   - APP_URL: Con la URL real de tu servicio"
echo "   - DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD: Con datos de Cloud SQL"