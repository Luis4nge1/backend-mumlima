#!/bin/bash

# Script para probar el contenedor localmente antes de Cloud Run

echo "🔨 Construyendo imagen..."
docker build -t fiscalizacion-test .

echo "🚀 Iniciando contenedor en puerto 8080..."
docker run -d --name fiscalizacion-test -p 8080:8080 fiscalizacion-test

echo "⏳ Esperando 10 segundos para que inicie..."
sleep 10

echo "🔍 Probando endpoints..."
echo "Health check:"
curl -f http://localhost:8080/health || echo "❌ Health check falló"

echo -e "\nAPI test:"
curl -f http://localhost:8080/api/distribuciones || echo "❌ API falló"

echo -e "\n📋 Logs del contenedor:"
docker logs fiscalizacion-test

echo -e "\n🧹 Limpiando..."
docker stop fiscalizacion-test
docker rm fiscalizacion-test

echo "✅ Prueba completada"