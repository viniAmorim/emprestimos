FROM httpd:2.4-alpine

# Fuso horário
ENV TZ=America/Sao_Paulo
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo "${TZ}" > /etc/timezone

# Instala OpenSSL e utilitários necessários
RUN apk add --no-cache \
    apache2-ssl \
    apache2-utils \
    openssl \
    && rm -rf /var/cache/apk/*

# Configuração de variáveis
ENV APP_ROOT=/var/www/app
ENV SERVER_NAME=app.ucredcredito.com
ENV DOCUMENT_ROOT=${APP_ROOT}/public_html
ENV APACHE_LOG_DIR=${APP_ROOT}/docker/apache/logs
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_RUN_USER=www-data

# Criar diretórios e permissões
WORKDIR ${APP_ROOT}
RUN mkdir -p ${DOCUMENT_ROOT} && \
    chown -R ${APACHE_RUN_USER}:${APACHE_RUN_USER} ${DOCUMENT_ROOT}

# Link simbólico para index.php
RUN ln -s ${APP_ROOT}/public_html/index.php ${DOCUMENT_ROOT}

# Copia sua configuração personalizada do Apache
COPY ./httpd.conf /usr/local/apache2/conf/httpd.conf
COPY ./localhost.conf /usr/local/apache2/conf/extra/localhost.conf

