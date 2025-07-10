#!/bin/bash
echo "Parando ambiente atual (se houver)..."
docker-compose down
echo "Iniciando ambiente local..."
docker-compose --env-file .env.local up --build -d
echo "Ambiente local iniciado. Acesse em https://app.ucredcredito.com/"
