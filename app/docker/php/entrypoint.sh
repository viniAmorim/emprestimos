#!/bin/bash

# --- Início do Script de Entrypoint Personalizado para PHP ---

echo "--- Executando script de entrypoint personalizado para PHP ---"

# Definir proprietário e permissões para a pasta de imagens (VOLUME NOMEADO)
# Embora seja um volume nomeado, forçamos as permissões para garantir que www-data tenha acesso total.
echo "Aplicando chown e chmod para /var/www/app/public_html/painel/images..."
chown -R www-data:www-data /var/www/app/public_html/painel/images
chmod -R 775 /var/www/app/public_html/painel/images

# Pré-criar os subdiretórios de upload com as permissões corretas
echo "Pré-criando subdiretórios de upload..."
UPLOAD_BASE_DIR="/var/www/app/public_html/painel/images"
sudo -u www-data mkdir -p "${UPLOAD_BASE_DIR}/comprovantes"
sudo -u www-data mkdir -p "${UPLOAD_BASE_DIR}/clientes"
sudo -u www-data mkdir -p "${UPLOAD_BASE_DIR}/documentos_renda"
sudo -u www-data mkdir -p "${UPLOAD_BASE_DIR}/prints_apps"

# Verificação das permissões dos novos diretórios
echo "Verificando permissões dos subdiretórios criados:"
ls -ld "${UPLOAD_BASE_DIR}/comprovantes"
ls -ld "${UPLOAD_BASE_DIR}/clientes"
ls -ld "${UPLOAD_BASE_DIR}/documentos_renda"
ls -ld "${UPLOAD_BASE_DIR}/prints_apps"

echo "--- Diagnóstico Concluído. Iniciando PHP-FPM ---"

# Executar o comando original do ENTRYPOINT da imagem PHP-FPM
# Isso garante que o PHP-FPM inicie corretamente
exec "$@"
