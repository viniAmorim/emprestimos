# docker/apache/Dockerfile
FROM php:8.2-apache

ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ENV APP_ROOT=/var/www/app
ENV SERVER_NAME=app.ucredcredito.com
ENV DOCUMENT_ROOT=${APP_ROOT}/public_html
ENV APACHE_LOG_DIR=/var/log/apache2 
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_RUN_USER=www-data

RUN apt-get update && apt-get install -y \
    tzdata \
    openssl \
    apache2-utils \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p ${APACHE_LOG_DIR} && chown -R ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} ${APACHE_LOG_DIR}

WORKDIR ${APP_ROOT}

RUN mkdir -p ${DOCUMENT_ROOT} \
    && chown -R ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} ${DOCUMENT_ROOT}

RUN a2enmod ssl \
    && a2enmod rewrite \
    && a2enmod proxy \
    && a2enmod proxy_fcgi \
    && a2enmod headers

# --- Geração de Certificados Autoassinados para LOCALHOST (dentro do container) ---
RUN mkdir -p /etc/apache2/ssl_certs_localhost \
    && openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout /etc/apache2/ssl_certs_localhost/server.key \
        -out /etc/apache2/ssl_certs_localhost/server.crt \
        -subj "/CN=localhost" \
    && chmod 600 /etc/apache2/ssl_certs_localhost/server.key

# Copia os VirtualHosts customizados
COPY app.ucredcredito.com.conf /etc/apache2/sites-available/app.ucredcredito.com.conf
COPY localhost.conf /etc/apache2/sites-available/localhost.conf

# Habilita os VirtualHosts e desabilita o padrão
RUN a2ensite app.ucredcredito.com.conf \
    && a2ensite localhost.conf \
    && a2dissite 000-default.conf

EXPOSE 80 443