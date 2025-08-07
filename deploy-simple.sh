#!/bin/bash

# Deployment simple para Cloud Run
PROJECT_ID="certain-purpose-465906-k5"
SERVICE_NAME="backend-cloud-run-mumlima"
REGION="us-central1"

echo "🚀 Desplegando $SERVICE_NAME..."

# Construir y subir imagen
gcloud builds submit --tag gcr.io/$PROJECT_ID/$SERVICE_NAME

# Desplegar con configuración específica
gcloud run deploy $SERVICE_NAME \
  --image gcr.io/$PROJECT_ID/$SERVICE_NAME \
  --platform managed \
  --region $REGION \
  --allow-unauthenticated \
  --memory 1Gi \
  --cpu 1 \
  --timeout 900 \
  --concurrency 80 \
  --max-instances 10 \
  --port 8080 \
  --set-env-vars "APP_ENV=production,APP_DEBUG=false,LOG_CHANNEL=stderr"

echo "✅ Deployment completado!"