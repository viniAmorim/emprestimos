#!/bin/bash
echo "Parando ambiente atual (se houver)..."
docker-compose down
echo "Iniciando ambiente remoto..."
docker-compose --env-file .env.remote up --build -d
echo "Ambiente remoto iniciado."
